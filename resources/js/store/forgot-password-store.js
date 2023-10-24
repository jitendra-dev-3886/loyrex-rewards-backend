import {HTTP} from "../common_services/api-services";

var baseUrl ='/api/v1/';

function initialState() {
    return {
        model: {
            password: '',
            password_confirmation: '',
        }
    }
}
const forgotPasswordStore = {
    namespaced: true,
    state: initialState,
    mutations: {
        clearStore(state) {
            let s = initialState();
            state.model = s.model;
        },
    },
    actions: {
        /**
         * Used for send forgot password mail
         * @param commit
         * @param param
         */
        sendForgotPasswordEmail({commit}, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "forgot-passsword", param).then(response => {
                    resolve(response);
                }).catch(e => {
                    reject(e);
                })
            })
        },

        /**
         * Used for reset password
         * @param commit
         * @param param
         */
        resetPassword({commit}, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "reset-password", param).then(response => {
                    resolve(response);
                }).catch(e => {
                    reject(e);
                })
            })
        },
    },
    getters: {}
}

export default forgotPasswordStore;
