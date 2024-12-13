import Vue from "vue";
import { store } from './store.js';
import ChatApp from "./ChatApp.vue";

function initOpenChat(hostname, model, prompttemplate) {
    
    store.commit('setHostname', hostname);
    store.commit('setModel', model);
    store.commit('setPrompttemplate', prompttemplate);
    

  new Vue({
    el: "#OpenChatApp",
    store,
    render: (h) => h(ChatApp),
  });
}

export { initOpenChat };
