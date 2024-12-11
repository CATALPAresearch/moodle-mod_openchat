//import Vue from 'vue';
import { createStore } from 'vuex';
import moodleAjax from 'core/ajax';
import moodleStorage from 'core/localstorage';
import Notification from 'core/notification';
import $ from 'jquery';

//Vue.use(Vuex);

export const store = new createStore({
    state: {
        pluginName: '',
        courseModuleID: 0,
        contextID: 0,
        strings: {},
        isModerator: false,        
        alert: {
            show: false,
            type: 'primary',
            message: 'unknown'
        },
        confValue: ''
    },
    //strict: process.env.NODE_ENV !== 'production',
    mutations: {
        setConfigValue(state, value){
            state.confValue = value;
        },
        setPluginName(state, name){
            state.pluginName = name;
        },
        setModerator(state, isModerator){
            state.isModerator = isModerator;
        },
        setCourseModuleID(state, id) {
            state.courseModuleID = id;
        },
        setContextID(state, id) {
            state.contextID = id;
        },       
        setStrings(state, strings) {
            state.strings = strings;
        },
        showAlert(state, [type, message]){
            const timeout = 3000;
            state.alert.type = type;
            state.alert.message = message;
            state.alert.show = true;
            new Promise(
                resolve => setTimeout(resolve, timeout)
            ).then(
                (resolve) => {
                    state.alert.show = false;
                    state.alert.type = "primary";
                    state.message = "unknown";
                }
            );    
        }
    },
    getters: {
        getConfigValue: function(state){
            return state.confValue;
        },
        getModeratorStatus: function(state){
            return state.isModerator;
        },
        getAlertType: function(state){
            return `alert-${state.alert.type}`;
        },
        getAlertState: function(state){
            return state.alert.show;
        },
        getAlertMessage: function(state){
            return state.alert.message;
        },
        getContextID: function(state){
            return state.contextID;
        },
        getCourseModuleID: function(state){
            return state.courseModuleID;
        },
        getPluginName: function(state){
            return state.pluginName;
        },
        getCMID: function(state){
            return state.courseModuleID;
        }
    },
    actions: {
        async loadComponentStrings(context) {
            const lang = $('html').attr('lang').replace(/-/g, '_');
            const cacheKey = 'local_ari/strings/' + lang;
            const cachedStrings = moodleStorage.get(cacheKey);
            if (cachedStrings) {
                context.commit('setStrings', JSON.parse(cachedStrings));
            } else {
                const request = {
                    methodname: 'core_get_component_strings',
                    args: {
                        'component': 'local_ari',
                        lang,
                    },
                };
                const loadedStrings = await moodleAjax.call([request])[0];
                let strings = {};
                loadedStrings.forEach((s) => {
                    strings[s.stringid] = s.string;
                });
                context.commit('setStrings', strings);
                moodleStorage.set(cacheKey, JSON.stringify(strings));
            }
        },
    }
});

/**
 * Single ajax call to Moodle.
 */
export async function ajax(method, args) {
    const request = {
        methodname: method,
        args: Object.assign({
            coursemoduleid: store.state.courseModuleID
        }, args),
    };

    try {
        return await moodleAjax.call([request])[0];
    } catch (e) {
        Notification.exception(e);
        throw e;
    }
}
