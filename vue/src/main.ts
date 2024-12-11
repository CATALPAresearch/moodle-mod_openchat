import Vue from "vue";
import ChatApp from "./ChatApp.vue";

function initOpenChat() { 
    new Vue({ el: "#OpenChatApp", render: (h) => h(ChatApp) });
}

export { initOpenChat };
