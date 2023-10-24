import CommonServices from "../../common_services/common.js";
import ErrorModal from "../../partials/ErrorModal";
import ErrorBlockServer from "../../partials/ErrorBlockServer.vue";
import { mapActions, mapState } from "vuex";

export default {
    data() {
        return {
            errorArr: [],
            errorDialog: false,
            errorMessage: "",
            password_rules:
                "^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$",
            validationMessages: {
                first_name: [{ key: "required", value: "First name required" }],
                last_name: [{ key: "required", value: "Last name required" }],
                email: [
                    { key: "required", value: "Email required" },
                    { key: "email", value: "Valid email required" },
                    { key: "emailcheck", value: "Email has already been taken" }
                ],
                password: [
                    { key: "required", value: "Password required" },
                    {
                        key: "min",
                        value: "Password length should be at least 8"
                    },
                    {
                        key: "regex",
                        value:
                            "Password contains atleast One Uppercase, One Lowercase, One Numeric and One Special Character"
                    }
                ],
                contact_number: [
                    { key: "required", value: "Contact no. required" },
                    { key: "min", value: "Contact no. must be 10 digits" },
                    { key: "max", value: "Contact no. must be 10 digits" }
                ],
                role_id: [{ key: "required", value: "Role required" }]
            },
            loading: false,
            checkEmailLoading: false,
            show_password: false
        };
    },
    components: {
        CommonServices,
        ErrorBlockServer,
        ErrorModal
    },
    props: ["value"],
    mixins: [CommonServices],
    computed: {
        ...mapState({
            model: state => state.adminStore.model,
            isEditMode: state => state.adminStore.editId > 0,
            roleList: state => state.roleStore.roledropdownlist
        })
    },
    methods: {
        ...mapActions("adminStore", ["checkEmail"]),
        /*
         * Submit action of form*/
        addAction() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    // loader enable
                    this.loading = true;
                    // apiName is method to call the from the store
                    var apiName = "add";
                    var editId = "";
                    var msgType = this.$getConst("CREATE_ACTION");
                    if (this.$store.state.adminStore.editId > 0) {
                        apiName = "edit";
                        editId = this.$store.state.adminStore.editId;
                        msgType = this.$getConst("UPDATE_ACTION");
                    }
                    this.$store
                        .dispatch("adminStore/" + apiName, {
                            model: this.model,
                            editId: editId
                        })
                        .then(
                            response => {
                                if (response.error) {
                                    // loader disable if any error and display the error
                                    this.loading = false;
                                    this.errorMessage = response.error;
                                } else {
                                    // if no error this code wiil execute
                                    this.$store.commit(
                                        "snackbarStore/setMsg",
                                        msgType
                                    );
                                    this.onCancel();
                                    this.$parent.getData();
                                    this.loading = false;
                                }
                            },
                            error => {
                                // loader disable if any error and display the error
                                this.loading = false;
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
            this.onModalClear("adminStore", "clearStore");
            this.onModalClear("adminStore", "clearModel");
        }
    },
    mounted() {
        // clear errorMessage
        this.errorMessage = "";
    },
    created() {
        // this.onModalClear('adminStore', 'clearCreateModel');
        this.$validator.extend("emailcheck", {
            validate: value => {
                return new Promise(resolve => {
                    if (value != null && value != "") {
                        var editId = "";
                        if (this.$store.state.adminStore.editId > 0) {
                            editId = this.$store.state.adminStore.editId;
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
                                resolve({
                                    valid: false
                                });
                            }
                        );
                    }
                });
            }
        });
    }
};
