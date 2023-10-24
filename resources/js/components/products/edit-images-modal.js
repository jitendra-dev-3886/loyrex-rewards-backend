import CommonServices from "../../common_services/common.js";
import { mapState } from "vuex";
import DeleteConfirm from "../../partials/DeleteConfirm.vue";
import ErrorBlockServer from "../../partials/ErrorBlockServer.vue";
import ErrorModal from "../../partials/ErrorModal.vue";
import VDropZone from "../../partials/VDropZone.vue";

export default {
    props: ["value"],
    components: {
        DeleteConfirm,
        ErrorBlockServer,
        ErrorModal,
        VDropZone
    },
    mixins: [CommonServices],
    data() {
        return {
            menu: false,
            deleteConfirm: false,
            paramProps: {
                idProps: "",
                indexProps: "",
                isFeature: ""
            },
            deleting: false,
            productImages: {
                id: "",
                images: []
            },
            featured_image_key: "",
            featured_image_extension: "",
            addImagesLoading: false,
            errorMessage: "",
            errorArr: [],
            errorDialog: false,
            validationMessages: {
                images: [
                    { key: "required", value: "Additional images required" },
                    {
                        key: "valid_file_length",
                        value: "Maximum image upload limit is 4"
                    },
                    {
                        key: "ext",
                        value: "Please upload jpeg,png,jpg,gif,svg format only"
                    },
                    { key: "size", value: "File size should be less than 4 MB" }
                ],
                featured_image: [
                    { key: "required", value: "Featured image required" },
                    {
                        key: "size",
                        value: "File size should be less than 4 MB!"
                    },
                    {
                        key: "ext",
                        value: "Please upload only jpeg,png,jpg,gif,svg format"
                    }
                ]
            },
            model1: "tab1",
            isUploading: false
        };
    },
    computed: {
        ...mapState({
            detailViewData: state => state.productStore.detailViewModel
        }),
        isFeatureImageDeleted() {
            return (
                this.detailViewData.featured_image.includes(
                    "images/default_product_image.png"
                ) || this.detailViewData.featured_image == ""
            );
        }
    },
    methods: {
        /**
         * Delete Particular Image in Product Detail Images Tab
         * @param id - Particular Product Child Image ID
         * @param index - index
         */
        confirmDelete(id, index, isFeature) {
            this.paramProps.idProps = id;
            this.paramProps.indexProps = index;
            this.paramProps.isFeature = isFeature;
            this.deleteConfirm = true;
        },

        /**
         * Delete Confirm emit Function from Delete Confirm Modal
         * @param payload - payload
         */
        deleteImage(payload) {
            this.deleting = true;
            this.deleteConfirm = false;
            let model = {
                is_feature: payload.isFeature ? "1" : "0",
                product_id: this.detailViewData.id
            };

            if (payload.isFeature == false) {
                model.image_id = payload.idProps;
            }
            this.$store.dispatch("productStore/deleteImage", model).then(
                response => {
                    this.deleting = false;
                    if (response.data.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        if (payload.isFeature == false) {
                            this.detailViewData.upload_images.splice(
                                payload.indexProps,
                                1
                            );
                        } else {
                            this.detailViewData.featured_image = "";
                        }
                        this.$store.commit(
                            "productStore/setDetailViewModel",
                            this.detailViewData
                        );
                        this.$store.commit(
                            "snackbarStore/setMsg",
                            this.$getConst("DELETE_PRODUCT_IMAGE")
                        );
                    }
                },
                error => {
                    this.deleting = false;
                    this.errorArr = this.getAPIErrorMessage(error.response);
                    this.errorDialog = true;
                }
            );
        },

        singleImageUploadSuccess(location) {
            const { key, extension, original } = this.extractFileProperties(
                location
            );
            this.featured_image_key = key;
            this.featured_image_extension = extension;
        },

        removeFeatureImage(imageKey) {
            this.featured_image_key = "";
            this.featured_image_extension = "";
        },

        multiImageUploadSuccess(location) {
            this.productImages.images.push(
                this.extractFileProperties(location)
            );
        },

        removemultiUploadImage(file) {
            const { key } = this.extractFileProperties(file.s3ObjectLocation);
            this.productImages.images = this.productImages.images.filter(
                item => item.key != key
            );
        },

        /**
         *  Add Multiple Images in Image Tab
         */
        uploadImages(isFeature) {
            this.$validator
                .validateAll(
                    isFeature ? "addFeatureImage" : "addMultipleImages"
                )
                .then(valid => {
                    if (valid) {
                        this.addImagesLoading = true;
                        let params = {
                            product_id: this.detailViewData.id,
                            is_feature: isFeature ? "1" : "0"
                        };
                        if (isFeature) {
                            params.product_images = [
                                {
                                    extension: this.featured_image_extension,
                                    key: this.featured_image_key
                                }
                            ];
                        } else {
                            params.product_images = this.productImages.images;
                        }

                        this.errorMessage = "";
                        this.errorArr = "";
                        this.$store
                            .dispatch("productStore/addMultipleImages", params)
                            .then(
                                response => {
                                    this.addImagesLoading = false;
                                    if (response.error) {
                                        this.errorMessage = response.data.error;
                                    } else {
                                        if (isFeature) {
                                            if (
                                                response.data.data.upload_images
                                            ) {
                                                this.detailViewData.featured_image =
                                                    response.data.data.upload_images.featured_image;
                                            }
                                        } else {
                                            response.data.data.upload_images.map(
                                                item => {
                                                    this.detailViewData.upload_images.push(
                                                        item
                                                    );
                                                }
                                            );
                                        }
                                        this.$store.commit(
                                            "productStore/setDetailViewModel",
                                            this.detailViewData
                                        );
                                        this.$store.commit(
                                            "snackbarStore/setMsg",
                                            this.$getConst("ADD_PRODUCT_IMAGE")
                                        );
                                        this.featured_image_extension = "";
                                        this.featured_image_key = "";
                                        this.productImages.id = "";
                                        this.productImages.images = [];
                                        if (this.$refs.upload_images) {
                                            this.$refs.upload_images.removeAllFiles();
                                        }
                                        if (this.$refs.featured_image_key) {
                                            this.$refs.featured_image_key.removeAllFiles();
                                        }
                                        this.$validator.reset();
                                        this.$parent.getData();
                                    }
                                },
                                error => {
                                    this.addImagesLoading = false;
                                    this.errorArr = this.getAPIErrorMessage(
                                        error.response
                                    );
                                    this.errorDialog = true;
                                }
                            );
                    }
                });
        },
        onCancel() {
            this.model1 = "tab1";
            this.$validator.reset();
            this.errorMessage = "";
            this.errorArr = [];
            this.featured_image_extension = "";
            this.featured_image_key = "";
            this.$emit("input"); //Close Pop-up
            this.$store.commit("productStore/clearDetailViewModel");
        }
    },
    created() {
        this.$validator.extend(
            "valid_file_length",
            {
                validate: value => {
                    let productImagesLength = this.detailViewData
                        ? this.detailViewData.upload_images.length
                        : 0;
                    return parseInt(productImagesLength + value.length) <= 4;
                }
            },
            "Maximum image upload limit is 4"
        );
    }
};
