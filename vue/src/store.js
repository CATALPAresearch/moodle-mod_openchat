import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    hostname: '',
    model: '',
    prompttemplate: '',
  },
  mutations: {
    setHostname(state, name){
      state.hostname = name;
    },
    setModel(state, name){
      state.model = name;
    },
    setPrompttemplate(state, name){
      state.prompttemplate = name;
    },
  },
  getters:{
    getHostname: function(state){
      return state.hostname;
    },
    getModel: function(state){
      return state.model;
    },
    getPrompttemplate: function(state){
      return state.prompttemplate;
    },
  },
  actions: {},
  modules: {},
});


