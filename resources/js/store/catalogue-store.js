import { HTTP } from "../common_services/api-services";
var baseUrl = "/api/v1/";
const catalogueStore = {
    namespaced: true,
    state: {
        pagination: {
            query: "",
            page: 1,
            limit: 10,
            orderBy: "",
            ascending: true,
            filter: ""
        },
        tableData: [],
        list: [],
        model: {
            name: ""
        },
        editId: 0
    },
    mutations: {
        setPagination(state, payload) {
            state.pagination = payload;
        },
        setTableData(state, payload) {
            state.tableData = payload;
        },
        setList(state, payload) {
            state.list = payload;
        },
        setEditId(state, payload) {
            state.editId = payload;
        },
        setModel(state, param) {
            state.model = param.model;
        },
        clearStore(state) {
            (state.model = {
                name: "",
                description: ""
            }),
                (state.editId = 0);
        }
    },
    actions: {
        /**
         * Used for add brand
         * @param commit
         * @param param
         */
        add({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "catalogues", param.model)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for edit brand
         * @param commit
         * @param param
         */
        edit({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.put(baseUrl + "catalogues/" + param.editId, param.model)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used to get all brand
         * @param commit
         * @param param
         */
        getAll({ commit }, param) {
            return new Promise((resolve, reject) => {
                let requestParams =
                    "?page=" +
                    param.page +
                    "&filter=" +
                    (param.filter ? param.filter : "") +
                    "&per_page=" +
                    param.limit +
                    "&search=" +
                    (param.query ? param.query : "") +
                    "&sort=" +
                    (param.orderBy ? param.orderBy : "") +
                    "&order_by=" +
                    (param.ascending == 0 ? "asc" : "desc");
                if (
                    Object.prototype.hasOwnProperty.call(param, "is_light") &&
                    param.is_light &&
                    param.is_light == "true"
                ) {
                    requestParams = `${requestParams}&is_light='true'`;
                }
                HTTP.get(baseUrl + "catalogues" + requestParams)
                    .then(response => {
                        resolve(response);
                        commit("setList", response.data.data);
                    })
                    .catch(e => {
                        console.error(e.response);
                        reject(e);
                    });
            });
        },

        /**
         * Used for delete brand
         * @param commit
         * @param param
         */
        delete({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.delete(baseUrl + "catalogues" + "/" + param).then(
                    response => {
                        resolve(response);
                    },
                    error => {
                        reject(error);
                    }
                );
            });
        },

        /**
         * Used for multiple delete
         * @param commit
         * @param param
         */
        multiDelete({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "catalogues-delete-multiple", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used to get a particular brand record
         * @param commit
         * @param state - used for edit Id
         */
        getById({ commit, state }) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "catalogues" + "/" + state.editId)
                    .then(response => {
                        commit("setModel", { model: response.data.data });
                        resolve(response.data);
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
                        "catalogues-export" +
                        "?page=" +
                        param.page +
                        "&per_page=" +
                        param.limit +
                        "&filter=" +
                        param.filter +
                        "&search=" +
                        param.query +
                        "&sort=" +
                        param.orderBy +
                        "&order_by=" +
                        (param.orderBy.ascending == 1 ? "asc" : "desc")
                )
                    .then(response => {
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

export default catalogueStore;
