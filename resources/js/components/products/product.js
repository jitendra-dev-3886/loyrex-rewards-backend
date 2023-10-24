import CustomTable from "../../components/customtable/table";
import DeleteModal from "../../partials/DeleteModal";
import ExportBtn from "../../partials/ExportBtn";
import MultiDelete from "../../partials/MultiDelete";
import CommonServices from "../../common_services/common.js";
import AddProduct from "./AddProduct.vue";
import ProductViewModal from "./ProductViewModal.vue";
import DragAndDropFile from "../../partials/DragAndDropFile";
import { mapState } from "vuex";
import {
    ADD_BODY_CLASSNAME,
    REMOVE_BODY_CLASSNAME
} from "../../store/htmlclass.module";
import EditImagesModal from "./EditImagesModal.vue";

export default CustomTable.extend({
    name: "Catalogue",
    data: function() {
        var self = this;
        return {
            tab: "tab1",
            files: [],
            modalOpen: false,
            addProductModal: false,
            isImportLoaded: false,
            urlApi: "productStore/getAll", // set store name here to set/get pagination data and for access of actions/mutation via custom table
            headers: [
                { text: "Product ID", value: "id", width: "10%" },
                { text: "Product Name", value: "name", width: "15%" },
                { text: "Points", value: "point", width: "10%" },
                {
                    text: "Feature Image",
                    value: "featured_image",
                    sortable: false,
                    width: "10%"
                },
                {
                    text: "Category",
                    value: "category.name",
                    width: "10%",
                    sortable: false
                },
                {
                    text: "Catalogues",
                    value: "catalogues",
                    width: "10%",
                    sortable: false
                },
                {
                    text: "Brand Name",
                    value: "brand.name",
                    width: "10%",
                    sortable: false
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false,
                    width: "10%"
                }
            ],
            paramProps: {
                delete_allowed: true,
                idProps: "",
                storeProps: ""
            },
            confirmation: {
                title: "",
                description: "",
                btnCancelText: self.$getConst("BTN_CANCEL"),
                btnConfirmationText: self.$getConst("BTN_DELETE")
            },
            deleteProps: {
                ids: "",
                store: ""
            },
            exportProps: {
                id: "",
                store: "",
                fileName: "",
                pagination: ""
            },
            importProps: {
                store: "productStore",
                modelName: "product",
                multiple: false
            },
            importZipProps: {
                store: "productStore",
                zipName: "zipUpload",
                multiple: false
            },
            state_id: "",
            filterMenu: false,
            productViewModal: false,
            category_id: "",
            point: "",
            sub_category_id: "",
            brand_id: "",
            catalogue_id: "",
            available_status: "",
            isSubCategoryLoading: false,
            editImagesDialog: false
        };
    },
    mixins: [CommonServices],
    components: {
        DeleteModal,
        ExportBtn,
        MultiDelete,
        AddProduct,
        DragAndDropFile,
        ProductViewModal,
        EditImagesModal
    },
    computed: {
        ...mapState({
            pagination: state => state.productStore.pagination,
            brandList: state => state.brandStore.list,
            catalogueList: state => state.catalogueStore.list,
            parentCategoryList: state => state.categoryStore.parentCategoryList,
            subCategoryList: state => state.categoryStore.subCategoryList
        })
    },
    watch: {},
    methods: {
        /**
         *
         */
        setExport() {
            let rowIds = [];
            this.selected.forEach((element, index) => {
                rowIds[index] = element.id;
            });

            this.exportProps.ids = rowIds;
            this.exportProps.store = "productStore";
            this.exportProps.fileName = "Catalogue";
            this.exportProps.pagination = JSON.parse(
                JSON.stringify(this.pagination)
            );
            this.$refs.exportbtn.exportToCSV();
        },

        /**
         * Subcategory filter from category
         */
        getSubCategory() {
            this.isSubCategoryLoading = true;
            this.$store
                .dispatch("categoryStore/getSubCategory", {
                    id: this.category_id
                })
                .then(
                    response => {
                        this.isSubCategoryLoading = false;
                        if (response.error) {
                            this.errorArr = response.data.error;
                            this.errorDialog = true;
                        } else {
                            // Reset category list
                            this.$store.commit(
                                "categoryStore/setSubCategoryList",
                                []
                            );
                            this.sub_category_id = "";
                            //set sub category list
                            this.$store.commit(
                                "categoryStore/setSubCategoryList",
                                JSON.parse(JSON.stringify(response.data.data))
                            );
                        }
                    },
                    function(error) {
                        this.isSubCategoryLoading = false;
                        this.errorMessage = this.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
        },

        /*
         * Add Product Modal method
         * */
        addProduct() {
            if (this.$refs.productModal) {
                this.$refs.productModal.$validator.reset();
            }
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store.commit("categoryStore/setParentCategoryList", []);
            /**
             * Batch request of product (Max 10 request is allowed at a time)
             */
            var api = "getBatchProduct";
            var requestArray = [
                {
                    url: `api/v1/brands?is_light='true'`,
                    request_id: "brandList"
                },
                {
                    url: "api/v1/parent-categories?is_light='true'",
                    request_id: "categoryList"
                },
                {
                    url: "api/v1/catalogues?is_light='true'",
                    request_id: "catalogueList"
                }
            ];
            this.$store.dispatch("batchRequestStore/" + api, requestArray).then(
                response => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    this.addProductModal = true;
                },
                error => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    //Error Handling for batch request
                    this.errorArr = this.getAPIErrorMessage(error.response);
                    this.errorDialog = true;
                }
            );
        },

        onImgError(id) {
            if (this.$refs["featuredImage" + id]) {
                this.$refs["featuredImage" + id].src =
                    "/images/default_product_image.png";
            }
        },

        /*
         * Edit Product Modal
         * */
        onEditView(id, isEdit) {
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            // set the edit id in store
            this.$store.commit("productStore/setEditId", id);
            //get by id to open and edit the role of particular id
            this.$store.dispatch("productStore/getById", id).then(
                response => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        if (isEdit) {
                            if (this.$refs.productModal) {
                                this.$refs.productModal.getSubCategory();
                            }
                            this.addProduct();
                        } else {
                            this.$store.commit(
                                "productStore/setProductList",
                                response.data
                            );
                            this.productViewModal = true;
                        }
                    }
                },
                error => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    this.errorArr = this.getAPIErrorMessage(error.response);
                    this.errorDialog = true;
                }
            );
        },
        /**
         * Product Child detail History Modal
         * @param id - Particular Product Listing Row Object ID
         */
        onEditImages(id) {
            this.$store.commit("productStore/setDetailViewId", id);
            this.$store.dispatch("productStore/getProductDetailById", id).then(
                response => {
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        this.editImagesDialog = true;
                    }
                },
                error => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    this.errorArr = this.getAPIErrorMessage(error.response);
                    this.errorDialog = true;
                }
            );
        },
        /**
         * Delete Data from row
         * @param id
         */
        deleteItem(row) {
            this.paramProps.idProps = row.id;
            this.paramProps.delete_allowed = true;
            this.paramProps.storeProps = "productStore";
            this.confirmation.title = this.$getConst("DELETE_TITLE");
            if (
                row.parent_id &&
                row.parent_id != "" &&
                row.parent_id != undefined
            ) {
                this.confirmation.description = this.$getConst(
                    "PARENT_CATEGORY_DELETE"
                );
            } else {
                this.confirmation.description = this.$getConst("WARNING");
            }
            this.modalOpen = true;
        },
        /**
         * Multiple Delete
         */
        multipleDelete() {
            let rowIds = [];
            this.selected.forEach((element, index) => {
                rowIds[index] = element.id;
            });

            this.deleteProps.ids = rowIds;
            this.deleteProps.store = "productStore";
            this.$refs.multipleDeleteBtn.deleteMulti();
        },
        /**
         *Refresh data on tab Click
         */
        refreshData() {
            var self = this;
            setTimeout(function() {
                if (self.tab == "tab1") {
                    self.refresh();
                } else if (self.tab == "tab2" && self.$refs.importdata) {
                    if (this.isImportLoaded) {
                        self.$refs.importdata.refreshImport();
                    }
                    this.isImportLoaded = true;
                }
            }, 100);
        },

        /**
         * Reset Filter
         */
        resetFilter() {
            this.category_id = "";
            this.sub_category_id = "";
            this.brand_id = "";
            this.catalogue_id = "";
            this.point = "";
            // Reset category list
            this.$store.commit("categoryStore/setSubCategoryList", []);
            this.applyFilter(false);
        },

        /**
         * Filter
         */
        applyFilter(fromCreate) {
            let filter = {};
            if (
                this.category_id != "" &&
                this.category_id != null &&
                this.category_id != undefined
            ) {
                filter.category_id = [this.category_id];
            }
            if (
                this.available_status != "" &&
                this.available_status != null &&
                this.available_status != undefined
            ) {
                filter.available_status = [this.available_status];
            }
            if (
                this.sub_category_id != "" &&
                this.sub_category_id != null &&
                this.sub_category_id != undefined
            ) {
                filter.sub_category_id = [this.sub_category_id];
            }
            if (
                this.point != "" &&
                this.point != null &&
                this.point != undefined
            ) {
                filter.cf = {};
                /*filter.cf = {};*/
                filter.cf.point = [{ "=": this.point }];
            }
            if (
                this.brand_id != "" &&
                this.brand_id != null &&
                this.brand_id != undefined
            ) {
                filter.brand_id = [this.brand_id];
            }
            if (
                this.catalogue_id != "" &&
                this.catalogue_id != null &&
                this.catalogue_id != ""
            ) {
                filter.pf = {};
                filter.pf.catalogues = [this.catalogue_id];
            }
            this.filterModel = filter;
            if (!fromCreate) {
                this.refresh();
            }
            this.filterMenu = false;
        }
    },
    mounted() {
        this.onModalClear("productStore", "clearModel");

        if (this.$route.params.willModalOpen) {
            setTimeout(() => {
                this.addProduct();
            }, 100);
        }

        var api = "getBatchProduct";
        var requestArray = [
            {
                url: "api/v1/brands?is_light='true'",
                request_id: "brandList"
            },
            {
                url: "api/v1/parent-categories?is_light='true'",
                request_id: "categoryList"
            },
            {
                url: "api/v1/catalogues?is_light='true'",
                request_id: "catalogueList"
            }
        ];
        this.$store.dispatch("batchRequestStore/" + api, requestArray).then(
            response => {},
            error => {
                //Error Handling for batch request
                this.errorArr = this.getAPIErrorMessage(error.response);
                this.errorDialog = true;
            }
        );
    },
    created() {
        // this.$store.commit("categoryStore/setSubCategoryList", []);
        //Redirect to respective page with selected filter
        if (
            this.$route.params.category_id ||
            this.$route.params.sub_category_id ||
            this.$route.params.brand_id ||
            this.$route.params.catalogue_id
        ) {
            if (this.$route.params.hasOwnProperty("category_id")) {
                this.category_id = this.$route.params.category_id;
            }
            if (this.$route.params.hasOwnProperty("sub_category_id")) {
                this.sub_category_id = this.$route.params.sub_category_id;
            }
            if (this.$route.params.hasOwnProperty("brand_id")) {
                this.brand_id = this.$route.params.brand_id;
            }
            if (this.$route.params.hasOwnProperty("catalogue_id")) {
                this.catalogue_id = this.$route.params.catalogue_id;
            }
            this.applyFilter(true);
        }
        if (this.$route.params) {
            if (this.$route.params.flag == "A") {
                this.available_status = "1";
            } else if (this.$route.params.flag == "O") {
                this.available_status = "0";
            } else {
                this.available_status = "";
            }
            this.applyFilter(true);
        }
    },
    beforeDestroy() {
        this.$store.commit("productStore/clearStore");
    }
});
