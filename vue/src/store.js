import Vue from "vue";
import Vuex from "vuex";
import communication from "./classes/communication";

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    isAdmin: false,
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
      chatmodus: null,
    },
    informedConsentAgreement: false,
    courseModuleID: null,
    pageInstanceId: null,
    ws_host: "http://localhost:5000/",
    ragWebserviceHost: "http://localhost:5000/",

    llmModelList: []
    
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
      state.pluginSettings.chatmodus = settings.chatmodus;
      this.dispatch("loadModelNames");
      //console.log('storr', state.getters.pluginSettings.model)
    },
    setHostname(state, name) {
      state.hostname = name;
    },
    setRAGWebserviceHost(state, name) {
      state.ragWebserviceHost = name;
    },
    setLLMModelList(state, list){
      state.llmModelList = list
    },
    setModel(state, name) {
      state.pluginSettings.model = name;
    },
    setPrompttemplate(state, name) {
      state.prompttemplate = name;
    },
    setPageInstanceId(state, name) {
      state.pageInstanceId = name;
    },
    setInformedConsentAgreement: function(state, value){
      state.informedConsentAgreement = value;
      this.dispatch("updatePreference");
    },
    setAdmin: function(state, value){
      state.isAdmin = value;
    },
    setChatModus: function(state, value){
      state.pluginSettings.chatmodus = value;
    }
  },
  getters: {
    getIsAdmin: function(state){
      return state.isAdmin;
    },
    getChatModus: function(state){
      return state.pluginSettings.chatmodus;
    },
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
    getLLMModelList(state){
      return state.llmModelList;
    },
    getModel: function (state) {
      return state.pluginSettings.model;
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
    getInformedConsentAgreement: function(state){
      return state.informedConsentAgreement;
    }
  },
  actions: {
    loadPluginSettings: async function (context) {
      try {
        const cmid = context.getters.getCMID;
        const req = await communication.webservice("load_settings", {
          cmid: cmid,
        });
        if (req.success) {
          console.log("@store: loadPluginSettings: ", JSON.parse(req.data));
          context.commit('setPluginSettings', JSON.parse(req.data));
        } else {
          console.log('@store: loadPluginSettings without success: ', req);
        }
      } catch (error) {
        console.error(error);
        throw new Error("@store: Failed to load plugin settings. ");
      }
    },
    updatePluginSettings: async function (context) {
      try {
        const response = await communication.webservice("update_settings", {
          cmid: context.getters.getCMID,
          settings: JSON.stringify(context.getters.getPluginSettings)
        });
  
        if (!response.success) {
          console.error(response);
          throw new Error("@store: Failed to update plugin settings. Webservice return ressult. ");
        }else{
          console.log('Stored settings', context.getters.getPluginSettings)
        }
  
      } catch (error) {
        console.error(error);
        throw new Error("@store: Failed to update plugin settings. Webservice not reachable. ");
      }
    },
    loadPreference: async function (context) {
      try {
        const req = await communication.webservice("preference", {
          preference: 'accepted-informed-consent',
          preference_value: 'none'
        });
        if (req.success) {
          console.log('pref', req.preference)
          context.informedConsentAgreement = req.preference;
          context.commit('setInformedConsentAgreement', req.preference);
        } else {
          console.log('loadPluginpreference', req);
        }
      } catch (error) {
        console.error(error);
        throw new Error("@store: Failed to load plugin preference. ");
      }
    },
    updatePreference: async function (context) {
      try {
        const req = await communication.webservice("preference", {
          preference: 'accepted-informed-consent',
          preference_value: context.getters.getInformedConsentAgreement,
        });
        if (req.success) {
          console.log('loadPluginpreference done', req);//context.commit('setInformedConsentAgreement', JSON.parse(req.preference));
        } else {
          console.log('loadPluginpreference failed', req);
        }
      } catch (error) {
        console.error(error);
        throw new Error("@store: Failed to load plugin preference. ");
      }
    },
    loadModelNames: async function (context) {
      const url = context.getters.getPluginSettings.hostname + 'api/tags';
      console.log('setLLMModelList', url)
      const response = await fetch(url); // or your base_url
      const data = await response.json();
      console.log('Availab. models', data.models)
      let models = data.models.map(m => m.name);
      context.commit('setLLMModelList', models);
    },
    loadRAGModelNames: async function(context) {
      let path = "llm/models/list";
      let response = await fetch(this.getters.getRAGWebserviceHost + path, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          //Authorization: "Bearer " + apiKey,
        },
      });

      if (!response.ok) {
        console.error("Failed to fetch models:", response.statusText);
        return;
      }
  
      let data = await response.json();

      if (data.success) {
        context.commit('setLLMModelList', data.data);
      }

    },
  },
  modules: {},
});
