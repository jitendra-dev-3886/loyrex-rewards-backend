import CommonServices from "../../common_services/common.js";
import ErrorBlock from "../../partials/ErrorBlock.vue";
import ErrorBlockServer from "../../partials/ErrorBlockServer.vue";
import { mapState } from "vuex";
import { REMOVE_BODY_CLASSNAME } from "../../store/htmlclass.module";

export default {
    data() {
        return {
            errorMessage: "",
            validationMessages: {
                courier_name: [{ key: "required", value: "Delivey required" }],
                tracking_id: [
                    { key: "required", value: "Tracking id required" }
                ],
                courier_link: [
                    { key: "required", value: "Tracking link required" }
                ]
            },
            statusLoading: false,
            deliveryLoading: false
        };
    },
    components: {
        CommonServices,
        ErrorBlock,
        ErrorBlockServer
    },
    props: ["value"],
    mixins: [CommonServices],
    computed: {
        ...mapState({
            model: state => state.orderStore.model,
            isEditMode: state => state.orderStore.editId > 0,
            orderStatusList: state => state.orderStore.orderStatusList
        })
    },
    methods: {
        /*
         * Order Listing redirect
         **/
        orderListing() {
            this.$router.push("/orders");
        },

        /*
         * Order status change functionality
         *
         **/
        changeOrderStatus() {
            //TODO: If order_status firld is mandatory then do uncomment the below code.
            // if (this.model.order_status) {
            this.statusLoading = true;
            // apiName is method to call the from the store
            var editId = "";
            editId = this.$store.state.orderStore.editId;
            var msgType = this.$getConst("UPDATE_ACTION");

            let sendData = {
                status: this.model.order_status,
                remark: this.model.order_status_remark
            };
            this.$store
                .dispatch("orderStore/changeOrderStatus", {
                    model: sendData,
                    editId: editId
                })
                .then(
                    response => {
                        if (response.error) {
                            // loader disable if any error and display the error
                            this.statusLoading = false;
                            this.errorMessage = response.error;
                        } else {
                            // if no error this code wiil execute
                            this.$store.commit("snackbarStore/setMsg", msgType);
                            this.onCancel();
                            this.statusLoading = false;
                            this.$store.dispatch("orderStore/getAll").then(
                                response => {
                                    if (response.error) {
                                        this.errorArr = response.data.error;
                                        this.errorDialog = true;
                                    } else {
                                        this.$store.commit(
                                            "orderStore/setModel",
                                            response.data.data
                                        );
                                    }
                                },
                                error => {
                                    this.errorArr = this.getModalAPIerrorMessage(
                                        error
                                    );
                                    this.errorDialog = true;
                                }
                            );
                        }
                    },
                    error => {
                        // loader disable if any error and display the error
                        this.statusLoading = false;
                        this.errorMessage = this.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
            // }
        },

        /*
         * Order delivery detail change functionality
         * */
        changeDeliveryDetail() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.deliveryLoading = true;
                    // apiName is method to call the from the store
                    var editId = "";
                    editId = this.$store.state.orderStore.editId;
                    var msgType = this.$getConst("UPDATE_ACTION");

                    let sendData = {
                        courier_name: this.model.courier_name,
                        tracking_id: this.model.tracking_id,
                        courier_link: this.model.courier_link
                    };
                    this.$store
                        .dispatch("orderStore/changeDeliveryDetails", {
                            model: sendData,
                            editId: editId
                        })
                        .then(
                            response => {
                                if (response.error) {
                                    // loader disable if any error and display the error
                                    this.deliveryLoading = false;
                                    this.errorMessage = response.error;
                                } else {
                                    // if no error this code wiil execute
                                    this.$store.commit(
                                        "snackbarStore/setMsg",
                                        msgType
                                    );
                                    this.onCancel();
                                    this.deliveryLoading = false;
                                    this.$store
                                        .dispatch("orderStore/getAll")
                                        .then(
                                            response => {
                                                if (response.error) {
                                                    this.errorArr =
                                                        response.data.error;
                                                    this.errorDialog = true;
                                                } else {
                                                    this.$store.commit(
                                                        "orderStore/setModel",
                                                        response.data.data
                                                    );
                                                }
                                            },
                                            error => {
                                                this.errorArr = this.getModalAPIerrorMessage(
                                                    error
                                                );
                                                this.errorDialog = true;
                                            }
                                        );
                                }
                            },
                            error => {
                                // loader disable if any error and display the error
                                this.deliveryLoading = false;
                                this.errorMessage = this.getAPIErrorMessage(
                                    error.response
                                );
                            }
                        );
                }
            });
        },

        onCancel() {
            // clear model
            this.onModalClear("orderStore", "clearStore");
        }
    },
    mounted() {
        // clear errorMessage
        this.errorMessage = "";
        this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
    }
};
