import Vue from "vue";
import { store } from "./store.js";
import ChatApp from "./ChatApp.vue";

function initOpenChat(
  hostname,
  model,
  prompttemplate,
  course_module_id,
  page_instance_id
) {
  store.commit("setHostname", hostname);
  store.commit("setModel", model);
  store.commit("setPrompttemplate", prompttemplate);
  store.commit("setCourseModuleId", course_module_id);
  store.commit("setPageInstanceId", page_instance_id);

  new Vue({
    el: "#OpenChatApp",
    store,
    render: (h) => h(ChatApp),
  });
}

export { initOpenChat };
