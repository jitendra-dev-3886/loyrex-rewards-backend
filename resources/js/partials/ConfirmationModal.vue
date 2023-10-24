<template>
    <v-dialog :value="value" content-class="modal-dialog">
        <v-card>
            <v-card-title
                class="headline black-bg"
                primary-title>
                {{confirmation.title}}
            </v-card-title>

            <v-card-text>
                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex xs12>
                            <span v-html="confirmation.description"></span>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex xs12>
                            <v-btn class="btn btn-black m-b-10 m-t-10" @click.native="$emit('input')">
                                {{confirmation.btnConfirmationText}}
                            </v-btn>
                            <v-btn color="success" class="btn btn-black m-b-10 m-t-10" @click=""> {{confirmation.btnCancelText}}
                            </v-btn>
                        </v-flex>
                    </v-layout>
            </v-card-text>

        </v-card>


    </v-dialog>
</template>

<script>
    export default {
        name: "ConfirmationModal",
        props: ['value', 'confirmation'],
    }
</script>
