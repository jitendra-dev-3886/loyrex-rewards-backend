import CustomTable from '../../components/customtable/table'
import DeleteModal from "../../partials/DeleteModal";
import ExportBtn from "../../partials/ExportBtn";
import MultiDelete from "../../partials/MultiDelete";
import AddCategory from "./AddCategory.vue";
import CommonServices from '../../common_services/common.js';
import {mapState} from "vuex";
import {ADD_BODY_CLASSNAME, REMOVE_BODY_CLASSNAME} from "../../store/htmlclass.module";

export default CustomTable.extend({
    name: "Category",
    data: function () {
        var self = this;
        return {
            tab: 'tab1',
            files: [],
            modalOpen: false,
            addCategoryModal: false,
            isImportLoaded: false,
            urlApi: 'categoryStore/getAll',// set store name here to set/get pagination data and for access of actions/mutation via custom table
            headers: [
                { text: 'Category ID', value: 'id', width: '15%'},
                { text: 'Category Name', value: 'name', width: '30%'},
                { text: 'Parent Category', value: 'parent_id', width: '30%'},
                { text: 'No. of Items', value: 'no_of_items' , sortable: false, width: '15%'},
                { text: 'Actions', value: 'actions', sortable: false, width: '10%' },
            ],
            paramProps:{
                delete_allowed: true,
                idProps: '',
                storeProps: '',
            },
            confirmation:{
                title: '',
                description: '',
                btnCancelText: self.$getConst('BTN_CANCEL'),
                btnConfirmationText: self.$getConst('BTN_DELETE'),
            },
            deleteProps:{
                ids: '',
                store: '',
            },
            exportProps:{
                id: '',
                store: '',
                fileName: '',
                pagination: '',
            },
            importProps:{
                store: 'categoryStore',
                modelName: 'category',
            },
            state_id:'',
            filterMenu: false,
        }
    },
    mixins: [CommonServices],
    components: {
        DeleteModal,
        AddCategory,
        ExportBtn,
        MultiDelete,
    },
    computed: {
        ...mapState({
            setStateList: state => state.stateStore.stateList,
            pagination : state => state.categoryStore.pagination,
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
            this.exportProps.store = 'categoryStore';
            this.exportProps.fileName = 'Category';
            this.exportProps.pagination = JSON.parse(JSON.stringify(this.pagination));
            this.$refs.exportbtn.exportToCSV();
        },
        /*
        * Add Category Modal method
        * */
        addCategory(){
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store.dispatch("categoryStore/getAllParentCategory").then((response) => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {
                    this.$store.commit('categoryStore/setParentCategoryList', response.data.data);
                    this.addCategoryModal = true;
                }
            }, error => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                this.errorArr = this.getModalAPIerrorMessage(error);
                this.errorDialog = true;
            });
        },
        /*
        * Edit category Modal
        * */
        editItem(id){
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            // set the edit id in store
            this.$store.commit('categoryStore/setEditId', id);
            //get by id to open and edit the role of particular id
            this.$store.dispatch('categoryStore/getById', id).then(response => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {
                    this.addCategory();
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
        deleteItem (row) {
            this.paramProps.idProps = row.id;
            this.paramProps.storeProps = 'categoryStore';
            this.paramProps.delete_allowed = true
            this.confirmation.title = this.$getConst('DELETE_TITLE');
            if(row.parent_id && row.parent_id != '' && row.parent_id != undefined) {
                this.confirmation.description = this.$getConst('WARNING');
            }else {
                this.confirmation.description = this.$getConst('PARENT_CATEGORY_DELETE');
            }
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
            this.deleteProps.store = 'categoryStore';
            this.$refs.multipleDeleteBtn.deleteMulti();
        },
        /**
         *Refresh data on tab Click
         */
        refreshData(){
            var self = this;
            setTimeout(function () {
                if(self.tab == 'tab1') {
                    self.refresh();
                }
            }, 100);
        },
        /**
         * Redirect to product and set filter as clicked brand
         * @param row
         */
        handleClick(row) {
            if(row.no_of_items > 0) {
                if(row.parent_id && row.parent_id == 0) {
                    this.$router.push({name: 'product-catalogue', params:{ category_id: row.id}});
                } else {
                    this.$router.push({name: 'product-catalogue', params:{ category_id: row.parent_id , sub_category_id: row.id}});
                }
            }
        }
    },
    mounted(){
        if(this.$route.params.willModalOpen){
            setTimeout(() => {
                this.addCategory();
            }, 100);
        }
    }
});
