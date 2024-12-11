import { createStore } from 'vuex';
import VuexORM, { Database } from '@vuex-orm/core';

const database = new VuexORM.Database();
database.register(Test);
database.register(Task);

export const initStore = (ariContext) => createStore({
    modules: {
        //TestModule
    },
    state: {
        ariContext,
    },
    getters: {  
        //[GET.LONGPAGE_CONTEXT]: ({ safranContext }) => safranContext,
    },
    plugins: [VuexORM.install(database)]
});