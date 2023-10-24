import { HTTP } from "../common_services/api-services.js";
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
        roledropdownlist: [],
        editId: 0,
        model: {
            id: [],
            name: "",
            guard_name: "",
            landing_page: ""
        }
    };
}

const roleStore = {
    namespaced: true,
    state: initialState(),
    mutations: {
        setPagination(state, payload) {
            state.pagination = payload;
        },
        setTableData(state, payload) {
            state.tableData = payload;
        },
        setEditId(state, payload) {
            state.editId = payload;
        },
        setRoleList(state, payload) {
            state.roledropdownlist = payload;
        },
        setModel(state, param) {
            Object.keys(state.model).forEach(key => {
                if (param.model[key] == null) {
                    param.model[key] = "";
                }
                state.model[key] = param.model[key];
            });
        },
        clearStore(state) {
            const s = initialState();
            state.model = s.model;
            state.editId = s.editId;
        }
    },
    actions: {
        /**
         * Used to get all role
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
                HTTP.get(baseUrl + "roles" + requestParams)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },
        /**
         * Used for add role
         * @param commit
         * @param param
         */
        add({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "roles", param.model)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for edit role
         * @param commit
         * @param param
         */
        edit({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.put(baseUrl + "roles/" + param.editId, param.model)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for delete role
         * @param commit
         * @param param
         */
        delete({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.delete(baseUrl + "roles/" + param, { _method: "DELETE" })
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
                HTTP.post(baseUrl + "roles-delete-multiple", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used to get a particular role record
         * @param commit
         * @param state - used for edit Id
         */
        getById({ commit, state }) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "roles" + "/" + state.editId)
                    .then(response => {
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
                        "roles-export" +
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
        import({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "roles-import-bulk", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used to display import history
         * @param commit
         * @param param
         */
        getAllImport({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(
                    baseUrl +
                        "import-csv-log" +
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
         * Used for display particular import history
         * @param commit
         * @param state
         */
        getByImportId({ commit, state }) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "import-csv-log" + "/" + state.editId)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        }
    }
};

export default roleStore;
