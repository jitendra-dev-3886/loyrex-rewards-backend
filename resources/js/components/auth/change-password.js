import { mapGetters, mapState } from "vuex";
import CommonServices from "../../common_services/common";
import ErrorBlockServer from "../../partials/ErrorBlockServer.vue";
export default {
    name: "ChangePassword",
    props: ["value"],
    components: {
        ErrorBlockServer
    },
    data() {
        return {
            errorMessage: "",
            isSubmitting: false,
            show_old_password: false,
            show_new_password: false,
            show_new_confirmation_password: false,
            validationMessages: {
                old_password: [
                    { key: "required", value: "Current password required" }
                ],
                new_password: [
                    { key: "required", value: "New password required" },
                    {
                        key: "regex",
                        value:
                            "New password should be between 8 to 32, should contain at least one numeric character, one lowercase letter, one uppercase letter and one special character"
                    }
                ],
                confirm_password: [
                    { key: "required", value: "Confirm new password required" },
                    {
                        key: "confirmed",
                        value: "New password confirmation does not match"
                    }
                ]
            }
        };
    },
    mixins: [CommonServices],
    computed: {
        ...mapState({
            model: state => state.changePasswordStore.model
        })
    },
    methods: {
        /**
         * Submit of Change Password Modal
         */
        onSubmit() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.isSubmitting = true;
                    this.$store
                        .dispatch(
                            "changePasswordStore/changePassword",
                            this.model
                        )
                        .then(
                            response => {
                                if (response.error) {
                                    this.isSubmitting = false;
                                    this.errorMessage = response.data.error;
                                } else {
                                    this.isSubmitting = false;
                                    this.$store.commit(
                                        "snackbarStore/setMsg",
                                        this.$getConst("CHANGED_PASSWORD")
                                    );
                                    this.onCancel();
                                }
                            },
                            error => {
                                this.isSubmitting = false;
                                this.errorMessage = this.getAPIErrorMessage(
                                    error.response
                                );
                            }
                        );
                }
            });
        },
        /**
         * Cancel Method
         */
        onCancel() {
            this.show_old_password = false;
            this.show_new_password = false;
            this.show_new_confirmation_password = false;
            this.onModalClear("changePasswordStore", "clearStore");
        }
    }
};
