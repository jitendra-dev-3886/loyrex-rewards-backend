<template>
    <v-dialog :value="value" content-class="modal-lg modal-dialog" scrollable @click:outside="onCancel()"
              @keydown.esc="onCancel()">
        <v-card>
            <v-card-title
                class="headline black-bg mb-4" primary-title>
                <span> Contact Detail</span>
                <v-spacer></v-spacer>
            </v-card-title>

            <v-card-text>
                <table class="table table-striped mx-0 px-0">
                    <tbody>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%"> First Name:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.first_name }}</td>
                    </tr>

                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%"> Last Name:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.last_name }}</td>
                    </tr>

                    <tr>
                        <td class="font-weight-medium font-size-h6-sm"> Email:</td>
                        <td class="font-weight-regular font-size-h6-sm">{{ model.email }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm"> Subject:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.subject }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm"> Message:</td>
                        <td class="font-weight-regular font-size-h6-sm">{{ model.message }}</td>
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
    name: "ContactUsViewModal",
    components: {},
    props: ['value'],
    data() {
        return {};
    },
    computed: {
        ...mapState({
            model: state => state.contactStore.model,
        }),
    },
    mixins: [CommonServices],
    methods: {
        onCancel() {
            this.$store.commit('contactStore/clearModel');
            this.errorMessage = '';
            this.$emit('input'); //Close Pop-up
        }
    },
};
</script>
