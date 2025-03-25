<template>
    <div id="container" class="content">
        <div class="chat-header mb-3 w100">
            <h3 class="d-flex justify-content-between* align-items-center* mb-3">
                Dokumenten-Chat
                <i class="fa fa-cog ml-3 mt-1 settings-icon" style="font-size:0.8em; color:#555;"
                    @click="$store.commit('toggleShowSettings', 1)"></i>
            </h3>

            <RAGChatSettings 
                v-if="$store.getters.showSettings" 
                :documents="documents" 
                />

        </div>

        <ChatUI 
            :messages="messages"
            @requestChatResponse="requestDocumentChat" 
            />
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { mapGetters } from 'vuex'
import RAGChatSettings from "./RAGChatSettings.vue";
import ChatUI from "./ChatUI.vue";

export default Vue.extend({
    name: "RAGChat",
    components: {
        RAGChatSettings: RAGChatSettings,
        ChatUI: ChatUI
    },
    data() {
        return {
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
        requestDocumentChat: async function (message) {
            console.log('requestDocumentChat');
            let _this = this;
            //@ts-ignore
            this.messages.push({ author: "user", message: message });
            //@ts-ignore
            let message_pos = this.messages.push({ author: "bot", message: "" });

            // default
            let url = this.$store.getters.getRAGWebserviceHost + "llm/query_documents";
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
    },
});
</script>

<style scoped>
.content {
    border: none;
    max-width: 830px;
    margin: 0 auto;
}

.settings-icon:hover{
    color:blue;
    cursor:pointer;
}
</style>