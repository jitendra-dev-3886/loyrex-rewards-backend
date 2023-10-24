import ErrorBlockServer from "../../partials/ErrorBlockServer.vue"
import {mapState} from "vuex";
import CommonServices from "../../common_services/common";
import ErrorModal from '../../partials/ErrorModal.vue';
import {ADD_BODY_CLASSNAME, REMOVE_BODY_CLASSNAME} from "../../store/htmlclass.module";

export default {
    components: { ErrorBlockServer, ErrorModal},
    mixins: [CommonServices],
    data() {
        return {
            errorDialog: false,
            errorArr: [],
            role_id: '',
        }
    },
    computed: {
        ...mapState({
            permissions: state => state.permissionStore.permissions,
            roleList: state => state.roleStore.roledropdownlist,
            currentUser: state => state.userStore.currentUserData,
        }),
    },
    mounted() {
        this.$store.commit('permissionStore/clearPermissions');
        this.$store.dispatch("roleStore/getAll",{page:1,limit:5000}).then(response => {
            if (response.error) {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                this.errorArr = response.data.error;
                this.errorDialog = true;
            } else {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                this.$store.commit('roleStore/setRoleList', response.data.data);
            }
        }, error => {
            this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
            this.errorArr = this.getModalAPIerrorMessage(error);
            this.errorDialog = true;
        });

    },
    methods: {
        getPermissions() {
            this.$store.dispatch("permissionStore/getById", this.role_id).then(response => {
                if (response.error) {
                    this.errorDialog = false;
                    this.errorArr = response.data.error;
                }
            }, error => {
                this.errorArr = this.getAPIErrorMessage(error.response);
                this.errorDialog = true;
            });
        },
        /**
         * reset permission if permission is set or revoked from current role
         */
        resetPermission() {
            this.$store.dispatch("permissionStore/getById", this.role_id).then(response => {
                if (response.error) {
                    this.errorDialog = false;
                    this.errorArr = response.data.error;
                }else{
                    if(response.data && response.data.length > 0) {
                        this.$store.commit('permissionStore/setUserPermissions', response.data);
                    }
                }
            }, error => {
                this.errorArr = this.getAPIErrorMessage(error.response);
                this.errorDialog = true;
            });
        },
        editPermission(permission) {
            let sendParams = {
                role_id: this.role_id,
                permission_id: permission.id,
                is_permission: permission.is_permission
            };
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
            this.$store.dispatch("permissionStore/edit", sendParams).then(response => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                if (response.error) {
                    this.errorDialog = false;
                    this.errorArr = response.data.error;
                } else {
                    if(this.currentUser.role_id == this.role_id){
                        this.resetPermission();
                    }
                    this.$store.commit("snackbarStore/setMsg", this.$getConst('UPDATE_ACTION'));
                }
            }, error => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                this.errorArr = this.getAPIErrorMessage(error.response);
                this.errorDialog = true;
            });
        }
    },

}
