import Vue from "vue";
import Vuex from "vuex";
import communication from "./classes/communication";

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    user: {
      groupId: null,
      peeredGroupId: null,
      astate: false,
      userId: null,
    },
    pluginSettings: {
      hostname: null,
      model: null,
      prompttemplate: null,
    },
    courseModuleID: null,
    pageInstanceId: null,
    ws_host: "http://localhost:5000",
    ragWebserviceHost: "",
    
  },
  mutations: {
    setCourseModuleID: function(state, id) {
      state.courseModuleID = id;
    },
    setPluginSettings: function (state, settings) {
      //Object.assign(state.pluginSettings, settings); 
      state.pluginSettings.hostname = settings.hostname;
      state.pluginSettings.model = settings.model;
      state.pluginSettings.prompttemplate = settings.prompttemplate;
      state.pluginSettings.courseModuleID = settings.courseModuleID;
      
      //console.log('storr', state.getters.pluginSettings.model)
    },
    setHostname(state, name) {
      state.hostname = name;
    },
    setRAGWebserviceHost(state, name) {
      state.ragWebserviceHost = name;
    },
    setModel(state, name) {
      state.model = name;
    },
    setPrompttemplate(state, name) {
      state.prompttemplate = name;
    },
    setPageInstanceId(state, name) {
      state.pageInstanceId = name;
    },
  },
  getters: {
    getCMID: function(state){
      return state.courseModuleID;
    },
    getPluginSettings: function (state) {
      return state.pluginSettings;
    },
    getHostname: function (state) {
      return state.hostname;
    },
    getRAGWebserviceHost: function (state) {
      return state.ragWebserviceHost
    },
    getModel: function (state) {
      return state.model;
    },
    getPrompttemplate: function (state) {
      return state.prompttemplate;
    },
    getCourseModuleId: function (state) {
      return state.courseModuleId;
    },
    getPageInstanceId: function (state) {
      return state.pageInstanceId;
    },
  },
  actions: {
    loadPluginSettings: async function (context) {
      try {
        const cmid = context.getters.getCMID;
        const req = await communication.webservice("settings", {
          cmid: cmid,
        });
        if (req.success) {
          console.log("loadPluginSettings: ", JSON.parse(req.data));
          context.commit('setPluginSettings', JSON.parse(req.data));
        } else {
          console.log('loadPluginSettings', req);
        }
      } catch (error) {
        console.log('loadPluginSettings', error);
      }
    },
    loadModels: async function() {
      let path = "/llm/query_documents";
      const response = await fetch(this.ws_host + path, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          //Authorization: "Bearer " + apiKey,
        },
      });
    }
  },
  modules: {},
});
