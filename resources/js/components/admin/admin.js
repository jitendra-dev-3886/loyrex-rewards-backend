import CustomTable from "../../components/customtable/table";
import DeleteModal from "../../partials/DeleteModal";
import ExportBtn from "../../partials/ExportBtn";
import MultiDelete from "../../partials/MultiDelete";
import AddAdmin from "./AddAdmin.vue";
import AdminViewModal from "./AdminViewModal.vue";
import CommonServices from "../../common_services/common.js";
import Import from "../../partials/Import.vue";
import { mapState } from "vuex";
import {
    ADD_BODY_CLASSNAME,
    REMOVE_BODY_CLASSNAME
} from "../../store/htmlclass.module";

export default CustomTable.extend({
    name: "Admin",
    props: ["isModalOpen"],
    data: function() {
        var self = this;
        return {
            tab: "tab1",
            files: [],
            modalOpen: false,
            addAdminModal: false,
            isImportLoaded: false,
            urlApi: "adminStore/getAll", // set store name here to set/get pagination data and for access of actions/mutation via custom table
            headers: [
                { text: "Admin ID", value: "id", width: "10%" },
                { text: "Name", value: "first_name", width: "22%" },
                { text: "Role", value: "role_id", width: "20%" },
                { text: "Contact No.", value: "contact_number", width: "15%" },
                { text: "Email", value: "email", width: "23%" },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false,
                    width: "10%"
                }
            ],
            paramProps: {
                delete_allowed: true,
                idProps: "",
                storeProps: ""
            },
            confirmation: {
                title: "",
                description: "",
                btnCancelText: self.$getConst("BTN_CANCEL"),
                btnConfirmationText: self.$getConst("BTN_DELETE")
            },
            deleteProps: {
                ids: "",
                store: ""
            },
            exportProps: {
                id: "",
                store: "",
                fileName: "",
                pagination: ""
            },
            importProps: {
                store: "adminStore",
                modelName: "admin"
            },
            filterMenu: false,
            adminViewModal: false
        };
    },
    mixins: [CommonServices],
    components: {
        DeleteModal,
        AddAdmin,
        ExportBtn,
        MultiDelete,
        Import,
        AdminViewModal
    },
    computed: {
        ...mapState({
            pagination: state => state.adminStore.pagination,
            isEditMode: state => state.adminStore.editId > 0
        })
    },
    watch: {},
    created() {},
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
            this.exportProps.store = "adminStore";
            this.exportProps.fileName = "Admin";
            this.exportProps.pagination = JSON.parse(
                JSON.stringify(this.pagination)
            );
            this.$refs.exportbtn.exportToCSV();
        },

        /*
         * Add Admin Modal method
         * */
        addAdmin() {
            this.$refs.adminModal.$validator.reset();
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store
                .dispatch("roleStore/getAll", {
                    page: 1,
                    limit: 5000,
                    is_light: "true"
                })
                .then(
                    response => {
                        this.$store.dispatch(
                            REMOVE_BODY_CLASSNAME,
                            "page-loading"
                        );
                        if (response.error) {
                            this.errorArr = response.data.error;
                            this.errorDialog = true;
                        } else {
                            this.$store.commit(
                                "roleStore/setRoleList",
                                response.data.data
                            );
                            this.addAdminModal = true;
                        }
                    },
                    error => {
                        this.$store.dispatch(
                            REMOVE_BODY_CLASSNAME,
                            "page-loading"
                        );
                        this.errorArr = this.getModalAPIerrorMessage(error);
                        this.errorDialog = true;
                    }
                );
        },
        /*
         * Edit admin Modal
         * */
        onEditView(id, isEdit) {
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            // set the edit id in store
            this.$store.commit("adminStore/setEditId", id);
            //get by id to open and edit the role of particular id
            this.$store.dispatch("adminStore/getById", id).then(
                response => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        if (isEdit) {
                            this.addAdmin();
                        } else {
                            this.adminViewModal = true;
                        }
                    }
                },
                error => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    this.errorArr = this.getModalAPIerrorMessage(error);
                    this.errorDialog = true;
                }
            );
        },
        /**
         * Delete Data from row
         * @param id
         */
        deleteItem(id) {
            this.paramProps.idProps = id;
            this.paramProps.delete_allowed = true;
            this.paramProps.storeProps = "adminStore";
            this.confirmation.title = this.$getConst("DELETE_TITLE");
            this.confirmation.description = this.$getConst("WARNING");
            this.modalOpen = true;
        },
        /**
         * Multiple Delete
         */
        multipleDelete() {
            let rowIds = [];
            this.selected.forEach((element, index) => {
                rowIds[index] = element.id;
            });

            this.deleteProps.ids = rowIds;
            this.deleteProps.store = "adminStore";
            this.$refs.multipleDeleteBtn.deleteMulti();
        },
        /**
         *Refresh data on tab Click
         */
        refreshData() {
            var self = this;
            setTimeout(function() {
                if (self.tab == "tab1") {
                    self.refresh();
                } else if (self.tab == "tab2" && self.$refs.importdata) {
                    if (this.isImportLoaded) {
                        self.$refs.importdata.refreshImport();
                    }
                    this.isImportLoaded = true;
                }
            }, 100);
        },

        /* call this method when open add modal on same page */
        openAdminModalOnSamePage() {
            this.addAdmin();
        }
    },
    mounted() {
        this.onModalClear("adminStore", "clearModel");
        if (this.$route.params.willModalOpen) {
            setTimeout(() => {
                this.addAdmin();
            }, 100);
        }
    }
});
