import CustomTable from "../../components/customtable/table";
import DeleteModal from "../../partials/DeleteModal";
import ExportBtn from "../../partials/ExportBtn";
import UserModal from "./UserModal.vue";
import { mapState } from "vuex";
import CommonServices from "../../common_services/common.js";
import ErrorModal from "../../partials/ErrorModal";
import MultiDelete from "../../partials/MultiDelete";
import DragAndDropFile from "../../partials/DragAndDropFile";
import UserViewModal from "./UserViewModal";
import {
    ADD_BODY_CLASSNAME,
    REMOVE_BODY_CLASSNAME
} from "../../store/htmlclass.module";

export default CustomTable.extend({
    name: "Users",
    data: function() {
        var self = this;
        return {
            tab: "tab1",
            files: [],
            modalOpen: false,
            isImportLoaded: false,
            urlApi: "userStore/getAll", // set store name here to set/get pagination data and for access of actions/mutation via custom table
            headers: [
                { text: "User ID", value: "id", width: "6%" },
                { text: "Name", value: "first_name", width: "15%" },
                { text: "Job Title", value: "job_title", width: "20%" },
                { text: "Contact No.", value: "contact_number", width: "15%" },
                { text: "Email", value: "email", width: "20%" },
                {
                    text: "User Groups",
                    value: "userGroup_array",
                    sortable: false,
                    width: "15%"
                },
                { text: "Reward Points", value: "reward_points", width: "14%" },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false,
                    width: "10%"
                }
            ],
            confirmation: {
                title: "",
                description: "",
                btnCancelText: self.$getConst("BTN_CANCEL"),
                btnConfirmationText: self.$getConst("BTN_DELETE")
            },
            userGroupNames: [],
            deleteProps: {
                ids: "",
                store: ""
            },
            importProps: {
                store: "userStore",
                modelName: "user",
                multiple: false
            },
            exportProps: {
                id: "",
                store: "",
                fileName: "",
                pagination: ""
            },
            paramProps: {
                delete_allowed: true,
                idProps: "",
                storeProps: ""
            },
            userDialogue: false,
            errorArr: [],
            errorDialog: false,
            role_id: "",
            filterMenu: false,
            images: [],
            userViewModal: false,
            userGroupsId: "",
            isGroupLoading: false
        };
    },
    mixins: [CommonServices],
    components: {
        DeleteModal,
        UserModal,
        ErrorModal,
        ExportBtn,
        MultiDelete,
        DragAndDropFile,
        UserViewModal
    },
    computed: {
        ...mapState({
            pagination: state => state.userStore.pagination,
            userGroupList: state => state.userGroupStore.userGroupList,
            userGroupmodel: state => state.userGroupStore.userGroupmodel
        })
    },
    watch: {},
    created() {
        this.getUserGroup();

        if (this.$route.params.userGroupsId) {
            if (this.$route.params.hasOwnProperty("userGroupsId")) {
                this.userGroupsId = this.$route.params.userGroupsId;
            }
            this.applyFilter(true);
        }
    },

    methods: {
        handleClick(row) {
            /*  var groupid
                for(let i == 0 ;i<serGroup.length;i++){
                    if(user)u
                }*/

            this.$router.push({
                name: "user-groups",
                params: { userGroupsId: row }
            });
            /*        this.$router.push({name: 'user-groups', params:{ userGroupsId: row.userGroup[id]}});*/
        },
        getUserByGroup() {
            this.$store.dispatch("userGroupStore/getUserGroupList").then(
                response => {
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                    }
                },
                error => {
                    this.errorArr = this.getAPIErrorMessage(error.response);
                    this.errorDialog = true;
                }
            );
        },
        /**
         *
         */
        setExport() {
            let rowIds = [];
            this.selected.forEach((element, index) => {
                rowIds[index] = element.id;
            });

            this.exportProps.ids = rowIds;
            this.exportProps.store = "userStore";
            this.exportProps.fileName = "User";
            this.exportProps.pagination = JSON.parse(
                JSON.stringify(this.pagination)
            );
            this.$refs.exportbtn.exportToCSV();
        },
        getUserGroup() {
            this.isGroupLoading = true;
            this.$store
                .dispatch("userGroupStore/getUserGroupList", {
                    id: this.userGroupmodel.id
                })
                .then(
                    response => {
                        this.isGroupLoading = false;
                        if (response.error) {
                            this.errorArr = response.data.error;
                            this.errorDialog = true;
                        } else {
                            // Reset category list
                            this.$store.commit(
                                "userGroupStore/setUserGroupList",
                                []
                            );
                            //set sub category list
                            this.$store.commit(
                                "userGroupStore/setUserGroupList",
                                JSON.parse(JSON.stringify(response.data.data))
                            );
                        }
                    },
                    function(error) {
                        this.isGroupLoading = false;
                        this.errorMessage = this.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
        },
        /**
         * delete user
         * @param id
         */
        deleteItem(id) {
            this.paramProps.idProps = id;
            this.paramProps.storeProps = "userStore";
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
            this.deleteProps.store = "userStore";
            this.$refs.multipleDeleteBtn.deleteMulti();
        },
        /*
         * Add User Modal method
         * */
        addUser() {
            this.$refs.userModal.$validator.reset();
            this.getUserByGroup();
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store.dispatch("categoryStore/getAllParentCategory").then(
                response => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        this.$store.commit(
                            "categoryStore/setParentCategoryList",
                            response.data.data
                        );
                        this.userDialogue = true;
                    }
                },
                error => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    this.errorArr = this.getModalAPIerrorMessage(error);
                    this.errorDialog = true;
                }
            );
        },
        /*
         * Edit User
         * @param id
         *  */
        onEditView(id, isEdit) {
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store.commit("userStore/setEditId", id);
            this.$store.dispatch("userStore/getById", id).then(
                response => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        if (isEdit) {
                            if (this.$refs.userModal) {
                                /*this.getUserByGroup();*/
                                // this.$refs.userModal.getProductsByCategory();
                            }
                            // Open edit user modal
                            this.addUser();
                        } else {
                            this.userViewModal = true;
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
        applyFilter(fromCreate) {
            let filter = {};
            if (
                this.userGroupsId != "" &&
                this.userGroupsId != null &&
                this.userGroupsId != undefined
            ) {
                filter.pf = {};
                filter.pf.userGroup = [this.userGroupsId];
            }
            /* this.getUserGroup()*/
            this.filterModel = filter;
            if (!fromCreate) {
                this.refresh();
            }
            this.filterMenu = false;
        },

        resetFilter() {
            this.userGroupsId = "";
            /*     this.$store.commit("userGroupStore/setUserGroupList", []);*/
            this.applyFilter(false);
        },

        /* call this method when open add modal on same page */
        openUserModalOnSamePage() {
            this.addUser();
        }
    },

    mounted() {
        this.onModalClear("userStore", "clearModel");
        if (this.$route.params.willModalOpen) {
            setTimeout(() => {
                this.addUser();
            }, 100);
        }
    },

    beforeDestroy() {
        this.$store.commit("userStore/clearModel");
    }
});
