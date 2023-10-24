import CustomTable from '../../components/customtable/table'
import DeleteModal from "../../partials/DeleteModal";
import ExportBtn from "../../partials/ExportBtn";
import MultiDelete from "../../partials/MultiDelete";
import ContactUsViewModal from "./ContactUsViewModal.vue";
import CommonServices from '../../common_services/common.js';
import ErrorModal from '../../partials/ErrorModal.vue';
import {mapState} from "vuex";
import {ADD_BODY_CLASSNAME, REMOVE_BODY_CLASSNAME} from "../../store/htmlclass.module";

export default CustomTable.extend({
    name: "ContactUs",

    data: function () {
        var self = this;
        return {
            tab: 'tab1',
            files: [],
            modalOpen: false,
            isImportLoaded: false,
            urlApi: 'contactStore/getAll',// set store name here to set/get pagination data and for access of actions/mutation via custom table
            headers: [
                {text: 'First Name', value: 'first_name', width: '22%'},
                {text: 'Last Name', value: 'last_name', width: '20%'},
                {text: 'Email', value: 'email', width: '23%'},
                {text: 'Subject', value: 'subject', width: '15%'},
                {text: 'Actions', value: 'actions', sortable: false, width: '10%'},
            ],
            paramProps: {
                delete_allowed: true,
                idProps: '',
                storeProps: '',
            },
            confirmation: {
                title: '',
                description: '',
                btnCancelText: self.$getConst('BTN_CANCEL'),
                btnConfirmationText: self.$getConst('BTN_DELETE'),
            },
            deleteProps: {
                ids: '',
                store: '',
            },
            exportProps: {
                id: '',
                store: '',
                fileName: '',
                pagination: '',
            },
            importProps: {},
            filterMenu: false,
            ContactUsViewModal: false,
            errorArr: [],
            errorDialog: false,

        }
    },
    mixins: [CommonServices],
    components: {
        DeleteModal,
        ExportBtn,
        MultiDelete,
        ContactUsViewModal,
        ErrorModal
    },
    computed: {
        ...mapState({
            pagination: state => state.contactStore.pagination,
            isEditMode: state => state.contactStore.editId > 0,
        })
    },
    watch: {},
    created() {
        // this.$store.dispatch('contactStore/getAll').then();
    },
    methods: {
        /**
         *  Export
         */
        setExport() {
            let rowIds = [];
            this.selected.forEach((element, index) => {
                rowIds[index] = element.id;
            });

            this.exportProps.ids = rowIds;
            this.exportProps.store = 'contactStore';
            this.exportProps.fileName = 'Contact';
            this.exportProps.pagination = JSON.parse(JSON.stringify(this.pagination));
            this.$refs.exportbtn.exportToCSV();
        },

        /*
        * Edit admin Modal
        * */
        onView(id) {
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            // set the edit id in store
            this.$store.commit('contactStore/setEditId', id);
            //get by id to open and edit the role of particular id
            this.$store.dispatch('contactStore/getById', id).then(response => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {
                    this.ContactUsViewModal = true;
                }
            }, error => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                this.errorArr = this.getModalAPIerrorMessage(error);
                this.errorDialog = true;
            });
        },

        deleteItem(id) {
            this.paramProps.idProps = id;
            this.paramProps.delete_allowed = true
            this.paramProps.storeProps = 'contactStore';
            this.confirmation.title = this.$getConst('DELETE_TITLE');
            this.confirmation.description = this.$getConst('WARNING');
            this.modalOpen = true;
        },

        refreshData() {
            var self = this;
            setTimeout(function () {
                if (self.tab == 'tab1') {
                    self.refresh();
                }
            }, 100);
        },

    },
    mounted() {
        this.onModalClear('contactStore', 'clearModel');
    }
});
