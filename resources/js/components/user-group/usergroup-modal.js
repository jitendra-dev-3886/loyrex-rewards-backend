import CommonServices from '../../common_services/common.js';
import ErrorBlockServer from "../../partials/ErrorBlockServer";
import ErrorModal from "../../partials/ErrorModal";
import {mapState, mapActions} from 'vuex';

export default {
    name: "GroupModal",
    components: {
        ErrorBlockServer,
        ErrorModal
    },
    props: ['value'],
    data() {
        return {
            errorArr: [],
            errorDialog: false,
            errorMessage: '',
            validationMessages: {
                "name": [{key: 'required', value: 'Group name required'}],
                "CatalogueList": [{key: 'required', value: 'Catalogue required'}],
                /* "category_id": [{key: 'required', value: 'Product category required'}],*/
            },
            isSubmitting: false,
            show_password: false,

        };
    },
    computed: {
        ...mapState({
            isEditMode: state => state.userGroupStore.editId > 0,
            userGroupList: state => state.userGroupStore.userGroupList,
            userGroupmodel: state => state.userGroupStore.userGroupmodel,
            CatalogueList: state => state.catalogueStore.list,
        }),

        icon () {
            if (this.selectedAllCatalogue) {
                return 'check_box'
            }
            if (this.someSelectedCategory) {
                return 'indeterminate_check_box'
            }
            return 'check_box_outline_blank'
        },
        selectedAllCatalogue () {
            return (this.userGroupmodel.catalogue_id) && (this.userGroupmodel.catalogue_id.length == this.CatalogueList.length)
        },
        someSelectedCategory () {
            return (this.userGroupmodel.catalogue_id) && (this.userGroupmodel.catalogue_id.length > 0 && !this.selectedAllCatalogue)
        },
    },
    mixins: [CommonServices],
    methods: {

        onSubmit() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    // loader enable
                    this.loading = true;
                    // apiName is method to call the from the store
                    var apiName = "add";
                    var editId = '';
                    var msgType=this.$getConst('CREATE_ACTION');
                    if (this.$store.state.userGroupStore.editId > 0) {
                        apiName = "edit";
                        editId = this.$store.state.userGroupStore.editId;
                        msgType = this.$getConst('UPDATE_ACTION');
                    }
                       /* delete this.userGroupmodel.parent_category;
                        if(this.userGroupmodel.id == '0') {
                            this.userGroupmodel.id = '';
                        }
                    }
                    if(this.userGroupmodel.id == undefined) {
                        this.userGroupmodel.id = '';
                    }*/
                    this.$store.dispatch('userGroupStore/'+apiName, {userGroupmodel: this.userGroupmodel, editId: editId}).then(response => {
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
        getCatalogue(){

            this.$store.dispatch('catalogueStore/getAll', {page:1, limit:5000, is_light: 'true'}).then((response)=>{
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {


                }
            }, error => {
                this.errorArr = this.getAPIErrorMessage(error.response);
                this.errorDialog = true;
            });
        },


        toggle_catalogue(){

            this.$nextTick(() => {
                if (this.selectedAllCatalogue) {
                    this.userGroupmodel.catalogue_id = [];
                } else {
                    this.userGroupmodel.catalogue_id  = [];
                    this.CatalogueList.map(item => {
                        this.userGroupmodel.catalogue_id.push(item.id);
                    });
                }

            })
        },
        onCancel(){

            // this.$parent.onModalClear('userStore', 'clearStore');
            this.CatalogueList = [];
            this.onModalClear('userGroupStore','clearGroupModel');
            this.errorMessage = '';
            this.isSubmitting = false;
            this.$validator.reset();
            this.$emit('input'); //Close Pop-up
        },
        /**
         * Selecting and unselecting all the caltegory items
         */
           },
    created() {
        /*this.getAllCatalogues();*/

    },
};
