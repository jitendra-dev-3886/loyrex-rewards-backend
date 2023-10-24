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
                "category": [{key: 'required', value: 'Category name required'}],
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
            model: state => state.categoryStore.model,
            parentCategoryList: state => state.categoryStore.parentCategoryList,
            isEditMode: state => state.categoryStore.editId > 0
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
                    if (this.$store.state.categoryStore.editId > 0) {
                        apiName = "edit";
                        editId = this.$store.state.categoryStore.editId;
                        msgType=this.$getConst('UPDATE_ACTION');
                        delete this.model.parent_category;
                        if(this.model.parent_id == '0') {
                            this.model.parent_id = '';
                        }
                    }
                    if(this.model.parent_id == undefined) {
                        this.model.parent_id = '';
                    }
                    this.$store.dispatch('categoryStore/'+apiName, {model: this.model, editId: editId}).then(response => {
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
            this.onModalClear('categoryStore', 'clearStore');
        },
    },
    mounted() {
        // clear errorMessage
        this.errorMessage = '';
    }
}
