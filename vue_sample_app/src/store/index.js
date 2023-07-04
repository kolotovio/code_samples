import { createStore } from 'vuex'
import { _axios } from '../axios'
import { users } from './modules/users'
import { quizzes } from './modules/quizzes'
import { leads } from './modules/leads'

export const store = createStore({
    state: () => ({}),
    getters: {},
    actions: {},
    mutations: {},
    modules: { users, quizzes, leads }
})