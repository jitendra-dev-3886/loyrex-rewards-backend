import CustomTable from '../../components/customtable/table'
import DeleteModal from "../../partials/DeleteModal";
import AddRole from "./AddRole.vue";
import ExportBtn from "../../partials/ExportBtn";
import MultiDelete from "../../partials/MultiDelete";
import ErrorModal from '../../partials/ErrorModal.vue';
import {mapState} from "vuex";
import CommonServices from '../../common_services/common.js';
// import Import from "../../partials/Import";
import {ADD_BODY_CLASSNAME, REMOVE_BODY_CLASSNAME} from "../../store/htmlclass.module";

export default CustomTable.extend({
    name: "Role",
    data: function () {
        var self = this;
        return {
            tab: 'tab1',
            files: [],
            modalOpen: false,
            addRoleModal: false,
            urlApi: 'roleStore/getAll',// set store name here to set/get pagination data and for access of actions/mutation via custom table
            headers: [
                { text: 'Role ID', value: 'id', width: '30%'},
                { text: 'Role Name', value: 'name', width: '60%'},
                { text: 'Actions', value: 'actions', sortable: false, width: '10%'},
            ],
            paramProps:{
                delete_allowed: true,
                idProps: '',
                storeProps: ''
            },
            exportProps:{
                id: '',
                store: '',
                fileName: '',
                pagination: '',
            },
            deleteProps:{
                ids: '',
                store: '',
            },
            confirmation:{
                title: '',
                description: '',
                btnCancelText: self.$getConst('BTN_CANCEL'),
                btnConfirmationText: self.$getConst('BTN_DELETE'),
            },
            role_id:'',
            errorArr: [],
            errorDialog: false,
        }
    },
    mixins: [CommonServices],
    components: {
        DeleteModal,
        AddRole,
        ExportBtn,
        MultiDelete,
        ErrorModal
        // Import
    },
    computed: {
        ...mapState({
            pagination : state => state.roleStore.pagination,
            setRoleList: state => state.roleStore.roledropdownlist,
        })



    },
    watch: {
    },
    created () {
    },
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
            this.exportProps.store = 'roleStore';
            this.exportProps.fileName = 'Role';
            this.exportProps.pagination = JSON.parse(JSON.stringify(this.pagination));
            this.$refs.exportbtn.exportToCSV();
        },
        /**
         * Add Role Modal method
         */
        addrole(){
            this.addRoleModal = true;
        },
        /**
         * Edit Role Modal
         * @param id
         */
        editItem(id){
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            // set the edit id in store
            this.$store.commit('roleStore/setEditId', id);
            //get by id to open and edit the role of particular id
            this.$store.dispatch('roleStore/getById', id).then(response => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {
                    this.$store.commit('roleStore/setModel', {model: response.data});
                    this.addRoleModal = true;
                }
            }, error => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                this.errorArr = this.getModalAPIerrorMessage(error);
                this.errorDialog = true;
            });
        },
        /**
         * Delete Data from row
         * @param id
         */
        deleteItem (id) {
            this.paramProps.idProps = id;
            this.paramProps.storeProps = 'roleStore';
            this.confirmation.title = this.$getConst('DELETE_TITLE');
            this.confirmation.description = this.$getConst('WARNING');
            this.modalOpen = true;
        },
        /**
         * Multiple Delete
         */
        multipleDelete() {
            const rowIds = [];
            let isAdminIncluded = false;
            this.selected.forEach((element) => {
                if (element.id == '1') {
                    isAdminIncluded = true;
                } else {
                    rowIds.push(element.id);
                }
            });
            if (isAdminIncluded) {
                this.errorArr = this.$getConst('ADMIN_DELETE_WARNING');
                this.errorDialog = true;
            } else {
                this.deleteProps.ids = rowIds;
                this.deleteProps.store = 'roleStore';
                this.$refs.multipleDeleteBtn.deleteMulti();
            }
        },
    },
    mounted(){},
    beforeDestroy() {
        this.$store.commit('roleStore/clearStore');
    },
});
