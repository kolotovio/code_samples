import { _axios } from "../../axios"
import { router } from "../../router"

export const users = {
    namespaced: true,
    state: () => ({
        userAccessToken: '' || localStorage.getItem('userAccessToken'),
        userRefreshToken: '' || localStorage.getItem('userRefreshToken'),
        user: localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : {}
    }),
    getters: {
        isLogged: (state) => !!state.userAccessToken,
        getUser: (state) => state.user,
        getAccessToken: (state) => state.userAccessToken,
        getRefreshToken: (state) => state.userRefreshToken,
    },
    actions: {
        signin: async ({ commit }, user) => {
            try {
                const { data } = await _axios.post('auth/signin', user)
                commit('setUser', data)
                router.push({ name: 'MyQuizzes' })
            } catch (error) {
                console.log(error)
            }
        },
        signup: async ({ commit }, newUser) => {
            try {
                const { data } = await _axios.post('auth/signup', newUser)
                commit('setUser', data)
                router.push({ name: 'MyQuizzes' })
            } catch (error) {
                console.log(error)
            }
        },
        logout: async ({ commit }) => {
            try {
                const { data } = await _axios.post('auth/logout')
                console.log(data)
                commit('setLogout')
                router.push({ name: 'Signin' })
            } catch (error) {
                console.log(error)
            }
        },
        refresh: async ({ commit, getters }) => {
            try {
                const { data } = await _axios.post('auth/refresh', null, { headers: { Authorization: `Bearer ${getters.getRefreshToken}` } })
                commit('setUser', data)
            } catch (error) {
                console.log(error)
            }
        }
    },
    mutations: {
        setUser: (state, data) => {
            state.user = data?.user
            state.userAccessToken = data?.accessToken
            state.userRefreshToken = data?.refreshToken
            localStorage.setItem('user', JSON.stringify(data?.user))
            localStorage.setItem('userAccessToken', data?.accessToken)
            localStorage.setItem('userRefreshToken', data?.refreshToken)
        },
        setLogout: (state) => {
            state.user = {}
            state.userAccessToken = ''
            state.userRefreshToken = ''
            localStorage.removeItem('user')
            localStorage.removeItem('userAccessToken')
            localStorage.removeItem('userRefreshToken')
        }
    },
}