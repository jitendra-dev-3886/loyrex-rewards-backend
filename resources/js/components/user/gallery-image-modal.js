import {mapGetters, mapState} from 'vuex';
import CommonServices from "../../common_services/common";
import ErrorBlockServer from "../../partials/ErrorBlockServer.vue"
import DeleteConfirm from "../../partials/DeleteConfirm.vue"
import ErrorModal from '../../partials/ErrorModal.vue';

export default {
    name: "GalleryImageModal",
    components: {
        ErrorBlockServer,DeleteConfirm,ErrorModal
    },
    props: ['value'],
    mixins: [CommonServices],
    data: function () {
        var self = this;
        return {
            email: '',
            errorMessage: '',
            errorArr: [],
            errorDialog: false,
            deleteConfirm: false,
            paramProps: {
                idProps: '',
                indexProps: '',
            },
        }
    },
    computed: {
        ...mapState({
            galleryList: state => state.userStore.galleryList,
        }),
    },
    methods: {
        /**
         * Cancel Method
         */
        onCancel(){
            this.errorMessage = '';
            this.$emit('input');
        },

        /* Delete Modal  */
        confirmDelete(id, index){
            this.paramProps.idProps = id;
            this.paramProps.indexProps = index;
            this.deleteConfirm = true;
        },

        /* Delete Image */
        deleteImage(payload){
            this.deleteConfirm = false;
            this.$store.dispatch('userStore/deleteImage' ,payload.idProps).then(response => {
                this.deleting = false;
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {
                    this.galleryList.splice(payload.indexProps, 1); // remove the image that we want to delete
                    this.$store.commit("userStore/setGalleryImageList", this.galleryList); // set Gallery image list
                    this.$store.commit("snackbarStore/setMsg", this.$getConst('DELETE_ACTION')); //Delete msg
                }
            }, error => {
                this.errorArr = this.getModalAPIerrorMessage(error);
                this.errorDialog = true;
            });
        },
    }
}
