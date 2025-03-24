import Vue from "vue";
import { store } from "./store.js";
import router from './router';
import ChatApp from "./ChatApp.vue";

import { library } from '@fortawesome/fontawesome-svg-core';
import { faCopy, faCog, faThumbsDown, faThumbsUp, faCheck } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';


function initOpenChat(
  course_module_id,
  contextid,
  isAdmin,
  page_instance_id,
) {
  
  store.commit("setPageInstanceId", page_instance_id);

  // new
  store.commit("setAdmin", isAdmin);
  store.commit("setCourseModuleID", course_module_id);
  store.commit("setPageInstanceId", page_instance_id)
  store.commit("setContextID", contextid);
  store.dispatch("loadPluginSettings");
  store.dispatch("loadPreference");
  

  library.add(faCopy, faCog, faThumbsDown, faThumbsUp, faCheck);

  Vue.component('font-awesome-icon', FontAwesomeIcon);

  new Vue({
    el: "#OpenChatApp",
    store,
    router,
    render: (h) => h(ChatApp),
  });
}

export { initOpenChat };
