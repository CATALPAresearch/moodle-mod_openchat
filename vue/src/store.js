import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    hostname: "",
    ragWebserviceHost: "",
    model: "",
    prompttemplate: "",
    courseModuleId: "",
    pageInstanceId: "",
  },
  mutations: {
    setHostname(state, name) {
      state.hostname = name;
    },
    setRAGWebserviceHost(state, name){
      state.ragWebserviceHost = name;
    },
    setModel(state, name) {
      state.model = name;
    },
    setPrompttemplate(state, name) {
      state.prompttemplate = name;
    },
    setCourseModuleId(state, name) {
      state.courseModuleId = name;
    },
    setPageInstanceId(state, name) {
      state.pageInstanceId = name;
    },
  },
  getters: {
    getHostname: function (state) {
      return state.hostname;
    },
    getRAGWebserviceHost: function(state){
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
  actions: {},
  modules: {},
});
