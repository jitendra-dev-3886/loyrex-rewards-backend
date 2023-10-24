<template>
    <v-dialog :value="value" content-class="modal-lg modal-dialog" scrollable @click:outside="onCancel()"
              @keydown.esc="onCancel()">
        <v-card>
            <v-card-title
                class="headline black-bg mb-4" primary-title>
                <span>View User Group</span>
                <v-spacer></v-spacer>
            </v-card-title>

            <v-card-text>
                <table class="table table-striped mx-0 px-0">
                    <tbody>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm"> Group Id</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.id }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Group Name:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.name }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm">No of Users</td>
                        <td class="font-weight-regular font-size-h6-sm">{{ model.no_of_users }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm">Catalogues</td>
                        <td class="font-weight-regular font-size-h6-sm">
                            <ul>
                                <li v-for="cat in model.catalogue_array">{{cat.name}} </li>
                            </ul> </td>
                    </tr>


                    <!--
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
                                        </tr>-->
                    </tbody>
                </table>
                <!--begin::Action-->
                <div class="mt-4">
                    <v-btn @click="onCancel()"
                           class="btn btn-grey">
                        {{ $getConst('BTN_CLOSE') }}
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
import CommonServices from '../../common_services/common.js';
import {mapState} from 'vuex';

export default {
    name: "GroupViewModal",
    components: {},
    props: ['value'],
    data() {
        return {};
    },
    computed: {
        ...mapState({
            model: state => state.userGroupStore.userGroupmodel,
        }),
    },
    mixins: [CommonServices],
    methods: {
        onCancel() {
            this.$store.commit('userGroupStore/clearGroupModel');
            this.errorMessage = '';
            this.$emit('input'); //Close Pop-up
        }
    },
};
</script>
