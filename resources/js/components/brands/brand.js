import CustomTable from "../../components/customtable/table";
import DeleteModal from "../../partials/DeleteModal";
import ExportBtn from "../../partials/ExportBtn";
import MultiDelete from "../../partials/MultiDelete";
import AddBrand from "./AddBrand.vue";
import CommonServices from "../../common_services/common.js";
import { mapState } from "vuex";
import {
    ADD_BODY_CLASSNAME,
    REMOVE_BODY_CLASSNAME
} from "../../store/htmlclass.module";

export default CustomTable.extend({
    name: "Brand",
    data: function() {
        var self = this;
        return {
            tab: "tab1",
            files: [],
            modalOpen: false,
            addBrandModal: false,
            isImportLoaded: false,
            urlApi: "brandStore/getAll", // set store name here to set/get pagination data and for access of actions/mutation via custom table
            headers: [
                { text: "Brand ID", value: "id", width: "10%" },
                { text: "Brand Name", value: "name", width: "20%" },
                {
                    text: "Brand Description",
                    value: "description",
                    width: "40%"
                },
                {
                    text: "No. of Items",
                    value: "no_of_items",
                    sortable: false,
                    width: "15%"
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false,
                    width: "15%"
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
                store: "brandStore",
                modelName: "brand"
            },
            state_id: "",
            filterMenu: false
        };
    },
    mixins: [CommonServices],
    components: {
        DeleteModal,
        AddBrand,
        ExportBtn,
        MultiDelete
    },
    computed: {
        ...mapState({
            setStateList: state => state.stateStore.stateList,
            pagination: state => state.brandStore.pagination
        })
    },
    watch: {},
    created() {},
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
            this.exportProps.store = "brandStore";
            this.exportProps.fileName = "Brand";
            this.exportProps.pagination = JSON.parse(
                JSON.stringify(this.pagination)
            );
            this.$refs.exportbtn.exportToCSV();
        },
        /*
         * Add Brand Modal method
         * */
        addBrand() {
            this.addBrandModal = true;
        },
        /*
         * Edit brand Modal
         * */
        editItem(id) {
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            // set the edit id in store
            this.$store.commit("brandStore/setEditId", id);
            //get by id to open and edit the role of particular id
            this.$store.dispatch("brandStore/getById", id).then(
                response => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        this.addBrand();
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
            this.paramProps.storeProps = "brandStore";
            this.paramProps.delete_allowed = true;
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
            this.deleteProps.store = "brandStore";
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
                }
            }, 100);
        },
        /**
         * Redirect to product and set filter as clicked brand
         * @param row
         */
        handleClick(row) {
            if (row.no_of_items > 0) {
                this.$router.push({
                    name: "product-catalogue",
                    params: { brand_id: row.id }
                });
            }
        }
    },
    mounted() {
        if (this.$route.params.willModalOpen) {
            setTimeout(() => {
                this.addBrand();
            }, 100);
        }
    }
});
