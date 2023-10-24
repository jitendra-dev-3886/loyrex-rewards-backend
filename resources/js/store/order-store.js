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
        orderList: [],
        model: {
            /*first_name: '',
            last_name:'',
            email:'',
            password:'',
            contact_number:'',
            role_id: '',
            role: ''*/
        },
        editId: 0,
        orderStatusList: [
            { order_status: "0", name: "Pending" },
            { order_status: "1", name: "In process" },
            { order_status: "2", name: "Shipped" },
            { order_status: "3", name: "Delivered" },
            { order_status: "4", name: "Cancel" }
        ]
    };
}

const orderStore = {
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
        },
        setOrderList(state, payload) {
            state.orderList = payload;
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
         * Used for add order
         * @param commit
         * @param param
         */
        /*add({commit}, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "orders", param.model).then(response => {
                    resolve(response);
                }).catch(e => {
                    reject(e);
                })
            })
        },*/

        /**
         * Used for edit order
         * @param commit
         * @param param
         */
        /*edit({commit}, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "orders/" + param.editId, param.model).then(response => {
                    resolve(response);
                }).catch(e => {
                    reject(e);
                })
            })
        },*/

        /**
         * Order status change
         * @param commit
         * @param param
         */
        changeOrderStatus({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(
                    baseUrl + "orders-status/" + param.editId,
                    param.model
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
         * Delivery detail change
         * @param commit
         * @param param
         */
        changeDeliveryDetails({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(
                    baseUrl + "delivery-partners/" + param.editId,
                    param.model
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
         * Used to get all order
         * @param commit
         * @param param
         */
        getAll({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(
                    baseUrl +
                        "orders" +
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
         * Used for delete order
         * @param commit
         * @param param
         */
        /* delete({commit}, param) {
            return new Promise((resolve, reject) => {
                HTTP.delete(baseUrl + "orders" + "/" + param,
                ).then(response => {
                    resolve(response);
                }, error => {
                    reject(error);
                })
            })
        },*/

        /**
         * Used to get a particular order record
         * @param commit
         * @param state - used for edit Id
         */
        getById({ commit, state }) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "orders" + "/" + state.editId)
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
                        "orders-export" +
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
         * Used for import functionality (upload file)
         * @param commit
         * @param param
         */
        import({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "orders-import-bulk", param)
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

export default orderStore;
