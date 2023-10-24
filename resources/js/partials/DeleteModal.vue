<template>
    <v-dialog :value="value" @input="$emit('input')" content-class="modal-dialog">
        <v-card>
            <v-card-title
                class="headline black-bg"
                primary-title
            >
                {{confirmation.title ? confirmation.title : this.$getConst('DELETE_TITLE')}}
            </v-card-title>

            <v-card-text>
                <form method="POST" name="" role="form">
                    <ErrorBlockServer :errorMessage="errorMessage"></ErrorBlockServer>
                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex xs12>
                            <p style="font-size: 15px;">{{confirmation.description ? confirmation.description : this.$getConst('WARNING')}}</p>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex xs12>
                            <v-btn class="btn btn-black m-b-10 m-t-10" :loading="isLoading" @click.native="deleteAction">{{confirmation.btnConfirmationText ? confirmation.btnConfirmationText : this.$getConst('BTN_DELETE')}}
                            </v-btn>
                            <v-btn class="btn btn-grey m-b-10 m-t-10 ml-3" @click.native="onCancel">
                                {{confirmation.btnCancelText ? confirmation.btnCancelText : this.$getConst('BTN_CANCEL')}}
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </form>

            </v-card-text>

        </v-card>


    </v-dialog>
</template>

<script>
    import CommonServices from '../common_services/common.js';
    import ErrorBlock from "../partials/ErrorBlock.vue"
    import ErrorBlockServer from "../partials/ErrorBlockServer.vue"

    export default {
        data() {
            return {
                errorMessage: '',
                mainOrigin: this.mainorigin,
                isLoading: false,
            }
        },
        components: {
            CommonServices,
            ErrorBlock,
            ErrorBlockServer,
        },
        props: {
            value: Boolean,
            paramProps: {
                type: Object,
                default() {
                    return {
                        idProps: '',
                        storeProps: '',
                        delete_allowed: true,
                    }
                }
            },
            confirmation: {
                type: Object,
                default() {
                    return {
                        title: this.$getConst('DELETE_TITLE'),
                        description: this.$getConst('WARNING'),
                        btnConfirmationText: this.$getConst('BTN_DELETE'),
                        btnCancelText: this.$getConst('BTN_CANCEL'),
                    };
                },
            },
            apiEndPoint: {
                type: [String],
                default() {
                    return '/delete';
                }
            },
        },
        mixins: [CommonServices],
        methods: {
            deleteAction() {



                /*this.isLoading = true;*/
                if(this.paramProps.delete_allowed){
                this.$store.dispatch(this.paramProps.storeProps + this.apiEndPoint, this.paramProps.idProps).then(response => {
                    this.isLoading = false;
                    if (response.error) {
                        this.errorMessage = response.error;
                    } else {
                        this.$store.commit("snackbarStore/setMsg",this.$getConst('DELETE_ACTION'));
                        this.$emit('delete-success');
                        this.onCancel();
                        this.$parent.getData()
                    }
                }, error => {
                    this.isLoading = false;
                    this.errorMessage = this.getAPIErrorMessage(error.response);
                });}
                else{
                    this.errorMessage = this.$getConst('DELETE_NOT_ALLOWED');
                }

            },
            onCancel() {
                this.$emit('input');
                this.errorMessage = '';
            }
        },
        mounted() {
            this.errorMessage = '';
        }
    }
</script>
