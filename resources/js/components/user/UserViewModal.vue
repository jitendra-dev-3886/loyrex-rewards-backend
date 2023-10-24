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
                <span>View User</span>
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
                                Name:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model | getFullName }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Job title:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.job_title }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Contact no.:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.contact_number }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Email:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.email }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                User Groups:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                <ul>
                                    <li v-for="group in model.userGroup_array">
                                        {{ group.name }}
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">
                                Reward points:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                {{ model.reward_points | formatNumber }}
                            </td>
                        </tr>
                        <!--  <tr>
                            <td class="font-weight-medium font-size-h6-sm">Product categories:</td>
                            <td class="font-weight-regular font-size-h6-sm">
                                <template v-for="(category,index) in model.categories_array">
                                    <span v-if="index != '0'">, </span>
                                    <span>{{category.name}}</span>
                                </template>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm">Mapped products:</td>
                            <td class="font-weight-regular font-size-h6-sm" v-if="model.products_array && model.products_array.length > 0">
                                <template v-for="(product,index) in model.products_array">
                                    <span v-if="index != '0'">, </span>
                                    <span>{{product.name}} </span>
                                </template>
                            </td>
                            <td class="font-weight-regular font-size-h6-sm" v-else>
                                <span>All products</span>
                            </td>
                        </tr>
 -->
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
    name: "UserViewModal",
    components: {},
    props: ["value"],
    data() {
        return {};
    },
    computed: {
        ...mapState({
            model: state => state.userStore.model
        })
    },
    mixins: [CommonServices],
    methods: {
        onCancel() {
            this.$store.commit("userStore/clearModel");
            this.errorMessage = "";
            this.$emit("input"); //Close Pop-up
        }
    }
};
</script>
