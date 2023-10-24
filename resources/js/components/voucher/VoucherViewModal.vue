<template>
    <v-dialog
        :value="value"
        content-class="modal-lg modal-dialog"
        scrollable
        @keydown.esc="onCancel()"
    >
        <v-card>
            <v-card-title class="headline black-bg mb-4" primary-title>
                <span>View Voucher</span>
                <v-spacer></v-spacer>
            </v-card-title>

            <v-card-text>
                <table class="table table-striped mx-0 px-0">
                    <tbody>
                        <tr>
                            <td
                                class="font-weight-medium font-size-h6-sm"
                                style="width: 30%"
                            >
                                Voucher name:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Validity:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{
                                    model.valid_till != ""
                                        ? "Valid till " +
                                          computedDateFormattedMomentjs(
                                              model.valid_till
                                          )
                                        : "N/A"
                                }}
                            </td>
                        </tr>
                        <tr v-if="model.voucher_type == '0'">
                            <td class="font-weight-medium font-size-h6-sm">
                                Product category:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.category ? model.category.name : "" }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                {{
                                    model.voucher_type == "1"
                                        ? "Points"
                                        : "Products"
                                }}:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                <template v-if="model.voucher_type == '1'">
                                    <template v-if="model.points != ''">{{
                                        model.points | formatNumber
                                    }}</template>
                                    <template v-else>0</template>
                                </template>
                                <template v-else>
                                    <template
                                        v-for="(product,
                                        index) in model.product"
                                    >
                                        <template v-if="index != '0'"
                                            >,
                                        </template>
                                        <template>{{ product.name }}</template>
                                    </template>
                                </template>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Assigned user:
                            </td>
                            <td
                                class="font-weight-regular font-size-h6-sm"
                                v-if="
                                    model.user_id &&
                                        model.user_id != '0' &&
                                        model.user_id != null &&
                                        model.user != null
                                "
                            >
                                {{ model.user | getFullName }}
                            </td>
                            <td
                                class="font-weight-regular font-size-h6-sm"
                                v-else
                            >
                                None
                            </td>
                        </tr>
                        <tr
                            v-if="
                                model.user_id &&
                                    model.user_id == '0' &&
                                    model.user_id != null
                            "
                        >
                            <td class="font-weight-medium font-size-h6-sm">
                                No of voucher:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.no_of_vouchers }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Voucher status:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.status_text }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Description:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.description }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--begin::Action-->
                <div class="mt-4">
                    <v-btn @click="onCancel()" class="btn btn-grey">
                        {{ $getConst("BTN_CLOSE") }}
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
import CommonServices from "../../common_services/common.js";
import { mapState } from "vuex";

export default {
    name: "VoucherViewModal",
    components: {},
    props: ["value"],
    data() {
        return {};
    },
    computed: {
        ...mapState({
            model: state => state.voucherStore.model
        })
    },
    mixins: [CommonServices],
    methods: {
        onCancel() {
            this.$store.commit("voucherStore/clearModel");
            this.errorMessage = "";
            this.$emit("input"); //Close Pop-up
        }
    }
};
</script>
