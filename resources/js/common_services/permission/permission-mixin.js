import {mapState} from "vuex";
import {isPermission} from './permission-filters';

export default {
    data() {
        return {}
    },
    computed: {
        ...mapState({
            userPermissions: state => state.permissionStore.userPermissions,
        }),
        /*getPermissionConst() {
            let module = location.pathname.split('/')[1];

            if (module == 'money') {
                return this.$getConst('MY_MONEY');
            }
            if (module == 'time') {
                return this.$getConst('MY_TIME');
            }
            if (module == 'patient') {
                return this.$getConst('MY_PATIENT');
            }
            if (module == 'product') {
                return this.$getConst('PRODUCT_AND_SERVICES');
            }
            if (module == 'dashboard') {
                return this.$getConst('DASHBOARD');
            }
            return '';
        }*/
    },
    methods: {
        hasPermission(permissionType, moduleType = '') {
            return  isPermission(this.userPermissions, moduleType == '' ? this.getPermissionConst : moduleType, permissionType);
        }
    }
}
