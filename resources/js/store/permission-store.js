import {HTTP} from "../common_services/api-services";

var baseUrl = '/api/v1/';

function initialState() {
    return {
        permissions: [],
        userPermissions: [],
        permissionDialog: false,
    }
}
const permissionStore = {
    namespaced: true,
    state: initialState,
    mutations: {
        setPermissions(state, param) {
            state.permissions = param;
        },
        clearPermissions(state, param) {
            state.permissions = [];
        },
        setUserPermissions(state, param) {
            state.userPermissions = param;
        },
        setPermissionDialog(state, payload) {
            state.permissionDialog = payload;
        }
    },
    actions: {
        /**
         * Used for set/unset permission
         * @param commit
         * @param param
         */
        edit({commit}, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "set_unset_permission_to_role", param).then(response => {
                    resolve(response);
                }).catch(e => {
                    reject(e);
                })
            })
        },

        /**
         * Used to get role by permission
         * @param commit
         * @param state - used for edit Id
         * @param param
         */
        getById({commit, state}, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + 'get_role_by_permissions/' + param).then(response => {
                    commit('setPermissions', response.data.data);
                    resolve(response.data);
                }).catch(e => {
                    reject(e);
                })
            })
        },
    },
    getters: {}
}

export default permissionStore;
