import { _axios } from "../../axios"

export const leads = {
    namespaced: true,
    state: () => ({
        leads: []
    }),
    getters: {
        getLeads: (state) => state.leads
    },
    actions: {
        fetchLeads: async ({ commit }) => {
            try {
                const { data } = await _axios.get('lead')
                commit('setLeads', data)
            } catch (error) {
                console.log(error)
            }
        }
    },
    mutations: {
        setLeads: (state, data) => state.leads = data
    },
}