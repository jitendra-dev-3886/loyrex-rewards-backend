import CommonServices from "../../common_services/common.js";
import ErrorBlockServer from "../../partials/ErrorBlockServer";
import ErrorModal from "../../partials/ErrorModal";
import VoucherLinksModal from "./VoucherLinksModal";
import { mapState } from "vuex";

export default {
    name: "VoucherModal",
    components: {
        ErrorBlockServer,
        ErrorModal,
        VoucherLinksModal
    },
    props: ["value"],
    data() {
        return {
            date_init: true,
            is_popup_colse: false,
            search: null,
            isLoadingUsers: false,
            errorArr: [],
            errorDialog: false,
            errorMessage: "",
            validationMessages: {
                name: [{ key: "required", value: "Voucher name required" }],
                valid_till: [{ key: "required", value: "Valid till required" }],
                category_id: [
                    { key: "required", value: "Product category required" }
                ],
                product_id: [{ key: "required", value: "Product required" }],
                voucher_type: [{ key: "required", value: "Product required" }],
                points: [
                    { key: "required", value: "Points required" },
                    { key: "decimal", value: "Invalid value" },
                    {
                        key: "min_value",
                        value: "Invalid value"
                    }
                ],
                user_id: [{ key: "required", value: "Assign user required" }],
                voucher_status: [
                    { key: "required", value: "Voucher status required" }
                ],
                contact_number: [
                    { key: "min", value: "Contact no. must be 10 digits" },
                    { key: "max", value: "Contact no. must be 10 digits" }
                ],
                no_of_vouchers: [
                    { key: "required", value: "No of voucher required" },
                    {
                        key: "decimal",
                        value: "Invalid value"
                    },
                    {
                        key: "min_value",
                        value: "Invalid value"
                    },
                    {
                        key: "max_value",
                        value: "Max value of no. of voucher should be 100"
                    }
                ]
            },
            isSubmitting: false,
            dobMenu: false,
            todayDate: new Date().toISOString().slice(0, 10),
            show_password: false,
            checkEmailLoading: false,
            checkContactLoading: false,
            categoryProductList: [],
            voucherLinksModal: false,
            menu: false,
            voucherDetail: {},
            tempValidityDate: ""
        };
    },
    computed: {
        ...mapState({
            model: state => state.voucherStore.model,
            voucherTypes: state => state.voucherStore.voucherTypes,
            isEditMode: state => state.voucherStore.editId > 0,
            parentCategoryList: state => state.categoryStore.parentCategoryList,
            statusTypes: state => state.voucherStore.statusTypes
        }),
        userList() {
            let list = [];
            if (this.$store.state.userStore.userList) {
                list = JSON.parse(
                    JSON.stringify(this.$store.state.userStore.userList)
                );
                list.unshift({
                    id: "0",
                    first_name: "None",
                    last_name: "",
                    email: "",
                    contact_number: ""
                });
            }
            return list;
        },
        computedValidityDateFormatted() {
            //code for deafult date format of current date
            // if (!this.isEditMode) {
            //     this.tempValidityDate = new Date().toISOString().slice(0, 10);
            // }
            if (this.date_init) {
                this.tempValidityDate = this.model.valid_till
                    ? this.model.valid_till
                    : this.tempValidityDate;
                this.date_init = !this.date_init;
            }
            if (this.is_popup_colse) {
                this.model.valid_till
                    ? (this.tempValidityDate = this.model.valid_till)
                    : this.tempValidityDate;
                this.is_popup_colse = !this.is_popup_colse;
                this.date_init = !this.date_init;
            }
            return this.tempValidityDate != ""
                ? this.getDatePickerDateFormat(this.tempValidityDate)
                : "";
        }
    },
    mixins: [CommonServices],
    methods: {
        convertToBackendDateFormat() {
            this.model.valid_till = this.tempValidityDate
                ? this.getDateFormatt(this.tempValidityDate)
                : "";
        },
        onSubmit() {
            this.$validator.validate().then(valid => {
                this.convertToBackendDateFormat();
                var self = this;
                if (valid) {
                    let flag = true;
                    self.isSubmitting = true;
                    var apiName = "add";
                    var editId = "";
                    var msgType = self.$getConst("CREATE_ACTION");

                    // For Edit Voucher
                    if (self.$store.state.voucherStore.editId > 0) {
                        apiName = "edit";
                        editId = self.$store.state.voucherStore.editId;
                        msgType = self.$getConst("UPDATE_ACTION");
                    }
                    this.model.user_id = this.model.user.id;
                    if (this.model.voucher_type == 1) {
                        this.model.product_id = "";
                    }
                    if (flag) {
                        self.$store
                            .dispatch("voucherStore/" + apiName, {
                                model: this.model,
                                editId: editId
                            })
                            .then(
                                response => {
                                    if (response.error) {
                                        self.isSubmitting = false;
                                        self.errorMessage = response.data.error;
                                    } else {
                                        self.isSubmitting = false;
                                        this.onCancel();
                                        // Success message
                                        self.$store.commit(
                                            "snackbarStore/setMsg",
                                            msgType
                                        );
                                        this.$parent.getData();
                                        this.voucherDetail = response.data.data;
                                        this.voucherLinksModal = true;
                                    }
                                },
                                error => {
                                    self.isSubmitting = false;
                                    self.errorMessage = self.getAPIErrorMessage(
                                        error.response
                                    );
                                }
                            );
                    } else {
                        self.isSubmitting = false;
                        self.errorArr = this.$getConst(
                            "REWARD_NEGATIVE_POINTS"
                        );
                        self.errorDialog = true;
                    }
                }
            });
        },
        /* Cancel */
        onCancel() {
            // this.$parent.onModalClear('voucherStore', 'clearStore');
            this.categoryProductList = [];
            this.onModalClear("voucherStore", "clearModel");
            this.onModalClear("userStore", "clearUserList");
            this.errorMessage = "";
            //code for deafult date format of current date
            // this.tempValidityDate = this.isEditMode
            // ? this.model.valid_till
            //     ? this.model.valid_till
            //     : ""
            // : new Date().toISOString().slice(0, 10);
            this.tempValidityDate = "";
            this.isSubmitting = false;
            this.$validator.reset();
            this.is_popup_colse = true;
            this.date_init = true;
            this.$emit("input"); //Close Pop-up
        },

        assignUserChange() {
            this.$validator.reset();
            this.model.no_of_vouchers =
                this.model.user_id != "0" ? "" : this.model.no_of_vouchers;
        },

        /**
         * Call when change category in dropdown
         */
        getProductsByCategory() {
            this.$store
                .dispatch("productStore/getProductsByCategory", {
                    id:
                        this.model.category_id != ""
                            ? [this.model.category_id]
                            : [],
                    filter: { availabilty: "" }
                })
                .then(
                    response => {
                        if (response.error) {
                            this.errorArr = response.data.error;
                            this.errorDialog = true;
                        } else {
                            this.categoryProductList = [];
                            if (
                                response.data.data &&
                                response.data.data.length > 0
                            ) {
                                this.categoryProductList = JSON.parse(
                                    JSON.stringify(response.data.data)
                                );
                            }
                        }
                    },
                    error => {
                        this.errorArr = this.getAPIErrorMessage(error.response);
                        this.errorDialog = true;
                    }
                );
        }
    },
    watch: {
        search(val) {
            clearTimeout(this._timerId);
            // delay new call 500ms for search
            if (val != null && val != "") {
                this._timerId = setTimeout(() => {
                    //Actual API call
                    this.isLoadingUsers = true;
                    this.$store
                        .dispatch("userStore/getUserList", {
                            is_light: "true",
                            per_page: this.$getConst("DROPDOWN_PER_PAGE"),
                            search: val,
                            requestType: "modal"
                        })
                        .then(response => {
                            this.isLoadingUsers = false;
                        });
                }, 500);
            }
        }
    }
};
