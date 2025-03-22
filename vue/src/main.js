import Vue from "vue";
import { store } from "./store.js";
import router from './router';
import ChatApp from "./ChatApp.vue";

function initOpenChat(
  course_module_id,
  contextid,
  isAdmin,
  page_instance_id
) {
  
  store.commit("setPageInstanceId", page_instance_id);

  // new
  store.commit("setAdmin", isAdmin);
  store.commit("setCourseModuleID", course_module_id);
  store.commit("setPageInstanceId", page_instance_id)
  store.commit("setContextID", contextid);    
  store.dispatch("loadModels");
  store.dispatch("loadPluginSettings");

  new Vue({
    el: "#OpenChatApp",
    store,
    router,
    render: (h) => h(ChatApp),
  });
}

export { initOpenChat };
