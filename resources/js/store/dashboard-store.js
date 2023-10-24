import {HTTP} from "../common_services/api-services";

var baseUrl = '/api/v1/';
const brandStore = {
    namespaced: true,
    state: {
        dashboardData: {},
    },
    mutations: {
        setDashboardData(state, payload) {
            state.dashboardData = payload;
        },
        clearStore(state) {
            state.data = {};
        },
    },
    actions: {
        /**
         * Used to get a particular dashboard contents
         * @param commit
         * @param state
         */
        getDashboard({commit, state}) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + 'dashboard').then(response => {
                    commit('setDashboardData', response.data.data)
                    resolve(response.data);
                })
                .catch(e => {
                    reject(e);
                })
            })
        },
    },
    getters: {}
}

export default brandStore;
