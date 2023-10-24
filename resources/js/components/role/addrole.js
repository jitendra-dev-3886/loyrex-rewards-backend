import CommonServices from '../../common_services/common.js';
import ErrorBlock from "../../partials/ErrorBlock.vue"
import ErrorBlockServer from "../../partials/ErrorBlockServer.vue"
import {mapState} from "vuex";

export default {
    data() {
        return {
            errorMessage: '',
            validationMessages: {
                "role": [{key: 'required', value: 'Role required'}]
            },
            loading: false
        }
    },
    components: {
        CommonServices,
        ErrorBlock,
        ErrorBlockServer,
    },
    props: ['value'],
    mixins: [CommonServices],
    computed: {
        ...mapState({
            model: state => state.roleStore.model,
            isEditMode: state => state.roleStore.editId > 0
        }),
    },
    methods: {
        /*
        * Submit action of form*/
        addAction() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    // loader enable
                    this.loading = true;
                    // apiName is method to call the from the store
                    var apiName = "add";
                    var editId = '';
                    var msgType=this.$getConst('CREATE_ACTION');
                    if (this.$store.state.roleStore.editId > 0) {
                        apiName = "edit";
                        editId = this.$store.state.roleStore.editId;
                        msgType=this.$getConst('UPDATE_ACTION');
                    }
                    let sendData = {
                        name: this.model.name,
                    };
                    this.$store.dispatch('roleStore/'+apiName, {model: sendData, editId: editId}).then(response => {
                        if (response.error) {
                            // loader disable if any error and display the error
                            this.loading =false;
                            this.errorMessage = response.error;
                        } else {
                            // if no error this code wiil execute
                            this.$store.commit("snackbarStore/setMsg",msgType);
                            this.onCancel();
                            this.$parent.getData();
                            this.loading =false;
                        }
                    }, error => {
                        // loader disable if any error and display the error
                        this.loading =false;
                        this.errorMessage = this.getAPIErrorMessage(error.response);
                    });

                }
            })
        },
        onCancel() {
            // clear model
            this.onModalClear('roleStore', 'clearStore');
        },
    },
    mounted() {
        // clear errorMessage
        this.errorMessage = '';
    }
}
