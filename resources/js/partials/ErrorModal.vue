<template>
    <v-dialog :value="value" content-class="modal-dialog">
        <v-card>
            <v-card-title
                class="headline black-bg"
                primary-title>
                Error
            </v-card-title>

            <v-card-text>
                <form method="POST" name="" role="form">

                    <v-layout row wrap class="display-block m-0 mb-5 mt-2">
                        <v-flex xs12 v-if="Array.isArray(errorArr)">
                            <span v-for="err in errorArr">{{err.name}} : {{err.message}}</span>
                        </v-flex>
                        <v-flex xs12 v-else>
                            <span v-html="errorArr"></span>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex xs12>
                            <v-btn class="btn btn-black m-b-10 m-t-10" @click.native="$emit('input')">
                                OK
                            </v-btn>
                            <!--<v-btn color="success" class="btn btn-black m-b-10 m-t-10" @click="deleteAction">Delete
                            </v-btn>-->
                        </v-flex>
                    </v-layout>
                </form>

            </v-card-text>

        </v-card>


    </v-dialog>
</template>

<script>
    export default {
        name: "ErrorModal",
        props: ['value', 'errorArr'],
    }
</script>
