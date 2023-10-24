import CommonServices from "../../common_services/common.js";
import ErrorBlockServer from "../../partials/ErrorBlockServer";
import ErrorModal from "../../partials/ErrorModal";
import { mapState, mapActions } from "vuex";

export default {
    name: "UserModal",
    components: {
        ErrorBlockServer,
        ErrorModal
    },
    props: ["value"],
    data() {
        return {
            errorArr: [],
            errorDialog: false,
            errorMessage: "",
            validationMessages: {
                first_name: [{ key: "required", value: "First name required" }],
                last_name: [{ key: "required", value: "Last name required" }],
                job_title: [{ key: "required", value: "Job title required" }],
                email: [
                    { key: "required", value: "Email required" },
                    {
                        key: "email",
                        value: "Invalid email"
                    },
                    {
                        key: "email_check",
                        value: "Email has already been taken."
                    }
                ],
                password: [
                    { key: "required", value: "Password required" },
                    {
                        key: "regex",
                        value:
                            "Password should be between 8 to 32, should contain at least one numeric character, one lowercase letter, one uppercase letter and one special character"
                    }
                ],

                category_id: [
                    { key: "required", value: "Product category required" }
                ],
                reward_point: [
                    { key: "required", value: "Reward point required" },
                    { key: "decimal", value: "Invalid value" },
                    {
                        key: "min_value",
                        value: "Invalid value"
                    },
                    {
                        key: "max",
                        value: "Reward point value must be less than 6 digits"
                    }
                ],
                contact_no: [
                    { key: "required", value: "Contact no required" },
                    {
                        key: "min",
                        value: "Contact no must be 10 digits"
                    },
                    { key: "max", value: "Contact no must be 10 digits" },
                    {
                        key: "contact_check",
                        value: "Contact no has already been taken"
                    }
                ],
                userGroupList: [
                    { key: "required", value: "User group required" }
                ]
            },
            isSubmitting: false,
            show_password: false,
            checkEmailLoading: false,
            checkContactLoading: false,
            categoryProductList: []
        };
    },
    computed: {
        ...mapState({
            model: state => state.userStore.model,
            isEditMode: state => state.userStore.editId > 0,
            parentCategoryList: state => state.categoryStore.parentCategoryList,
            userGroupList: state => state.userGroupStore.userGroupList,
            userGroupmodel: state => state.userGroupStore.userGroupmodel
        }),
        selectedAllCategory() {
            return (
                this.model.category_id.length == this.parentCategoryList.length
            );
        },
        selectedAllGroup() {
            return this.model.userGroup_id.length == this.userGroupList.length;
        },
        someSelectedCategory() {
            return (
                this.model.category_id.length > 0 && !this.selectedAllCategory
            );
        },
        someSelectedGroup() {
            return this.model.userGroup_id.length > 0 && !this.selectedAllGroup;
        },
        icon() {
            if (this.selectedAllCategory) {
                return "check_box";
            }
            if (this.someSelectedCategory) {
                return "indeterminate_check_box";
            }
            return "check_box_outline_blank";
        },
        groupIcon() {
            if (this.selectedAllGroup) {
                return "check_box";
            }
            if (this.someSelectedGroup) {
                return "indeterminate_check_box";
            }
            return "check_box_outline_blank";
        },
        calculatedRewardPoints() {
            if (
                this.model.action_type == "1" &&
                this.model.reward_points != ""
            ) {
                return parseInt(
                    parseInt(this.model.temp_reward_points) +
                        parseInt(this.model.reward_points)
                );
            } else if (
                this.model.action_type == "0" &&
                this.model.reward_points != ""
            ) {
                return parseInt(
                    parseInt(this.model.temp_reward_points) -
                        parseInt(this.model.reward_points)
                );
            }
            return this.model.reward_points == "" ||
                this.model.reward_points != ""
                ? this.model.temp_reward_points
                : 0;
        }
    },
    mixins: [CommonServices],
    methods: {
        ...mapActions("userStore", ["checkContact", "checkEmail"]),
        onSubmit() {
            this.$validator.validate().then(valid => {
                var self = this;
                if (valid) {
                    let flag = true;
                    self.isSubmitting = true;
                    var apiName = "add";
                    var editId = "";
                    var msgType = self.$getConst("CREATE_ACTION");

                    // For Edit User
                    if (self.$store.state.userStore.editId > 0) {
                        apiName = "edit";
                        editId = self.$store.state.userStore.editId;
                        msgType = self.$getConst("UPDATE_ACTION");
                        if (parseInt(this.calculatedRewardPoints) < 0) {
                            flag = false;
                        }
                    }
                    this.model.reward_points = this.model.reward_points
                        ? this.model.reward_points
                        : 0;
                    /* if (this.model.product_id["0"] == "0") {
                        this.model.product_id = [];
                    } */
                    if (flag) {
                        self.$store
                            .dispatch("userStore/" + apiName, {
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
        /**
         * Handling checkbox product selection
         */
        /*  onChangeProduct() {
            if (this.model.product_id.length > 0) {
                let filteredArray = this.multipleSelectAllOptionFunctionality(this.model.product_id);
                this.model.product_id = filteredArray.selectedValueArray;
            }
        }, */
        onChangeGroup() {
            if (this.model.userGroup.length > 0) {
                let filteredArray = this.multipleSelectAllOptionFunctionality(
                    this.model.userGroup
                );
                this.model.userGroup = filteredArray.selectedValueArray;
            }
        },
        /* Cancel */
        onCancel() {
            // this.$parent.onModalClear('userStore', 'clearStore');
            this.categoryProductList = [];
            this.onModalClear("userStore", "clearModel");
            this.errorMessage = "";
            this.isSubmitting = false;
            this.$validator.reset();
            this.$emit("input"); //Close Pop-up
        },
        /**
         * Selecting and unselecting all the caltegory items
         */
        /* toggle () {

            this.$nextTick(() => {
                if (this.selectedAllCategory) {
                    this.model.category_id = [];
                } else {
                    this.model.category_id = [];
                    this.parentCategoryList.map(item => {
                        this.model.category_id.push(item.id);
                    });
                }
                if(this.model.category_id) {
                    this.getProductsByCategory();
                }
            })
        }, */
        toggle_group() {
            this.$nextTick(() => {
                if (this.selectedAllGroup) {
                    this.model.userGroup_id = [];
                } else {
                    this.model.userGroup_id = [];
                    this.userGroupList.map(item => {
                        this.model.userGroup_id.push(item.id);
                    });
                }
                /*  if(this.userGroupmodel.id) {
                    this.getUserByGroup();
                }*/
            });
        },
        /**
         * call when reward points changing
         */
        onChangePoints(fromRewardActionType) {
            this.calculatedRewardPoints;
            if (fromRewardActionType) {
                this.model.reward_points = 0;
            }
        }
        /**
         * Call when change category in dropdown
         */
        /*  getProductsByCategory() {
            this.$store.dispatch("productStore/getProductsByCategory", {id: this.model.category_id, filter:  {availabilty: ''}}).then((response) => {
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {
                    this.categoryProductList = [];
                    if(response.data.data && response.data.data.length > 0) {
                        this.categoryProductList = JSON.parse(JSON.stringify(response.data.data));
                        this.categoryProductList.unshift({id: '0', name: 'Select All'});
                    }
                }
            }, error => {
                this.errorArr = this.getAPIErrorMessage(error.response);
                this.errorDialog = true;
            });
        }, */
    },
    created() {
        this.$validator.extend("contact_check", {
            validate: value => {
                return new Promise(resolve => {
                    var editId = "";
                    if (this.$store.state.userStore.editId > 0) {
                        editId = this.$store.state.userStore.editId;
                    }
                    this.checkContactLoading = true;
                    this.checkContact({
                        id: editId,
                        contact_number: value
                    }).then(
                        response => {
                            resolve({
                                valid: response.data.data
                            });
                            this.checkContactLoading = false;
                            this.errorMessage = "";
                        },
                        error => {
                            this.checkContactLoading = false;
                            this.errorMessage = this.getAPIErrorMessage(
                                error.response
                            );
                        }
                    );
                });
            }
        });
        this.$validator.extend("email_check", {
            validate: value => {
                return new Promise(resolve => {
                    if (value != null && value != "") {
                        var editId = "";
                        if (this.$store.state.userStore.editId > 0) {
                            editId = this.$store.state.userStore.editId;
                        }
                        this.checkEmailLoading = true;
                        this.checkEmail({
                            id: editId,
                            email: value
                        }).then(
                            response => {
                                resolve({
                                    valid: response.data.data
                                });
                                this.checkEmailLoading = false;
                                this.errorMessage = "";
                            },
                            error => {
                                this.checkEmailLoading = false;
                                this.errorMessage = this.getAPIErrorMessage(
                                    error.response
                                );
                            }
                        );
                    }
                });
            }
        });
    }
};
