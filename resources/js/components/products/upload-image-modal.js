import { mapGetters, mapState } from "vuex";
import CommonServices from "../../common_services/common";
import ErrorBlockServer from "../../partials/ErrorBlockServer.vue";
import DeleteConfirm from "../../partials/DeleteConfirm.vue";
import ErrorModal from "../../partials/ErrorModal.vue";

export default {
    name: "UploadImageModal",
    components: {
        ErrorBlockServer,
        DeleteConfirm,
        ErrorModal
    },
    props: ["value","product_id"],
    mixins: [CommonServices],
    data: function() {
        var self = this;
        return {
            email: "",
            errorMessage: "",
            errorArr: [],
            errorDialog: false,
            deleteConfirm: false,
            paramProps: {
                idProps: "",
                indexProps: ""
            }
        };
    },
    computed: {
        ...mapState({
            uploadImageList: state => state.productStore.uploadImageList,
            detailViewData: state => state.productStore.detailViewModel
        })
    },
    methods: {
        /**
         * Cancel Method
         */
        onCancel() {
            this.errorMessage = "";
            this.$emit("input");
        },

        /* Delete Modal  */
        confirmDelete(id, index) {
            this.paramProps.idProps = id;
            this.paramProps.product_idProps = this.product_id;
            this.paramProps.indexProps = index;
            this.deleteConfirm = true;
        },

        /* Delete Image */
        deleteImage(payload) {
            this.deleteConfirm = false;
            let model = {
                is_feature: payload.isFeature ? "1" : "0",
                product_id: payload.product_idProps,
                image_id : payload.idProps,
            };
            this.$store
                .dispatch("productStore/deleteImage", model)
                .then(
                    response => {
                        this.deleting = false;
                        if (response.error) {
                            this.errorArr = response.data.error;
                            this.errorDialog = true;
                        } else {
                            this.uploadImageList.splice(payload.indexProps, 1); // remove the image that we want to delete
                            this.$store.commit(
                                "productStore/setUploadImageList",
                                this.uploadImageList
                            ); // set upload image list
                            this.$store.commit(
                                "snackbarStore/setMsg",
                                this.$getConst("DELETE_ACTION")
                            ); //Delete msg
                        }
                    },
                    error => {
                        this.errorArr = this.getModalAPIerrorMessage(error);
                        this.errorDialog = true;
                    }
                );
        }
    }
};
