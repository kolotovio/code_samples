import axios from "axios";
import { store } from "../store";

let config = {
    baseURL: import.meta.env.VITE_QUIZLER_API_URL
}

const _axios = axios.create(config);

_axios.interceptors.request.use(
    function (config) {
        // Do something before request is sent
        if (store.getters['users/isLogged']) {
            config.headers.common['Authorization'] = `Bearer ${store.getters['users/getAccessToken']}`
        }
        return config;
    },
    function (error) {
        // Do something with request error
        return Promise.reject(error);
    }
);

// Add a response interceptor
_axios.interceptors.response.use(
    function (response) {
        // Do something with response data
        return response;
    },
    async function (error) {
        // Do something with response error
        let originalRequest = error.config

        if (error.response.status === 401 && error.response.data.status === 'expired' && !originalRequest._retry) {
            originalRequest._retry = true
            await store.dispatch('users/refresh')
            originalRequest.headers.Authorization = `Bearer ${store.getters['users/getAccessToken']}`
            return _axios(originalRequest)
        }
        if (error.response.status === 401 && error.response.data.status === 'invalid' && !originalRequest._retry) {
            originalRequest._retry = true
            store.commit('users/setLogout')
        }
        if (error.response.status === 401 && error.response.data.status === 'blacklisted' && !originalRequest._retry) {
            originalRequest._retry = true
            store.commit('users/setLogout')
        }
        if (error.response.status === 401 && error.response.data.status === 'misused' && !originalRequest._retry) {
            originalRequest._retry = true
            store.commit('users/setLogout')
        }

        return Promise.reject(error);
    }
);

export { _axios }