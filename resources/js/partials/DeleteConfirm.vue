<template>
    <v-dialog :value="value" @click:outside="$emit('input')" @keydown.esc="$emit('input')" content-class="modal-dialog">
        <v-card>
            <v-card-title
                class="headline black-bg"
                primary-title>
                Delete Confirmation
            </v-card-title>

            <v-card-text>
                <form method="POST" name="" role="form">
                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex xs12>
                            <p>Are you sure you want to delete?</p>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex xs12>
                            <v-btn class="btn btn-black m-b-10 m-t-10" @click="deleteAction">Delete
                            </v-btn>
                            <v-btn class="btn btn-grey m-b-10 m-t-10 ml-3" @click.native="$emit('input')">
                                {{ $getConst('BTN_CANCEL') }}
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </form>
            </v-card-text>

        </v-card>


    </v-dialog>
</template>

<script>
    export default {

        props: ['value', 'paramProps'],

        methods: {
            deleteAction() {
                this.$emit('delete',this.paramProps);
                this.$emit('input');
            }
        }
    }
</script>
