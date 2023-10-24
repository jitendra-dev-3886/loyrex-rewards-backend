import CommonServices from "../../common_services/common.js";
import ErrorModal from "../../partials/ErrorModal";
import ErrorBlockServer from "../../partials/ErrorBlockServer.vue";
import UploadImageModal from "./UploadImageModal.vue";
import VDropZone from "../../partials/VDropZone.vue";

import { mapState } from "vuex";

export default {
    data() {
        return {
            errorArr: [],
            errorDialog: false,
            errorMessage: "",
            validationMessages: {
                name: [{ key: "required", value: "Product name required" }],
                category_id: [{ key: "required", value: "Category required" }],
                catalogue_id: [
                    { key: "required", value: "Catalogues required" }
                ],
                sub_category_id: [
                    { key: "required", value: "Sub category required" }
                ],
                brand_id: [{ key: "required", value: "Brand required" }],
                featured_image: [
                    { key: "required", value: "Featured Image required" }
                ],
                description: [
                    { key: "required", value: "Description required" }
                ],
                point: [
                    { key: "required", value: "Point required" },
                    {
                        key: "min_value",
                        value: "Point should not be less than 0"
                    }
                ],
                available_status: [
                    { key: "required", value: "Availability required" }
                ],
                upload_images: [{ key: "required", value: "Gallery required" }]
            },
            loading: false,
            availabilityList: [
                { id: "1", name: "Available" },
                { id: "0", name: "Out of stock" }
            ],
            imageModal: false,
            isSubCategoryLoading: false,
            alreadyUploadedImages: 0,
            multipleImages: []
        };
    },
    components: {
        CommonServices,
        ErrorBlockServer,
        ErrorModal,
        UploadImageModal,
        VDropZone
    },
    props: ["value"],
    mixins: [CommonServices],
    computed: {
        ...mapState({
            model: state => state.productStore.model,
            isEditMode: state => state.productStore.editId > 0,
            editId: state => state.productStore.editId,
            brandList: state => state.brandStore.list,
            parentCategoryList: state => state.categoryStore.parentCategoryList,
            subCategoryList: state => state.categoryStore.subCategoryList,
            catalogueList: state => state.catalogueStore.list
        }),
        alreadyUploadedFeatureImage() {
            return this.model.featured_image_key ? 1 : 0;
        },
        getFeaturedImageValidation() {
            let valdationrule = "ext:jpeg,png,jpg,gif,svg|size:4000";
            if (!this.isEditMode) {
                valdationrule = `required|${valdationrule}`;
            }
            return valdationrule;
        },
        getUploadImageValidation() {
            let valdationrule = "ext:jpeg,png,jpg,gif,svg|size:4000";
            if (!this.isEditMode) {
                valdationrule = `required|${valdationrule}`;
            }
            return valdationrule;
        },
        selectAllCatalogues() {
            return (
                this.model.catalogue_id &&
                this.model.catalogue_id.length == this.catalogueList.length
            );
        },
        icon() {
            if (this.selectAllCatalogues) {
                return "check_box";
            }
            if (
                this.model.catalogue_id.length > 0 &&
                !this.selectAllCatalogues
            ) {
                return "indeterminate_check_box";
            }
            return "check_box_outline_blank";
        }
    },
    created() {
        if (this.isEditMode) {
            this.getSubCategory();
        }
    },
    methods: {
        removeFeatureImage(imageKey) {
            this.model.featured_image_key = "";
            this.model.featured_image_extension = "";
        },
        singleImageUploadSuccess(location) {
            const { key, extension, original } = this.extractFileProperties(
                location
            );
            this.model.featured_image_key = key;
            this.model.featured_image_extension = extension;
        },
        multiImageUploadSuccess(location) {
            this.multipleImages.push(this.extractFileProperties(location));
        },
        removeMultiUploadedImages(file) {
            const { key } = this.extractFileProperties(file.s3ObjectLocation);
            this.multipleImages = this.multipleImages.filter(
                item => item.key != key
            );
        },
        /**
         * Selecting and unselecting all the catalogue items
         */
        toggle() {
            this.$nextTick(() => {
                if (this.selectAllCatalogues) {
                    this.model.catalogue_id = [];
                } else {
                    this.model.catalogue_id = [];
                    this.catalogueList.map(item => {
                        this.model.catalogue_id.push(item.id);
                    });
                }
            });
        },
        /*
         * Submit action of form *
         */

        onSubmit() {
            this.model.available_status = this.model.available_status.toString();
            this.$validator.validate().then(valid => {
                var self = this;
                if (valid) {
                    self.loading = true;
                    const sendParamModel = this.model;
                    var apiName = "add";
                    var msgType = self.$getConst("CREATE_ACTION");
                    if (this.editId) {
                        apiName = "edit";
                        msgType = self.$getConst("UPDATE_ACTION");
                    }
                    sendParamModel.upload_images = this.multipleImages;
                    self.$store
                        .dispatch("productStore/" + apiName, {
                            model: this.model,
                            editId: this.editId
                        })
                        .then(
                            response => {
                                if (response.error) {
                                    self.loading = false;
                                    self.errorMessage = response.data.error;
                                } else {
                                    self.loading = false;
                                    // if no error this code will execute
                                    this.$store.commit(
                                        "snackbarStore/setMsg",
                                        msgType
                                    );
                                    this.onCancel();
                                    this.$parent.getData();
                                }
                            },
                            error => {
                                self.loading = false;
                                self.errorMessage = self.getAPIErrorMessage(
                                    error.response
                                );
                            }
                        );
                } else {
                }
            });
        },
        onCancel() {
            // clear model
            this.$store.commit("categoryStore/setSubCategoryList", []);
            this.onModalClear("productStore", "clearModel");
            this.onModalClear("productStore", "clearStore");
        },

        /**
         * Subcategory filter from category
         */
        getSubCategory(fromPage) {
            this.isSubCategoryLoading = true;
            this.$store
                .dispatch("categoryStore/getSubCategory", {
                    id: this.model.category_id
                })
                .then(
                    response => {
                        this.isSubCategoryLoading = false;
                        if (response.error) {
                            this.errorArr = response.data.error;
                            this.errorDialog = true;
                        } else {
                            // If comes from add product then reset the sub category
                            if (fromPage == "addProduct") {
                                this.model.sub_category_id = "";
                            }
                            // Reset category list
                            this.$store.commit(
                                "categoryStore/setSubCategoryList",
                                []
                            );
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

        /**
         * For view and delete upload image
         */
        onImageModal() {
            this.imageModal = true;
        }
    },
    mounted() {
        // clear errorMessage
        this.alreadyUploadedImages = this.model.upload_images
            ? this.model.upload_images.length
            : 0;
        this.errorMessage = "";
    }
};
