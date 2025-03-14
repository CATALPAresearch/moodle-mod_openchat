<template>
    <div id="container" class="content">
        <div id="chat">
            <div class="col-6">
                <div class="box" v-if="is_loading">
                    <i class="fa fa-spin" />
                </div>
            </div>
            <button v-if="!chatStarted" @click="startChat" class="btn btn-primary start-btn">
                Start Chat
            </button>

            <div v-if="chatStarted" class="chat-container">
                <div class="messages">
                    <div v-for="(message, index) in messages" :key="index" :class="{
                        'user-message': message.user,
                        'server-message': !message.user,
                    }">
                        {{ message.text }}
                    </div>
                </div>

                <div class="chat-input">
                    <input v-model="userInput" @keyup.enter="sendMessage" placeholder="Type a message..." />
                    <button @click="sendMessage">Send</button>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script lang="ts">
import axios from "axios";

export default {
    data() {
        return {
            is_loading: false,
            host: 'http://localhost:5000',
            userid: 123, // # FixMe
            chatStarted: false,
            userInput: "",
            messages: [],
        };
    },
    methods: {
        async startChat() {
            this.is_loading = true;
            // curl -X POST http://localhost:5000/startConversation -H "Content-Type: application/json" -d '{ "language": "en", "client": "discord", "userid": "none"}'
            await axios.post(
                this.host + "/startConversation",
                { language: "en", client: "discord", userid: this.userid }
            ).then(response => {
                this.is_loading = false;
                console.log('/startConversation: ', response);
                this.messages.push({
                    text: response.data || "Chat started!",
                    user: 123,
                });
                this.chatStarted = true;
            }).catch(error => {
                this.is_loading = false;
                console.error("Error starting chat:", error);
            });
        },


        async sendMessage() {
            if (!this.userInput.trim()) return;
            this.is_loading = true;
            const message = this.userInput.trim();
            this.messages.push({ text: message, user: true });
            this.userInput = "";

            await axios.post(
                this.host + "/reply", {
                message: message,
                client: "discord",
                userid: this.userid,
            }).then(response => {
                this.is_loading = false;
                console.log('/reply: ', response);
                this.messages.push({ text: response.data, user: false });
                this.wait_video_generation = false
            }).catch(error => {
                this.is_loading = false;
                console.error("Error sending message:", error);
                this.messages.push({
                    text: "Error: Unable to get a response.",
                    user: false,
                });
            });
        },
    },
};
</script>

<style scoped>
.chat-widget {
    max-width: 400px;
    margin: auto;
    font-family: Arial, sans-serif;
}


.chat-container {
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
    background: #f9f9f9;
}

.messages {
    max-height: 300px;
    overflow-y: auto;
    padding: 10px;
    display: flex;
    flex-direction: column;
}

.user-message {
    align-self: flex-end;
    background-color: #007bff;
    color: white;
    padding: 8px;
    border-radius: 10px;
    margin: 5px;
}

.server-message {
    align-self: flex-start;
    background-color: #e0e0e0;
    padding: 8px;
    border-radius: 10px;
    margin: 5px;
}

.chat-input {
    display: flex;
    margin-top: 10px;
}

.chat-input input {
    flex-grow: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.chat-input button {
    padding: 8px 15px;
    margin-left: 5px;
    border: none;
    background-color: #28a745;
    color: white;
    cursor: pointer;
}
</style>
