<template>
    <span>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn color="error" class="mr-2" v-on="on"><v-icon small>{{ icons.mdiDelete }}</v-icon> </v-btn>
            </template>
            <span>{{$getConst('DELETE_TOOLTIP')}}</span>
        </v-tooltip>
        <error-modal :errorArr="errorArr" v-model="errorDialog"></error-modal>

    <v-dialog :value="deleteModal" @input="onCancel" content-class="modal-dialog">
        <v-card>
            <v-card-title
                class="headline black-bg"
                primary-title>{{this.$getConst('DELETE_TITLE')}}
            </v-card-title>

            <v-card-text>
                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex xs12>
                            <p>{{ $getConst('MULTIPLE_DELETE_WARNING_START') + deleteProps.ids.length + $getConst('MULTIPLE_DELETE_WARNING_END')}}</p>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex xs12>
                            <v-btn class="btn btn-black m-b-10 m-t-10" :loading="isDeleting" @click.native="deleteAction">{{this.$getConst('BTN_DELETE')}}</v-btn>
                            <v-btn class="btn btn-grey m-b-10 m-t-10 ml-3" @click.native="onCancel">{{this.$getConst('BTN_CANCEL')}}</v-btn>
                        </v-flex>
                    </v-layout>
            </v-card-text>
        </v-card>

    </v-dialog>
        </span>
</template>

<script>
    import CommonServices from '../common_services/common.js';
    import ErrorModal from '../partials/ErrorModal';

    export default {
        data() {
            return {
                errorArr: [],
                errorDialog: false,
                deleteModal: false,
                isDeleting: false,
            }
        },
        components: {
            CommonServices,
            ErrorModal
        },
        props: ['value', 'deleteProps'],
        mixins: [CommonServices],
        methods: {
            deleteMulti() {
                this.deleteModal = true;
            },
            deleteAction() {
                this.isDeleting = true;
                this.$store.dispatch(this.deleteProps.store+'/multiDelete', { id: this.deleteProps.ids}).then(response => {
                    this.isDeleting = false;
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        // if no error this code wiil execute
                        this.$store.commit("snackbarStore/setMsg",this.$getConst('DELETE_ACTION'));
                        this.$emit('multiDelete');
                        this.loading =false;
                        this.onCancel();
                    }
                }, error => {
                    this.isDeleting = false;
                    this.errorArr = this.getAPIErrorMessage(error.response);
                    this.errorDialog = true;
                });
            },
            onCancel(){
                this.deleteModal = false;
                this.$emit('input');
            }
        },
        mounted() {
            this.errorMessage = '';
        }
    }
</script>
