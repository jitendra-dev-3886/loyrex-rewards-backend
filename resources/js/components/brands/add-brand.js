import CommonServices from '../../common_services/common.js';
import ErrorModal from "../../partials/ErrorModal";
import ErrorBlockServer from "../../partials/ErrorBlockServer.vue"
import {mapState} from "vuex";

export default {
    data() {
        return {
            errorArr: [],
            errorDialog: false,
            errorMessage: '',
            validationMessages: {
                "brand": [{key: 'required', value: 'Brand name required'}],
            },
            loading: false
        }
    },
    components: {
        CommonServices,
        ErrorBlockServer,
        ErrorModal
    },
    props: ['value'],
    mixins: [CommonServices],
    computed: {
        ...mapState({
            model: state => state.brandStore.model,
            isEditMode: state => state.brandStore.editId > 0
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
                    if (this.$store.state.brandStore.editId > 0) {
                        apiName = "edit";
                        editId = this.$store.state.brandStore.editId;
                        msgType=this.$getConst('UPDATE_ACTION');
                    }
                    this.$store.dispatch('brandStore/'+apiName, {model: this.model, editId: editId}).then(response => {
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
            this.onModalClear('brandStore', 'clearStore');
        },
    },
    mounted() {
        // clear errorMessage
        this.errorMessage = '';
    }
}
