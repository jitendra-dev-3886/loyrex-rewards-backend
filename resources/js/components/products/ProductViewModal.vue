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
                <span> Product Detail</span>
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
                                Product Name:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.name ? model.name : "" }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="font-weight-medium font-size-h6-sm"
                                style="width: 30%"
                            >
                                Feature Image:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                <v-btn
                                    class="btn-small"
                                    @click="isViewImage = true"
                                    >View image</v-btn
                                >
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Category:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{
                                    model.category && model.category.name
                                        ? model.category.name
                                        : ""
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Sub category:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{
                                    model.subcategory && model.subcategory.name
                                        ? model.subcategory.name
                                        : ""
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Brand:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{
                                    model.brand && model.brand.name
                                        ? model.brand.name
                                        : ""
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Description :
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.description ? model.description : "" }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Points :
                            </td>
                            <td
                                class="font-weight-regular font-size-h6-sm"
                                v-if="model.point"
                            >
                                {{ model.point | formatNumber }}
                            </td>
                            <td
                                class="font-weight-regular font-size-h6-sm"
                                v-else
                            >
                                -
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Available Status :
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{
                                    model.available_status_text
                                        ? model.available_status_text
                                        : ""
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Catalogues:
                            </td>
                            <td
                                class="font-weight-regular font-size-h6-sm"
                                v-if="model.catalogue_array"
                            >
                                <template
                                    v-for="(catalogue,
                                    index) in model.catalogue_array"
                                >
                                    <template v-if="index != '0'">, </template>
                                    <template>{{
                                        catalogue.name ? catalogue.name : ""
                                    }}</template>
                                </template>
                            </td>
                            <td
                                class="font-weight-regular font-size-h6-sm"
                                v-else
                            >
                                -
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Available Status :
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.available_status_text }}
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
        <view-image-modal
            v-if="isViewImage"
            v-model="isViewImage"
        ></view-image-modal>
    </v-dialog>
</template>
<script>
import CommonServices from "../../common_services/common.js";
import { mapState } from "vuex";
import ViewImageModal from "./ViewImageModal.vue";
export default {
    name: "ProductViewModal",
    components: { ViewImageModal },
    props: ["value"],
    data() {
        return {
            isViewImage: false
        };
    },
    computed: {
        ...mapState({
            model: state => state.productStore.model
        })
    },
    mixins: [CommonServices],
    methods: {
        onCancel() {
            this.$store.commit("productStore/clearModel");
            this.errorMessage = "";
            this.$emit("input"); //Close Pop-up
        }
    }
};
</script>
