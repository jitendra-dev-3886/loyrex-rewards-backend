<template>
    <v-dialog
        :value="value"
        content-class="modal-lg modal-dialog"
        scrollable
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
    >
        <v-card>
            <v-card-title class="headline black-bg mb-4" primary-title>
                <span>Send Voucher Link</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="onCancel">
                    <v-icon>{{ icons.mdiClose }}</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text>
                <ErrorBlockServer
                    :errorMessage="errorMessage"
                ></ErrorBlockServer>
                <v-text-field
                    class="mt-4"
                    label="User's Email ID"
                    v-model="voucherEmail"
                    :error-messages="getErrorValue('email')"
                    name="email"
                    type="email"
                    maxlength="191"
                    v-validate="'required|email'"
                ></v-text-field>
                <!--begin::Action-->
                <div class="mt-4">
                    <v-btn
                        @click="onSendEmail()"
                        class="btn btn-grey"
                        :loading="isLoading"
                    >
                        {{ $getConst("BTN_SEND_MAIL") }}
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
import CommonServices from "../../common_services/common.js";
import { mapState } from "vuex";
import ErrorBlockServer from "../../partials/ErrorBlockServer";
export default {
    name: "VoucherViewModal",
    components: { ErrorBlockServer },
    props: ["value"],
    data() {
        return {
            errorMessage: "",
            voucherEmail: "",
            isLoading: false,
            validationMessages: {
                email: [
                    { key: "email", value: "Valid email required" },
                    { key: "required", value: "Email ID required" }
                ]
            }
        };
    },
    computed: {
        ...mapState({
            model: state => state.voucherStore.sendVoucherModel
        })
    },
    mixins: [CommonServices],
    created() {
        this.voucherEmail = this.model.user.email;
    },
    methods: {
        onCancel() {
            this.$store.commit("voucherStore/clearVoucherModel");
            this.voucherEmail = "";
            this.errorMessage = "";
            this.$emit("input"); //Close Pop-up
        },

        onSendEmail() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.isLoading = true;
                    this.$store
                        .dispatch("voucherStore/sendVoucherEmail", {
                            voucher_id: this.model.id,
                            email: this.voucherEmail
                        })
                        .then(
                            response => {
                                if (response) {
                                    this.isLoading = false;
                                    this.$store.commit(
                                        "snackbarStore/setMsg",
                                        response.data.message
                                    );
                                    this.$emit("input");
                                }
                            },
                            error => {
                                this.isLoading = false;
                                this.errorMessage = this.getAPIErrorMessage(
                                    error.response
                                );
                            }
                        );
                }
            });
        }
    }
};
</script>
