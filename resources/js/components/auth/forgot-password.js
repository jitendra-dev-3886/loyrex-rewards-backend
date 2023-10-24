import {mapGetters, mapState} from 'vuex';
import CommonServices from "../../common_services/common";
import ErrorBlockServer from "../../partials/ErrorBlockServer.vue"
export default {
    name: "ForgotPassword",
    components: {
        ErrorBlockServer,
    },
    props: ['value'],
    computed: { },
    mixins: [CommonServices],
    data: function () {
        return {
            email: '',
            errorMessage: '',
            validationMessages: {
                "email": [{key: 'email', value: 'Valid email required'}, {key: 'required', value: 'Authorised email ID required'}],
            },
            isSubmittingForgotPassword: false,
        }
    },
    methods: {
        /**
         * Cancel Method
         */
        onCancel(){
            this.email = '';
            this.errorMessage= '';
            this.isSubmittingForgotPassword = false;
            this.onModalClear('forgotPasswordStore','clearStore');
        },

        /**
         * Submit of Forgot Password Modal
         */
        onSubmit() {
            this.$validator.validateAll('forgotPassword').then(valid => {
                if (valid) {
                    this.isSubmittingForgotPassword = true;
                    this.$store.dispatch("forgotPasswordStore/sendForgotPasswordEmail",
                        {
                            email: this.email,
                        }).then(response => {
                        if (response.error) {
                            this.isSubmittingForgotPassword = false;
                            this.errorMessage = response.data.error;
                        } else {
                            this.$store.commit("snackbarStore/setMsg", this.$getConst('EMAIL_SEND_MESSAGE'));
                            this.onCancel();
                        }
                    }, error => {
                        this.isSubmittingForgotPassword = false;
                        this.errorMessage = this.getAPIErrorMessage(error.response);
                    });

                }
            });
        }
    }
}
