import { HTTP } from "../common_services/api-services";
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
        list: [],
        adminList: [],
        model: {
            first_name: "",
            last_name: "",
            email: "",
            password: "",
            contact_number: "",
            role_id: "",
            role: ""
        },
        editId: 0
    };
}

const adminStore = {
    namespaced: true,
    state: initialState(),
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
            state.model.first_name = param.model.first_name;
            state.model.last_name = param.model.last_name;
            state.model.email = param.model.email;
            state.model.password = "";
            state.model.contact_number = param.model.contact_number;
            state.model.role_id = parseInt(param.model.role_id);
        },
        setAdminList(state, payload) {
            state.adminList = payload;
        },
        clearStore(state) {
            const s = initialState();
        },
        clearModel(state) {
            const s = initialState();
            state.model = s.model;
            state.editId = s.editId;
        }
    },
    actions: {
        /**
         * Used for add admin
         * @param commit
         * @param param
         */
        add({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "admin-register", param.model)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for edit admin
         * @param commit
         * @param param
         */
        edit({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "admin-users/" + param.editId, param.model)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used to get all admin
         * @param commit
         * @param param
         */
        getAll({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(
                    baseUrl +
                        "admin-users" +
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
         * Used for delete admin
         * @param commit
         * @param param
         */
        delete({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.delete(baseUrl + "admin-users" + "/" + param).then(
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
                HTTP.post(baseUrl + "admin-users-delete-multiple", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used to get a particular admin record
         * @param commit
         * @param state - used for edit Id
         */
        getById({ commit, state }) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "admin-users" + "/" + state.editId)
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
                        "admin-users-export" +
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
        },

        checkEmail({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "admin-check_email", param)
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

export default adminStore;
