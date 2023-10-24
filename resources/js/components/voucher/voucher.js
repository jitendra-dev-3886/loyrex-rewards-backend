import CustomTable from "../../components/customtable/table";
import ExportBtn from "../../partials/ExportBtn";
import VoucherModal from "./VoucherModal.vue";
import { mapState } from "vuex";
import CommonServices from "../../common_services/common.js";
import ErrorModal from "../../partials/ErrorModal";
import DragAndDropFile from "../../partials/DragAndDropFile";
import VoucherViewModal from "./VoucherViewModal";
import SendVoucherMailModal from "./SendVoucherMailModal";

import {
    ADD_BODY_CLASSNAME,
    REMOVE_BODY_CLASSNAME
} from "../../store/htmlclass.module";

export default CustomTable.extend({
    name: "Voucher",
    data: function() {
        var self = this;
        return {
            isLoadingUsers: false,
            tab: "tab1",
            files: [],
            modalOpen: false,
            isImportLoaded: false,
            urlApi: "voucherStore/getAll", // set store name here to set/get pagination data and for access of actions/mutation via custom table
            headers: [
                { text: "Voucher ID", value: "id", width: "10%" },
                {
                    text: "Voucher Code",
                    value: "voucher_code",
                    width: "15%"
                },
                { text: "Voucher Name", value: "name", width: "15%" },
                {
                    text: "Voucher Link",
                    value: "link",
                    sortable: false,
                    width: "10%"
                },
                {
                    text: "Points/Products",
                    value: "points_products",
                    sortable: false,
                    width: "15%"
                },
                { text: "User", value: "user_id", width: "10%" },
                { text: "Validity", value: "valid_till", width: "10%" },
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
            deleteProps: {
                ids: "",
                store: ""
            },
            importProps: {
                store: "voucherStore",
                modelName: "voucher",
                multiple: false
            },
            exportProps: {
                id: "",
                store: "",
                fileName: "",
                pagination: "",
                idKeyName: ""
            },
            paramProps: {
                idProps: "",
                storeProps: ""
            },
            voucherDialogue: false,
            errorArr: [],
            errorDialog: false,
            voucherViewModal: false,
            sendVoucherMailModal: false,
            filterMenu: false,
            voucherType: "",
            voucherStatus: "",
            voucher_redeem: "",
            userAssign: "",
            referenceVoucherNo: "",
            search: null
        };
    },
    mixins: [CommonServices],
    components: {
        VoucherModal,
        ErrorModal,
        ExportBtn,
        DragAndDropFile,
        VoucherViewModal,
        SendVoucherMailModal
    },
    computed: {
        ...mapState({
            pagination: state => state.voucherStore.pagination,
            voucherTypes: state => state.voucherStore.voucherTypes,
            statusTypes: state => state.voucherStore.statusTypes,
            userTypes: state => state.voucherStore.userTypes
        }),
        userList() {
            let list = [];
            if (this.$store.state.userStore.filterUserList) {
                list = JSON.parse(
                    JSON.stringify(this.$store.state.userStore.filterUserList)
                );
                list.unshift({
                    id: "0",
                    first_name: "None",
                    last_name: "",
                    email: "",
                    contact_number: ""
                });
            }
            return list;
        }
    },
    watch: {
        search(val) {
            clearTimeout(this._timerId);
            // delay new call 500ms for search
            if (val != null && val != "") {
                this._timerId = setTimeout(() => {
                    //Actual API call
                    this.isLoadingUsers = true;

                    this.$store
                        .dispatch("userStore/getUserList", {
                            is_light: "true",
                            per_page: this.$getConst("DROPDOWN_PER_PAGE"),
                            search: val,
                            requestType: "filter"
                        })
                        .then(response => {
                            this.isLoadingUsers = false;
                        });
                }, 500);
            }
        }
    },
    methods: {
        /**
         *
         */
        setExport() {
            let rowIds = [];
            this.selected.forEach((element, index) => {
                rowIds[index] = element.id;
            });
            this.exportProps.ids = rowIds;
            this.exportProps.store = "voucherStore";
            this.exportProps.fileName = "Voucher";
            this.exportProps.idKeyName = "voucher_id";
            this.exportProps.pagination = JSON.parse(
                JSON.stringify(this.pagination)
            );
            this.$refs.exportbtn.exportToCSV();
        },
        /*
         * Add Voucher Modal method
         * */
        addVoucher(fromCreate) {
            this.$refs.voucherModal.$validator.reset();
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
                        this.voucherDialogue = true;
                    }
                },
                error => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    this.errorArr = this.getModalAPIerrorMessage(error);
                    this.errorDialog = true;
                }
            );
        },

        //Send Voucher mail to the user
        onSendMail(id) {
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store.commit("voucherStore/setEditId", id);
            this.$store.dispatch("voucherStore/getById", id).then(
                response => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        this.$store.commit(
                            "voucherStore/setVoucherModel",
                            response.data
                        );
                        this.sendVoucherMailModal = true;
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
         * Edit Voucher
         * @param id
         *  */
        onEditView(id, isEdit) {
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store.commit("voucherStore/setEditId", id);
            this.$store.dispatch("voucherStore/getById", id).then(
                response => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        if (isEdit) {
                            if (this.$refs.voucherModal) {
                                this.$refs.voucherModal.getProductsByCategory();
                            }
                            // Open edit voucher modal
                            this.addVoucher();
                        } else {
                            this.voucherViewModal = true;
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
                }
            }, 100);
        },
        /**
         * Update voucher status to active/deactive
         * @param status - boolean value - [0 -> Deactive], [1 -> Active]
         */
        updateStatus(row) {
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store
                .dispatch("voucherStore/updateStatus", {
                    id: row.id,
                    status: row.status == "1" ? "0" : "1"
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
                            this.getData();
                            this.$store.commit(
                                "snackbarStore/setMsg",
                                this.$getConst("VOUCHER_STATUS")
                            );
                        }
                    },
                    error => {
                        this.$store.dispatch(
                            REMOVE_BODY_CLASSNAME,
                            "page-loading"
                        );
                        //Error Handling for batch request
                        this.errorArr = this.getAPIErrorMessage(error);
                        this.errorDialog = true;
                    }
                );
        },
        /**
         * Filter
         */
        applyFilter() {
            let filter = {};
            if (
                this.voucherType != "" &&
                this.voucherType != null &&
                this.voucherType != undefined
            ) {
                filter.voucher_type = [this.voucherType];
            }
            if (
                this.voucher_redeem != "" &&
                this.voucher_redeem != null &&
                this.voucher_redeem != undefined
            ) {
                filter.voucher_redeem = [this.voucher_redeem];
            }
            if (
                this.voucherStatus != "" &&
                this.voucherStatus != null &&
                this.voucherStatus != undefined
            ) {
                filter.status = [this.voucherStatus];
            }
            if (
                this.userAssign != "" &&
                this.userAssign != null &&
                this.userAssign != undefined
            ) {
                filter.user_id = [this.userAssign.id];
            }
            if (
                this.referenceVoucherNo != "" &&
                this.referenceVoucherNo != null &&
                this.referenceVoucherNo != undefined
            ) {
                filter.reference_voucher_no = [this.referenceVoucherNo];
            }
            this.filterModel = filter;
            this.refresh();
            this.filterMenu = false;
        },
        /**
         * Reset Filter
         */
        resetFilter() {
            this.voucherType = "";
            this.voucherStatus = "";
            this.userAssign = "";
            this.referenceVoucherNo = "";
            this.applyFilter(false);
        },

        /* call this method when open add modal on same page */
        openVoucherModalOnSamePage() {
            this.addVoucher();
        }
    },
    created() {
        if (this.$route.params) {
            if (this.$route.params.flag == "R") {
                this.voucher_redeem = "1";
            }
            this.applyFilter();
        }
    },

    mounted() {
        this.onModalClear("voucherStore", "clearModel");
        this.onModalClear("userStore", "clearFilterUserList");
        if (this.$route.params.willModalOpen) {
            setTimeout(() => {
                this.addVoucher();
            }, 100);
        }
    },

    beforeDestroy() {
        this.$store.commit("voucherStore/clearStore");
    }
});
