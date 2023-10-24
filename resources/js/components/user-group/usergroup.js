import CustomTable from '../../components/customtable/table'
import DeleteModal from "../../partials/DeleteModal";
import ExportBtn from "../../partials/ExportBtn";
import GroupModal from "./GroupModal.vue";
import {mapState} from "vuex";
import CommonServices from '../../common_services/common.js';
import ErrorModal from "../../partials/ErrorModal";
import MultiDelete from "../../partials/MultiDelete";
import DragAndDropFile from "../../partials/DragAndDropFile";
import GroupViewModal from "./GroupViewModal";
import {ADD_BODY_CLASSNAME, REMOVE_BODY_CLASSNAME} from "../../store/htmlclass.module";

export default CustomTable.extend({
    name: "UserGroup",
    data: function () {
        var self = this;
        return {
            tab: 'tab1',
            files: [],
            modalOpen: false,
            isImportLoaded: false,
            urlApi: 'userGroupStore/getAllGroups',// set store name here to set/get pagination data and for access of actions/mutation via custom table
            headers: [
                { text: 'Group ID', value: 'id', width: '20%'},
                { text: 'Group Name', value: 'name', width: '25%'},
                { text: 'No of Users', value: 'no_of_users',sortable: false, width: '15%'},
                {text: 'Catalogues',value: 'catalogue_array',sortable: false, width: '20%'},
                { text: 'Actions', value: 'actions', sortable: false, width: '10%' },
            ],
            confirmation: {
                title: '',
                description: '',
                btnCancelText: self.$getConst('BTN_CANCEL'),
                btnConfirmationText: self.$getConst('BTN_DELETE'),
            },
            deleteProps:{
                ids: '',
                store: '',
            },
            importProps:{
                store: 'userGroupStore',
                modelName: 'usergroup',
                multiple: false,
            },
            exportProps:{
                id: '',
                store: '',
                fileName: '',
                pagination: '',
            },
            paramProps: {
                delete_allowed: true,
                idProps: '',
                storeProps: '',
            },
            userDialogue: false,
            errorArr: [],
            errorDialog: false,
            role_id:'',
            filterMenu: false,
            images: [],
            UsergroupViewModal: false,
            isGroupLoading: false,
            userGroupsId: '',
        }
    },
    mixins: [CommonServices],
    components: {
        DeleteModal,
        GroupModal,
        ErrorModal,
        ExportBtn,
        MultiDelete,
        DragAndDropFile,
        GroupViewModal,
    },
    computed: {
        ...mapState({
            pagination : state => state.userGroupStore.pagination,
            userGroupList: state => state.userGroupStore.userGroupList,
            userGroupmodel: state => state.userGroupStore.userGroupmodel,
            /*CatalogueList: state => state.catalogueStore.list*/
        }),
    },
    watch: {
    },
    /*created(){
        this.getUserGroup()
          },*/

    methods:{
        /**
         *
         */
        setExport(){
            let rowIds = [];
            this.selected.forEach((element, index) => {
                rowIds[index] = element.id;
            });

            this.exportProps.ids = rowIds;
            this.exportProps.store = 'userGroupStore';
            this.exportProps.fileName = 'User-Group';
            this.exportProps.pagination = JSON.parse(JSON.stringify(this.pagination));
            this.$refs.exportbtn.exportToCSV();
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
        handleClick(row) {
            if(row.no_of_users > 0) {

                    this.$router.push({name: 'users', params:{ userGroupsId: row.id}});

            }
        },


/*
        getUserGroup(){
            this.isGroupLoading= true;
            this.$store.dispatch('userGroupStore/getUserGroupList', {id: this.userGroupmodel.id }).then(response => {
                this.isGroupLoading = false;
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {
                    // Reset category list
                    this.$store.commit("userGroupStore/setUserGroupList", []);
                    //set sub category list
                    this.$store.commit("userGroupStore/setUserGroupList", JSON.parse(JSON.stringify(response.data.data)));
                }
            }, function (error) {
                this.isGroupLoading = false;
                this.errorMessage = this.getAPIErrorMessage(error.response);
            });

        },*/
        /**
         * delete user
         * @param id
         */
        deleteItem (row) {

            this.paramProps.idProps = row.id;
            this.paramProps.storeProps = 'userGroupStore';

                if(row.no_of_users != '0'){
                    this.paramProps.delete_allowed = false
                }else{
                    this.paramProps.delete_allowed = true
                }
            this.confirmation.title = this.$getConst('DELETE_TITLE');
            this.confirmation.description = this.$getConst('WARNING');
            this.modalOpen = true;
        },
        /**
         * Multiple Delete
         */
        multipleDelete(){
            let rowIds = [];
            this.selected.forEach((element, index) => {
                rowIds[index] = element.id;
            });

            this.deleteProps.ids = rowIds;
            this.deleteProps.store = 'userGroupStore';
            this.$refs.multipleDeleteBtn.deleteMulti();
        },
        /*
        * Add User Modal method
        * */
        addUserGroup(){

           /* this.getCatalogue()*/


            this.getCatalogue()
            this.$refs.GroupModal.$validator.reset();
            this.userDialogue = true;
            /*this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store.dispatch("userGroupStore/getAllGroups").then((response) => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {
                    this.$store.commit('userGroupStore/setUserGroupList', response.data.data);
                    this.userDialogue = true;
                }
            }, error => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                this.errorArr = this.getModalAPIerrorMessage(error);
                this.errorDialog = true;
            });*/
        },
        onEditView(id, isEdit) {
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store.commit('userGroupStore/setGroupEditId', id);
            this.$store.dispatch('userGroupStore/getGroupById', id).then(response => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {
                    if(isEdit) {
                           if (this.$refs.GroupModal){
                               this.getCatalogue();
                           }


                        // Open edit user modal


                        this.userDialogue = true;

                    }

                    else {
                        this.UsergroupViewModal = true;
                    }
                }
            }, error => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                this.errorArr = this.getModalAPIerrorMessage(error);
                this.errorDialog = true;
            });
        },
        refreshData(){
            var self = this;
            setTimeout(function () {
                if(self.tab == 'tab1') {
                    self.refresh();
                } else if(self.tab == 'tab2' && self.$refs.importdata) {
                    if(this.isImportLoaded) {
                        self.$refs.importdata.refreshImport();
                    }
                    this.isImportLoaded = true;
                }
            }, 100);
        },

        /* call this method when open add modal on same page */
        // openUserModalOnSamePage() {
        //     this.addUser();
        // }
    },

    mounted(){
        this.onModalClear('userGroupStore','clearGroupModel');
        if(this.$route.params.willModalOpen){
            setTimeout(() => {
                // this.addUser();
            }, 100);
        }
    },


    beforeDestroy() {
        this.$store.commit('userGroupStore/clearGroupModel');
    }
});
