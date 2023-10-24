import CustomTable from "../../components/customtable/table";
import DeleteModal from "../../partials/DeleteModal";
import ExportBtn from "../../partials/ExportBtn";
import MultiDelete from "../../partials/MultiDelete";
import CommonServices from "../../common_services/common.js";
import DragAndDropFile from "../../partials/DragAndDropFile";
import { mapState } from "vuex";
import {
    ADD_BODY_CLASSNAME,
    REMOVE_BODY_CLASSNAME
} from "../../store/htmlclass.module";

export default CustomTable.extend({
    name: "Order",
    data: function() {
        var self = this;
        return {
            tab: "tab1",
            files: [],
            modalOpen: false,
            isImportLoaded: false,
            urlApi: "orderStore/getAll", // set store name here to set/get pagination data and for access of actions/mutation via custom table
            headers: [
                { text: "Order ID", value: "id", width: "10%" },
                {
                    text: "Items",
                    value: "items",
                    width: "15%",
                    sortable: false
                },
                {
                    text: "Category",
                    value: "category",
                    width: "15%",
                    sortable: false
                },
                { text: "Customer Details", value: "first_name", width: "15%" },
                { text: "Quantity", value: "quantity", width: "10%" },
                { text: "Total Points", value: "total_points", width: "10%" },
                { text: "Status", value: "order_status", width: "15%" },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false,
                    width: "10%"
                }
            ],
            paramProps: {
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
                store: "orderStore",
                modelName: "order",
                multiple: false
            },
            filterMenu: false,
            orderStatus: "",
            fromDate: "",
            fromDateMenu: false,
            toDate: "",
            toDateMenu: false
        };
    },
    mixins: [CommonServices],
    components: {
        DeleteModal,
        ExportBtn,
        MultiDelete,
        DragAndDropFile
    },
    computed: {
        ...mapState({
            pagination: state => state.orderStore.pagination,
            isEditMode: state => state.orderStore.editId > 0,
            orderStatusList: state => state.orderStore.orderStatusList
        })
    },
    watch: {},
    created() {
        if (this.$route.params) {
            if (this.$route.params.flag == "D") {
                this.orderStatus = "3";
            } else if (this.$route.params.flag == "P") {
                this.orderStatus = "0";
            } else {
            }
            this.applyFilter();
        }
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
            this.exportProps.store = "orderStore";
            this.exportProps.fileName = "Order";
            this.exportProps.pagination = JSON.parse(
                JSON.stringify(this.pagination)
            );
            this.$refs.exportbtn.exportToCSV();
        },

        /**
         * Open order detail page on edit action
         * @param row
         */
        editOrder(row) {
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store.commit("orderStore/setEditId", row.id);
            //get by id to open and edit the role of particular id
            this.$store.dispatch("orderStore/getById", row.id).then(
                response => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        this.$router.push({ name: "order-detail" });
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
         * Filter
         */
        applyFilter() {
            let filter = {};
            if (
                this.orderStatus != "" &&
                this.orderStatus != null &&
                this.orderStatus != undefined
            ) {
                filter.order_status = [this.orderStatus];
            }
            if (this.fromDate != "" && this.toDate != "") {
                filter.created_at = this.fromDate + "to" + this.toDate;
            }

            this.filterModel = filter;
            this.refresh();
            // this.filterMenu= false;
        },
        /**
         * Reset Filter
         */
        resetFilter() {
            this.orderStatus = "";
            this.fromDate = "";
            this.toDate = "";
            this.applyFilter(false);
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
        }
    },
    mounted() {
        this.onModalClear("orderStore", "clearModel");
    },
    beforeDestroy() {
        this.$store.commit("orderStore/clearStore");
    }
});
