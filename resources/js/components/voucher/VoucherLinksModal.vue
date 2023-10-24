<template>
    <v-dialog
        :value="value"
        content-class="modal-sm modal-dialog"
        scrollable
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
    >
        <v-card class="mx-auto">
            <v-card-text class="mt-4 mb-4">
                <div class="text-center p-5">
                    <img
                        :src="$getConst('LOYREX_LOGO')"
                        height="120"
                        class="img-responsive mb-6 mt-4"
                        alt="Logo"
                    />
                    <h6 class="mt-2">
                        {{
                            $getConst("VOUCHER_LINKS_DOWNLOAD_1ST_LINE_START") +
                                voucherDetail.no_of_vouchers +
                                $getConst("VOUCHER_LINKS_DOWNLOAD_1ST_LINE_END")
                        }}
                    </h6>
                    <h6 class="mb-2">
                        {{ $getConst("VOUCHER_LINKS_DOWNLOAD_2ND_LINE") }}
                    </h6>
                </div>
                <div class="flex-center mb-2">
                    <v-btn
                        @click="downloadLinks"
                        :loading="isDownloading"
                        class="btn btn-primary font-weight-bold font-size-3 w100 mt-2"
                    >
                        {{ $getConst("CLICK_DOWNLOAD_BTN") }}
                    </v-btn>
                </div>
                <div class="flex-center">
                    <v-btn
                        @click="onCancel()"
                        class="btn btn-grey font-weight-bold font-size-3 w100 mt-2"
                    >
                        {{ $getConst("BTN_SKIP") }}
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
        <error-modal v-model="errorDialog" :errorArr="errorArr"></error-modal>
    </v-dialog>
</template>
<script>
import CommonServices from "../../common_services/common.js";
import { mapState } from "vuex";
import ErrorModal from "../../partials/ErrorModal";

export default {
    name: "VoucherViewModal",
    components: { ErrorModal },
    props: ["value", "voucherDetail"],
    data() {
        return {
            errorArr: [],
            errorDialog: false,
            isDownloading: false
        };
    },
    computed: {
        ...mapState({
            pagination: state => state.voucherStore.pagination
        })
    },
    mixins: [CommonServices],
    methods: {
        onCancel() {
            this.$emit("input"); //Close Pop-up
        },
        downloadLinks() {
            let pagination = JSON.parse(JSON.stringify(this.pagination));
            // pagination.filter = this.convetFiltertoBase64({
            //     reference_voucher_no: [this.voucherDetail.reference_voucher_no]
            // });
            pagination.filter =
                this.voucherDetail.reference_voucher_no != "" &&
                this.voucherDetail.reference_voucher_no != undefined
                    ? this.convetFiltertoBase64({
                          reference_voucher_no: [
                              this.voucherDetail.reference_voucher_no
                          ]
                      })
                    : "";
            pagination.limit = "1000";
            this.isDownloading = true;
            this.$store.dispatch("voucherStore/export", pagination).then(
                response => {
                    this.isDownloading = false;
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        this.convertToCSV("Voucher", response.data);
                    }
                },
                error => {
                    this.isDownloading = false;
                    this.errorArr = this.getAPIErrorMessage(error.response);
                    this.errorDialog = true;
                }
            );
        }
    }
};
</script>
