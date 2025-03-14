<template>
    <div id="container" class="content">
        <div class="w100">
            <h3 class="d-flex justify-content-between* align-items-center*">
                Dokumentan Chat
                <i class="fa fa-cog ml-3 mt-1" style="font-size:0.8em; color:#555;" @click="show_settings=!show_settings"></i>
            </h3>
        </div>
        <div v-if="show_settings" class="settings">
            <span v-if="documents.length > 0" class="bold">Ausgewählte Dokumente</span>
            <table v-if="documents.length > 0" class="document-table">
                <thead>
                <tr>
                    <th>Auswahl</th>
                    <th>Dokument</th>
                    <th>Aktivitätstyp</th>
                    <th>Aktionen</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="doc in documents" :key="doc.id">
                    <td>
                    <input type="checkbox" v-model="doc.selected" />
                    </td>
                    <td>{{ doc.file.name }}</td>
                    <td>{{ doc.activity_type }}</td>
                    <td>
                    <i class="fa fa-trash delete-icon" @click="removeDocument(doc.id)"></i>
                    </td>
                </tr>
                </tbody>
            </table>
            
            <div class="mt-3">
                <RAGupload @document_uploaded="addDocument"></RAGupload>
                <span style="background-color: red;">{{ error_msg }}</span>
            </div>
            <div>
                Ressource aus dem Kurs als Dokument hinzufügen;
                [todo: page, longpage, wiki, forum, assign]
            </div>
        </div>
        
        <div id="chat" class="chat">
            <div class="w-100">
                <div v-for="m in messages" key="m" :class="m.author == 'bot' ? 'chat-message ml-auto' : 'chat-message'"
                    :style="m.author == 'bot'
                            ? 'background-color:azure;'
                            : 'background-color:cornsilk;'
                        ">
                    {{ m.message }}
                </div>
            </div>
            <div class="row w-100 chat-input">
                <input type="text" class="col-6" v-model="chat_message" @keyup.enter="requestServerChat"
                    placeholder="" />
                <button class="btn btn-primary col-2" @click="requestServerChat">send</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { mapGetters } from 'vuex'
import RAGupload from "./RAGupload.vue";

export default Vue.extend({
    name: "OpenChat",
    components: {
        RAGupload: RAGupload
    },
    data() {
        return {
            show_settings: true,
            chat_message: "",
            messages: [],
            documents: [],
            document_index: [],
            error_msg: '',
        };
    },
    mounted: function () { },
    methods: {
        ...mapGetters({
            rag_webservice_host: 'getRAGWebserviceHost',
            hostname: 'getHostname', 
            model: 'getModel', 
            prompttemplate: 'getPrompttemplate',
            coursemoduleid: 'getCourseModuleId',
            pageinstanceid: 'getPageInstanceId',
        }),
        requestClientChat: async function () {
            let _this = this;
            let message = this.chat_message;
            this.chat_message = ""; // reset input field
            //@ts-ignore
            this.messages.push({ author: "user", message: message });
            //@ts-ignore
            let message_pos = this.messages.push({ author: "bot", message: "" });

            // default
            let url = "http://localhost/llm/query";//this.hostname();
            let payload = {
                "model": this.model(),//"llama3.1",
                "prompt": message,
            };
            const apiKey =
                "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjczYmUyMGFiLWI4YjYtNDNmNS05YmZjLWIzMDU1OGZkODZiYyJ9.7QCdTgHAPVvTJgkbr7NLxYcO4iUTwlL4ai6rfw_neXE"; // Replace with your actual API key
            

            try {
                // send request
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: "Bearer " + apiKey,
                    },
                    body: JSON.stringify(payload),
                });
                console.log(response);    
                if (!response.ok) {
                    throw new Error("HTTP error! Status:" + response.status);
                }

                // Handle response
                //@ts-ignore
                const reader = response.body != null ? response.body.getReader() : null;
                const decoder = new TextDecoder("utf-8");
                let done = false;

                while (!done) {
                    //@ts-ignore
                    const { value, done: readerDone } = await reader.read();
                    done = readerDone;

                    if (value) {
                        const chunk = decoder.decode(value, { stream: true });
                        let res = "";
                        try {
                            res = JSON.parse(chunk).response;
                        } catch (e) {
                            res = "";
                        }
                        //@ts-ignore
                        _this.messages[message_pos - 1].message = _this.messages[message_pos - 1].message + res;
                    }
                }
            } catch (error) {
                console.error("Error fetching streaming data:", error);
            }
        },
        addDocument: function(response){
            console.log('handle adddocument', response)
            if(response.error){
                this.error_msg = response.msg;
                return;
            }
            this.document_index = response.document_index;
            this.documents.push({
                file: response.file,
                activity_type: response.activity_type,
                activity_id: response.activity_id,
                document_index: response.document_index, // needed? FixMe
                selected: 'selected',
            });
        },
        removeDocument: function(activity_id){
            this.documents = this.documents.filter(doc => doc.id !== activity_id);
        },
        updateDocumentFilter: function(){
            let activities = [];
            let document_types = this.documents.filter(); // todo
            for(let dtype in document_types){
                if(activities[dtype] == null){
                    activities[dtype] = []
                }
                for(let doc in this.documents){
                    if(doc.activity_type == dtype){
                        activities[dtype].push(doc.activity_id);
                    }
                    
                }
            }
            this.document_filter = {
                'system': ['aple-demo-moodle'], // FixMe: this.moodle
                'courses': [0], // FixMe: this.course_id
            }
            for(let a in activities){
                this.document_filter[a] = activities[a];
            }
            console.log(this.document_filter)
            return this.document_filter;
        },
        requestServerChat: async function () {
            let _this = this;
            //@ts-ignore
            let message = this.chat_message;
            this.chat_message = ""; // reset input field
            //@ts-ignore
            this.messages.push({ author: "user", message: message });
            //@ts-ignore
            let message_pos = this.messages.push({ author: "bot", message: "" });

            let postData = new FormData();
            postData.append('model', this.model());
            postData.append('hostname', "http://localhost/llm/query_documents");//this.hostname());
            postData.append('document_index', this.document_index);
            postData.append('filter', this.document_filter);
            postData.append('coursemoduleid', this.coursemoduleid());
            postData.append('pageinstanceid', this.pageinstanceid());
            postData.append('prompt', message);

            try {
                const response = await fetch(M.cfg.wwwroot + "/mod/openchat/llm_rag_stream.php", {
                    method: "POST",
                    body: postData,
                });

                if (!response.body) {
                    throw new Error(
                        "ReadableStream is not supported in this environment."
                    );
                }

                const reader = response.body.getReader();
                const decoder = new TextDecoder("utf-8");
                let done = false;

                while (!done) {
                    const { value, done: streamDone } = await reader.read();
                    done = streamDone;
                    if (value) {
                        const chunk = decoder.decode(value, { stream: true });
                        let res = "";
                        try {
                            res = JSON.parse(chunk).response;
                        } catch (e) {
                            res = "";
                        }
                        //@ts-ignore
                        _this.messages[message_pos - 1].message = _this.messages[message_pos - 1].message + res;
                        //outputElement.textContent += chunk; // Append data to the output element
                    }
                }
            } catch (error) {
                console.error("Error fetching streaming data:", error);
            }
        },
    },
});
</script>

<style scoped>
.content {
    border: none;
    max-width: 830px;
    margin: 0 auto;
}

.content .settings{
    display: block;
    width: 500px;
}

.content .chat{
    display: block;
    width: 500px;
}

#chat input {
    font-size: 1.1em;
    padding: 2px;
    margin-right: 2px;
}

#chat .chat-input {
    margin-top: 30px;
    padding-top: 10px;
    border-top: #222222 1px solid;
}

#chat .chat-message {
    display: block;
    padding: 8px 10px;
    font-size: 1.1em;
    margin-bottom: 5px;
    width: 300px;
    border-radius: 2% 2%;
    border: solid 1px #555;
}

.document-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.document-table th,
.document-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.document-table th {
  background-color: #f4f4f4;
}

.delete-icon {
  color: #555;
  cursor: pointer;
}

.delete-icon:hover {
  color: red;
}


</style>