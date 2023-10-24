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
        model: {
            name: "",
            description: "",
            voucher_type: "",
            category_id: "",
            user_id: "",
            contact_number: "",
            no_of_vouchers: "",
            points: "",
            valid_till: "",
            validity_status: "0",
            status: "1",
            product_id: []
        },
        sendVoucherModel: {
            id: "",
            user: {
                email: ""
            }
        },
        editId: 0,
        voucherTypes: [
            { id: "0", name: "Products" },
            { id: "1", name: "Points" }
        ],
        statusTypes: [
            { id: "1", name: "Active" },
            { id: "0", name: "Deactive" }
        ]
    };
}

const voucherStore = {
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
        setModel(state, param) {
            state.model = param.model;
            if (state.model.user_id == "0") {
                state.model.user = {
                    id: "0",
                    first_name: "None",
                    last_name: "",
                    email: "",
                    contact_number: ""
                };
            }
        },
        setVoucherModel(state, param) {
            state.sendVoucherModel = param;
        },
        clearVoucherModel(state, param) {
            const s = initialState();
            state.sendVoucherModel = s.sendVoucherModel;
        },
        clearStore(state) {
            const s = initialState();
            Object.keys(s).forEach(key => {
                state[key] = s[key];
            });
        },
        clearModel(state) {
            const s = initialState();
            state.model = s.model;
            state.editId = s.editId;
        }
    },
    actions: {
        /**
         * Used to get all user
         * @param commit
         * @param param
         */
        getAll({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(
                    baseUrl +
                        "vouchers" +
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
                HTTP.post(baseUrl + "vouchers", param.model)
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
        getById({ commit, state }) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "vouchers" + "/" + state.editId)
                    .then(response => {
                        response.data.data.validity_status =
                            response.data.data.valid_till == "" ? "1" : "0";
                        commit("setModel", { model: response.data.data });
                        if (
                            Object.prototype.hasOwnProperty.call(
                                response.data.data,
                                "user"
                            )
                        ) {
                            let ar = [];
                            if (response.data.data.user != null) {
                                ar.push(response.data.data.user);
                            }
                            commit("userStore/setUserList", ar, { root: true });
                        }
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
                HTTP.post(baseUrl + "vouchers/" + param.editId, param.model)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        // Send Voucher Mail
        sendVoucherEmail({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "send-voucher-manually", param)
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
                HTTP.delete(baseUrl + "vouchers/" + param, {
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
         * Update voucher status
         * @param commit
         * @param param
         */
        updateStatus({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "update-voucher-status", param)
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
                        "vouchers-export" +
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
                HTTP.post(baseUrl + "vouchers-bulk", param)
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

export default voucherStore;
