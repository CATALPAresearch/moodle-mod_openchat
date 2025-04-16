<template>
    <div id="chat" class="container-fluid px-2 py-3">
        <div class="w-100">
            <div v-for="(m, index) in messages" :key="m.id || index">
                <article :class="m.author == 'bot' ? 'chat-message ml-auto user-bot' : 'chat-message user-human'">
                    <div v-if="m.message == ''" role="status" aria-live="polite" aria-atomic="true">
                        <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                        <span class="sr-only">Nachricht wird geladen</span>
                    </div>
                    <div>{{ m.message }}</div>
                    <div v-if="m.author == 'bot' && m.message != ''" class="message-actions">
                        <button type="button" aria-label="Antwort kopieren" title="Kopieren" v-if="!copied" @click="copyMessageToClipboard(m.message)">
                            <i class="fa fa-copy" />
                        </button>
                        <span v-if="copied" aria-live="assertive" class="copied-feedback">
                            <span class="sr-only">Nachricht wurde kopiert</span>
                            <i class="fa fa-check" />
                        </span>
                        <button type="button" aria-label="Antwort positiv bewerten" title="Gefällt mir" @click="sendRating('up', index)">
                            <i class="fa fa-thumbs-up" />
                        </button>
                        <button type="button" aria-label="Antwort negativ bewerten" title="Gefällt mir nicht" @click="sendRating('down', index)">
                            <i class="fa fa-thumbs-down" />
                        </button>
                    </div>
                </article>
            </div>
        </div>
        <fieldset>
            <legend class="sr-only">Neue Nachricht schreiben</legend>
            <div class="w-100 chat-input">
                <div>
                    <label for="chatTextarea" class="sr-only">Gib deine Frage ein</label>
                    <textarea ref="chatTextarea" class="w100 chat-textarea" v-model="chat_message"
                        @keyup.enter="handleEnter" @input="resizeTextarea" placeholder="Frag etwas" role="textbox"/>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <i class="fa fa-dots" aria-hidden="true"></i>
                        <button type="button" class="btn btn-primary" @click="handleChatMessage" :disabled="chat_message.length == 0">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                    </div>
                </div>
            </div>
        </fieldset>
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
            copied: false,
        };
    },
    mounted: function () { },
    methods: {
        ...mapGetters({
            //rag_webservice_host: 'getRAGWebserviceHost',
        }),
        handleEnter(event) {
            if (event.shiftKey) {
                return;
            }
            this.handleChatMessage();
        },
        handleChatMessage: function () {
            this.$emit('requestChatResponse', this.chat_message)
            this.chat_message = ""; // reset input field
            this.$nextTick(() => {
                this.$refs.chatTextarea.focus();
            });
        },
        resizeTextarea: function () {
            const textarea = this.$refs.chatTextarea;
            textarea.style.height = "auto";  // Reset height
            textarea.style.height = textarea.scrollHeight + "px"; // Set height dynamically
        },
        copyMessageToClipboard: function (text) {
            //const el = this.$refs.copyTarget;
            //const text = el.innerText || el.textContent;

            navigator.clipboard.writeText(text).then(() => {
                this.copied = true;
                setTimeout(() => (this.copied = false), 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        },
        sendRating: function (rating, message_index) {
            let params = {
                request: this.messages[message_index - 1],
                response: this.messages[message_index],
                rating: rating,
            };
            // TODO
        }
    },
});
</script>

<style scoped>

.sr-only {
  position: absolute;
  left: -9999px;
  width: 1px;
  height: 1px;
  overflow: hidden;
}


#chat .chat-textarea {
    min-height: 40px;
    /* Minimum height */
    max-height: 300px;
    /* Limit maximum height */
    overflow-y: auto;
    /* Allow scrolling for long content */
    width: 100%;
    resize: none;
    /* Disable manual resizing */
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
    /*padding: 8px 10px;*/
    font-size: 1.1em;
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 0.75rem;
    word-break: break-word;
}

.user-bot {
    background-color: #fff;
}

.user-human {
    background-color: #d9edf7;
    border-color: 0 solid #d9edf7;
}

.message-actions {
    margin-top: 10px;
    min-height: 20px;
}

.message-actions * {
    display: inline;
    margin-right: 10px;
    font-size: 1.1em;
    color: #7d7575;
    border-radius: 4px;
    padding: 5px;
}

.message-actions *:hover {
    background-color: #dadde4;
}

.message-bot:hover .message-actions * {
    display: inline;
}

</style>