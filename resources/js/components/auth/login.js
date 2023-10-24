import CommonServices from "../../common_services/common.js";
import ErrorBlockServer from "../../partials/ErrorBlockServer";
import ForgotPasswordModal from "./ForgotPasswordModal.vue";
import Snackbar from "../../partials/Snackbar.vue";
import PermissionDialog from "../../partials/PermissionDialog";
import { mapState } from "vuex";

export default {
    name: "Login",
    data() {
        return {
            errorMessage: "",
            showPassword: false,
            //Validation Message
            validationMessages: {
                email: [
                    { key: "required", value: "Authorised email ID required" },
                    {
                        key: "email",
                        value: "Valid Authorised email ID required"
                    }
                ],
                password: [
                    { key: "required", value: "Password required" },
                    {
                        key: "min",
                        value: "Password length should be at least 6"
                    }
                ]
            },
            //login info
            loginDetail: {
                email: "",
                password: ""
            },
            forgotPasswordDialog: false,
            isSubmitting: false
        };
    },
    components: {
        ForgotPasswordModal,
        Snackbar,
        ErrorBlockServer,
        PermissionDialog
    },
    mixins: [CommonServices],
    methods: {
        /**
         * Login Submit Method
         */
        onSubmit() {
            this.$validator.validateAll("loginform").then(valid => {
                if (valid) {
                    this.isSubmitting = true;
                    this.$store
                        .dispatch("userStore/login", {
                            loginDetail: this.loginDetail
                        })
                        .then(response => {
                            if (response.error) {
                                // loader disable if any error and display the error
                                this.isSubmitting = false;
                                this.errorMessage = response.error;
                            } else {
                                this.errorMessage = "";
                                this.isSubmitting = false;

                                // Set Data of Current user in store
                                this.$store.commit(
                                    "userStore/setCurrentUserData",
                                    response.data.data
                                );

                                // Set permission data
                                if (
                                    response.data &&
                                    response.data.data.permissions &&
                                    response.data.data.permissions.length > 0
                                ) {
                                    this.$store.commit(
                                        "permissionStore/setUserPermissions",
                                        response.data.data.permissions
                                    );
                                }
                                this.$router.push("/dashboard");
                            }
                        })
                        // If Login has Error
                        .catch(err => {
                            this.isSubmitting = false;
                            this.errorMessage = this.getAPIErrorMessage(
                                err.response
                            );
                        });
                } else {
                    this.errorMessage = "";
                }
            });
        },

        /**
         * Forgot Password Emit Method
         */
        forgotPassword(payload) {
            this.$store
                .dispatch("forgotPasswordStore/sendForgotPasswordEmail", {
                    email: payload
                })
                .then(
                    response => {
                        if (response.error) {
                            this.errorMessage = response.data.error;
                        } else {
                            this.$store.commit(
                                "snackbarStore/setMsg",
                                this.$getConst("EMAIL_SEND_MESSAGE")
                            );
                        }
                    },
                    error => {
                        this.errorMessage = this.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
        }
    },
    computed: {
        ...mapState({
            snackbar: state => state.snackbarStore.snackbar,
            permissionDialog: state => state.permissionStore.permissionDialog
        })
    }
};
