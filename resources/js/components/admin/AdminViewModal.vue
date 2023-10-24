<template>
    <v-dialog :value="value" content-class="modal-lg modal-dialog" scrollable @click:outside="onCancel()"
              @keydown.esc="onCancel()">
        <v-card>
            <v-card-title
                class="headline black-bg mb-4" primary-title>
                <span> Admin Detail</span>
                <v-spacer></v-spacer>
            </v-card-title>

            <v-card-text>
                <table class="table table-striped mx-0 px-0">
                    <tbody>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%"> Name:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model|getFullName }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm">Email:</td>
                        <td class="font-weight-regular font-size-h6-sm">{{ model.email }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm"> Contact no.:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.contact_number }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm">Role:</td>
                        <td class="font-weight-regular font-size-h6-sm">{{ model.role.name }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm">Status:</td>
                        <td class="font-weight-regular font-size-h6-sm">{{ model.status_text }}</td>
                    </tr>
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
        name: "AdminViewModal",
        components: {},
        props: ['value'],
        data() {
            return {};
        },
        computed: {
            ...mapState({
                model: state => state.adminStore.model,
            }),
        },
        mixins: [CommonServices],
        methods: {
            onCancel() {
                this.$store.commit('adminStore/clearModel');
                this.errorMessage = '';
                this.$emit('input'); //Close Pop-up
            }
        },
    };
</script>
