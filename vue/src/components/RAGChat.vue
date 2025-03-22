<template>
    <div id="container" class="content">
        <div class="chat-header mb-3 w100">
            <h3 class="d-flex justify-content-between* align-items-center* mb-3">
                Dokumentan Chat
                <i class="fa fa-cog ml-3 mt-1 settings-icon" style="font-size:0.8em; color:#555;"
                    @click="show_settings = !show_settings"></i>
            </h3>

            <RAGChatSettings 
                v-if="show_settings" 
                @init-data="init" 
                @setSimulationData="setSimulationData"
                :documents="documents" 
                @update-graph-params="updateGraphParams" 
                />

        </div>
        <div id="chat" class="chat">
            <div class="w-100">
                <div v-for="m in messages" key="m" :class="m.author == 'bot' ? 'message-bot' : 'message--human'">
                    <div :class="m.author == 'bot' ? 'chat-message ml-auto user-bot' : 'chat-message user-human'">
                        <i v-if="m.message.length == 0" class="fa fa-spinner fa-spin"></i>
                        <div v-html="m.message"></div>
                    </div>
                    <div v-if="m.author == 'bot' && m.message.length > 0" class="message-actions">
                        <i class="fa fa-copy"></i>
                        <i class="fa fa-thumb-up"></i>
                        <i class="fa fa-thumb-down"></i>
                    </div>
                </div>
            </div>
            <div class="w-100 chat-input">
                <div>
                    <textarea 
                        ref="chatTextarea"
                        class="w100 chat-textarea" 
                        v-model="chat_message" 
                        @keyup.enter="handleEnter"
                        @input="resizeTextarea"
                        placeholder="" 
                        />
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <i class="fa fa-dots"></i>
                        <button class="btn btn-primary" @click="requestDocumentChat" :disabled="chat_message.length == 0">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { mapGetters } from 'vuex'
import RAGChatSettings from "./RAGChatSettings.vue";

export default Vue.extend({
    name: "OpenChat",
    components: {
        RAGChatSettings: RAGChatSettings
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
            pluginSettings: 'getPluginSettings',
        }),
        handleEnter(event) {
            if (event.shiftKey) {
            return; // Do nothing if Shift + Enter is pressed
            }
            this.requestDocumentChat(); // Fire event only when Enter is pressed alone
        },
        resizeTextarea: function() {
            const textarea = this.$refs.chatTextarea;
            textarea.style.height = "auto";  // Reset height
            textarea.style.height = textarea.scrollHeight + "px"; // Set height dynamically
        },
        requestDocumentChat: async function () {
            let _this = this;
            let message = this.chat_message;
            this.chat_message = ""; // reset input field
            //@ts-ignore
            this.messages.push({ author: "user", message: message });
            //@ts-ignore
            let message_pos = this.messages.push({ author: "bot", message: "" });

            // default
            let url = "http://localhost:5000/llm/query_documents";//this.hostname();
            let payload = {
                //"model": this.model(),//"llama3.1",
                "filter": [],
                "prompt": message,
            };
            const apiKey = ""; // Replace with your actual API key

            try {
                // send request
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        //Authorization: "Bearer " + apiKey,
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
        
        updateDocumentFilter: function () {
            let activities = [];
            let document_types = this.documents.filter(); // todo
            for (let dtype in document_types) {
                if (activities[dtype] == null) {
                    activities[dtype] = []
                }
                for (let doc in this.documents) {
                    if (doc.activity_type == dtype) {
                        activities[dtype].push(doc.activity_id);
                    }

                }
            }
            this.document_filter = {
                'system': ['aple-demo-moodle'], // FixMe: this.moodle
                'courses': [0], // FixMe: this.course_id
            }
            for (let a in activities) {
                this.document_filter[a] = activities[a];
            }
            console.log(this.document_filter)
            return this.document_filter;
        },
        requestDocumentChat_old: async function () {
            let _this = this;
            if (this.chat_message.length == 0) {
                return;
            }
            //@ts-ignore
            this.messages.push({ author: "user", message: this.chat_message });
            //@ts-ignore
            let message_pos = this.messages.push({ author: "bot", message: "" });

            let postData = new FormData();
            postData.append('model', this.model());
            postData.append('prompt', this.chat_message);
            postData.append('document_index', this.document_index);
            postData.append('filter', this.document_filter);
            postData.append('hostname', "http://localhost:5000/llm/query_documents");//this.hostname());


            postData.append('coursemoduleid', this.coursemoduleid());
            postData.append('pageinstanceid', this.pageinstanceid());

            this.chat_message = ""; // reset input field
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
                console.log(response.body)
                console.log(response)
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



.content .chat {
    display: block;
    width: 500px;
}

#chat .chat-textarea {
  min-height: 40px; /* Minimum height */
  max-height: 300px; /* Limit maximum height */
  overflow-y: auto; /* Allow scrolling for long content */
  width: 100%;
  resize: none; /* Disable manual resizing */
  font-size: 1.1em;
    padding: 2px;
    margin-right: 2px;
}


#chat .chat-input {
    margin-top: 2px;
    padding-top: 10px;
}

#chat .chat-message {
    display: block;
    padding: 8px 10px;
    font-size: 1.1em;
    margin-bottom: 5px;
    width: 500px;
}


.user-bot {
    background-color: #fff;
}

.user-human {
    background-color: #c2c9d6;
    border-color: 0 solid #e3e3e3;
}

.message-actions {
    display: none;
}

.message-bot:hover .message-actions {
    display: block;
}

.settings-icon:hover{
    color:blue;
    cursor:pointer;
}
</style>