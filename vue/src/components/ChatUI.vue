<template>
    <div id="chat">
            <div class="w-100">
                <div v-for="m in messages" key="m" :class="m.author == 'bot' ? 'message-bot' : 'message--human'">
                    <div :class="m.author == 'bot' ? 'chat-message ml-auto user-bot' : 'chat-message user-human'">
                        <i v-if="m.message.length == 0" class="fa fa-spinner fa-spin"></i>
                        <div v-html="m.message"></div>
                        <div v-if="m.author == 'bot' && m.message.length > 0" class="message-actions">
                            <i class="fa fa-copy"></i>
                            <i class="fa fa-thumb-up"></i>
                            <i class="fa fa-thumb-down"></i>
                        </div>
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
                        <button class="btn btn-primary" @click="handleChatMessage" :disabled="chat_message.length == 0">
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

export default Vue.extend({
    name: "ChatUI",
    props: {
        messages: Object,
    },
    data() {
        return {
            chat_message: "",
            error_msg: '',
        };
    },
    mounted: function () { },
    methods: {
        ...mapGetters({
            rag_webservice_host: 'getRAGWebserviceHost',
        }),
        handleEnter(event) {
            if (event.shiftKey) {
            return; // Do nothing if Shift + Enter is pressed
            }
            // Fire event only when Enter is pressed alone
            this.handleChatMessage();
        },
        handleChatMessage: function(){
            this.$emit('requestChatResponse', this.chat_message)
            this.chat_message = ""; // reset input field
        },
        resizeTextarea: function() {
            const textarea = this.$refs.chatTextarea;
            textarea.style.height = "auto";  // Reset height
            textarea.style.height = textarea.scrollHeight + "px"; // Set height dynamically
        },
    },
});
</script>

<style scoped>

#chat {
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

</style>