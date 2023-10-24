<template>
    <div>
        <v-snackbar
            :value="snackbar1"
            :bottom="y === 'bottom'"
            :color="color"
            :left="x === 'left'"
            :multi-line="mode === 'multi-line'"
            :right="x === 'right'"
            :timeout="timeout"
            :top="y === 'top'"
            :vertical="mode === 'vertical'">
            {{ msg }}
        </v-snackbar>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        data() {
            return {
                color: 'primary',
                mode: '',
                snackbar1: this.snackbar,
                timeout: 6000,
                x: 'right',
                y: 'bottom',

            }
        },
        computed: {
            ...mapState({
                msg: state => state.snackbarStore.msg,
                snackbar: state => state.snackbarStore.snackbar,
            }),
        },
        watch: {
            snackbar(val) {
                var self = this;
                self.snackbar1 = val;
                //To reset the message and snackbar model to false once shown
                setTimeout(function () {
                    self.$store.commit('snackbarStore/clearStore')
                }, this.timeout)
            }
        },
        methods: {}
    }
</script>
