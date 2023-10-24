import { HTTP } from "../common_services/api-services.js"; // set url here e.g.'/api/v1/mypreferences/business/user/'
/*var baseUrl = '';*/ var loginUrl = "/api/v1/login";
var baseUrl = "/api/v1/";

function initialState() {
    return {
        pagination: {
            query: "",
            page: 1,
            limit: 10,
            orderBy: "",
            ascending: true,
            filter: ""
        },
        tableData: [],
        roleList: [],
        userGroupmodel: {
            name: "",
            no_of_users: "",
            id: "",
            catalogue_id: [],
            catalogue_array: []
        },
        editId: 0,

        userList: [],
        userGroupList: [],
        filterUserList: [],
        filterUserGroupList: []
    };
}

const userGroupStore = {
    namespaced: true,
    state: initialState(),
    mutations: {
        setPagination(state, payload) {
            state.pagination = payload;
        },
        setTableData(state, payload) {
            state.tableData = payload;
        },
        setGroupEditId(state, payload) {
            state.editId = payload;
        },
        setModel(state, param) {
            state.userGroupmodel = param.userGroupmodel;
            state.catalogue_id = param.userGroupmodel.catalogue;
        },
        clearStore(state) {
            const s = initialState();
        },
        clearGroupModel(state) {
            const s = initialState();
            state.userGroupmodel = s.userGroupmodel;
            state.editId = s.editId;
        },
        setUserGroupList(state, payload) {
            state.userGroupList = payload;
        },
        setFilterUserGroupList(state, payload) {
            state.filterUserGroupList = payload;
        },
        setUserGroupListSearch(state, payload) {
            state.pagination.query = payload;
        },
        clearFilterUserGroupList(state) {
            state.filterUserGroupList = [];
        },
        clearUserGroupList(state) {
            state.userGroupList = [];
        }
    },
    actions: {
        /**
         * Used to get all user
         * @param commit
         * @param param
         */

        getAllGroups({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(
                    baseUrl +
                        "user-groups" +
                        "?page=" +
                        param.page +
                        "&per_page=" +
                        param.limit +
                        "&search=" +
                        (param.query ? param.query : "") +
                        "&filter=" +
                        (param.filter ? param.filter : "") +
                        "&sort=" +
                        (param.orderBy ? param.orderBy : "") +
                        "&order_by=" +
                        (param.ascending == 0 ? "asc" : "desc")
                )
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for registration
         * @param commit
         * @param param
         */
        add({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "user-groups", param.userGroupmodel)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used to get a particular user record
         * @param commit
         * @param state - used for edit Id
         */
        getGroupById({ commit, state }) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "user-groups" + "/" + state.editId)
                    .then(response => {
                        commit("setModel", {
                            userGroupmodel: response.data.data
                        });
                        resolve(response.data);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for edit user
         * @param commit
         * @param param
         */
        edit({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.put(
                    baseUrl + "user-groups/" + param.editId,
                    param.userGroupmodel
                )
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for delete user
         * @param commit
         * @param param
         */
        delete({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.delete(baseUrl + "user-groups/" + param, {
                    _method: "DELETE"
                })
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for multiple delete
         * @param commit
         * @param param
         */
        multiDelete({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "user-group-delete-multiple", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for export functionality
         * @param commit
         * @param param
         */
        export({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(
                    baseUrl +
                        "user-groups-export" +
                        "?page=" +
                        param.page +
                        "&per_page=" +
                        param.limit +
                        "&search=" +
                        param.query +
                        "&filter=" +
                        param.filter +
                        "&sort=" +
                        param.orderBy +
                        "&order_by=" +
                        (param.ascending == 1 ? "asc" : "desc")
                )
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for import functionality (upload file)
         * @param commit
         * @param param
         */
        getUserGroupList({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "user-groups")
                    .then(response => {
                        commit("setUserGroupList", response.data.data);

                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        }
    },
    getters: {}
};

export default userGroupStore;
