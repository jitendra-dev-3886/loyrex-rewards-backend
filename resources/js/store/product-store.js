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
        productList: [],
        model: {
            name: "",
            category_id: "",
            sub_category_id: "",
            brand_id: "",
            featured_image: "",
            featured_image_key: "",
            featured_image_extension: "",
            description: "",
            point: "",
            available_status: "",
            upload_images: [],
            catalogue_id: []
        },
        editId: 0,
        uploadImageList: [],
        detailViewModel: {
            upload_images: []
        },
        downloadLink: ""
    };
}

const productStore = {
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
            state.model.name = param.model.name;
            state.model.category_id = param.model.category_id;
            state.model.sub_category_id = param.model.sub_category_id;
            state.model.brand_id = param.model.brand_id;
            state.model.featured_image = param.model.featured_image;
            state.model.description = param.model.description;
            state.model.point = param.model.point;
            state.model.available_status = param.model.available_status;
            state.uploadImageList = param.model.upload_images;
            state.model.upload_images = null;
            state.catalogue_id = param.model.catalogue;
        },
        setProductList(state, payload) {
            state.productList = payload;
        },
        setUploadImageList(state, payload) {
            state.uploadImageList = payload;
        },
        setDetailViewId(state, payload) {
            state.detailViewId = payload;
        },
        setDetailViewModel(state, param) {
            state.detailViewModel = param;
        },
        setDownloadLink(state, payload) {
            state.downloadLink = payload;
        },
        clearStore(state) {
            const s = initialState();
        },
        clearDetailViewModel(state) {
            state.detailViewModel = {
                upload_images: []
            };
        },
        clearModel(state) {
            const s = initialState();
            state.model = s.model;
            state.editId = s.editId;
        }
    },
    actions: {
        /**
         * Used for add category
         * @param commit
         * @param param
         */
        add({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "products", param.model)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for edit category
         * @param commit
         * @param param
         */
        edit({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "products/" + param.editId, {
                    name: param.model.name,
                    category_id: param.model.category_id,
                    sub_category_id: param.model.sub_category_id,
                    brand_id: param.model.brand_id,
                    description: param.model.description,
                    point: param.model.point,
                    available_status: param.model.available_status,
                    catalogue_id: param.model.catalogue_id
                })
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used to get all category
         * @param commit
         * @param param
         */
        getAll({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(
                    baseUrl +
                        "products" +
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
         * Used for delete category
         * @param commit
         * @param param
         */
        delete({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.delete(baseUrl + "products" + "/" + param).then(
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
                HTTP.post(baseUrl + "product-delete-multiple", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used to get a particular category record
         * @param commit
         * @param state - used for edit Id
         */
        getById({ commit, state }) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "products" + "/" + state.editId)
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
                        "products-export" +
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
         * download zip sample file
         */
        downloadZipFile({ commit, state }) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "download-product-zip")
                    .then(response => {
                        commit(
                            "setDownloadLink",
                            response.data.data.downloadLink
                        );
                        resolve(response.data);
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
                HTTP.post(baseUrl + "products-bulk", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for import image zip functionality (upload file)
         * @param commit
         * @param param
         */
        importZip({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "upload-zip", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        addMultipleImages({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "upload-images", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },

        /**
         * Used for delete gallery image
         * @param commit
         * @param param
         */
        deleteImage({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "delete-images", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },
        /**
         * Get products by category id
         * @param commit
         * @param param
         */
        getProductsByCategory({ commit }, param) {
            return new Promise((resolve, reject) => {
                HTTP.post(baseUrl + "products-by-category", param)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        },
        getProductDetailById({ commit, state }) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl + "products" + "/" + state.detailViewId)
                    .then(response => {
                        commit("setDetailViewModel", response.data.data);
                        resolve(response.data);
                    })
                    .catch(e => {
                        reject(e);
                    });
            });
        }
    },
    getters: {}
};

export default productStore;
