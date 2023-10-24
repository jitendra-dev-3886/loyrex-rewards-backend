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
        currentUserData: {},
        model: {
            user_id: "",
            first_name: "",
            last_name: "",
            job_title: "",
            email: "",
            password: "",
            contact_number: "",
            category_id: [],
            product_id: [],
            reward_points: "",
            userGroup: [],
            userGroup_array: [],
            userGroup_id: [],
            upload_images: [],
            featured_image_key: "",
            featured_image_extension: ""
        },
        editId: 0,
        userList: [],
        filterUserList: [],
        defaultRouteUrl: "/dashboard"
    };
}

const userStore = {
    namespaced: true,
    state: initialState(),
    mutations: {
        setPagination(state, payload) {
            state.pagination = payload;
        },
        setTableData(state, payload) {
            state.tableData = payload;
        },
        setDefaultUrl(state, payload) {
            state.defaultRouteUrl = payload;
        },
        removeAuthorization(state, payload) {
            state.currentUserData.authorization = payload;
        },
        clearUserData(state) {
            state.currentUserData = {};
        },
        setCurrentUserData(state, payload) {
            state.currentUserData = payload;
        },

        setEditId(state, payload) {
            state.editId = payload;
        },
        setModel(state, param) {
            state.model = param.model;
            state.model.action_type = "";
            state.model.temp_reward_points = param.model.reward_points;
        },
        clearStore(state) {
            const s = initialState();
        },
        clearModel(state) {
            const s = initialState();
            state.model = s.model;
            state.editId = s.editId;
        },
        setUserList(state, payload) {
            state.userList = payload;
        },
        setFilterUserList(state, payload) {
            state.filterUserList = payload;
        },
        setUserListSearch(state, payload) {
            state.pagination.query = payload;
        },
        clearFilterUserList(state) {
            state.filterUserList = [];
        },
        clearUserList(state) {
            state.userList = [];
        },
        clearDefaultUrl(state) {
            state.defaultRouteUrl = "/dashboard";
        }
    },
    actions: {
        login({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(loginUrl, param.loginDetail)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        userLogout() {
            return new Promise((resolve, reject) => {
                HTTP.get(`${baseUrl}logout`)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        logoff({ commit }, param) {
            commit("removeAuthorization", param);
        },

        /**
         * Used to get all user
         * @param commit
         * @param param
         */
        getAll({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(
                    baseUrl +
                        "users-index" +
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
                HTTP.post(baseUrl + "users-add", param.model)
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
                HTTP.get(baseUrl + "users-show" + "/" + state.editId)
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
         * Used for edit user
         * @param commit
         * @param param
         */
        edit({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "users-update/" + param.editId, param.model)
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
                HTTP.delete(baseUrl + "users-delete/" + param, {
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
                HTTP.post(baseUrl + "users-delete-multiple", param)
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
                        "users-export" +
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
                HTTP.post(baseUrl + "users-import-bulk", param)
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
                HTTP.post(baseUrl + "user-check-email", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },
        checkContact({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "user-check-contact", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },
        /**
         * Used to get all user
         * @param commit
         * @param param
         */
        getUserList({ commit }, param) {
            let requestType = param.requestType;
            delete param.requestType;
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "users-index", {
                    params: param
                })
                    .then(response => {
                        if (requestType == "filter") {
                            commit("setFilterUserList", response.data.data);
                        }
                        if (requestType == "modal") {
                            commit("setUserList", response.data.data);
                        }
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        }
    },
    getters: {
        /**
         * Used to get user fullname
         */
        userFullName: state => {
            let user_name =
                state.currentUserData.first_name +
                " " +
                state.currentUserData.last_name;
            return user_name;
        },

        /**
         * Used to get user profile image
         */
        userProfilePicture: state => {
            if (state.currentUserData.profile == "") {
                return "/images/profile.png";
            } else {
                return state.currentUserData.profile;
            }
        }
    }
};

export default userStore;
