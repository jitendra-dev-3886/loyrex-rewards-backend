import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersist from 'vuex-persist';

// theme store file
import htmlClass from "./htmlclass.module";
import config from "./config.module";
import breadcrumbs from "./breadcrumbs.module";


// module store
import batchRequestStore from './batch-request-store';
import snackbarStore from './snackbar-store.js';
import userStore from './user-store';
import userGroupStore from './usergroup-store';
import roleStore from './role-store';
import forgotPasswordStore from './forgot-password-store';
import changePasswordStore from './change-password-store';
import permissionStore from './permission-store';
import brandStore from "./brand-store";
import categoryStore from "./category-store";
import contactStore from "./contact-store";
import adminStore from "./admin-store";
import productStore from "./product-store";
import voucherStore from "./voucher-store";
import orderStore from "./order-store";
import dashboardStore from './dashboard-store';
import catalogueStore from './catalogue-store'
Vue.use(Vuex);

const vuexPersist = new VuexPersist({
    key: 'loyrex',
    storage: localStorage
});


export default new Vuex.Store({
    plugins: [vuexPersist.plugin],
    modules: {
        htmlClass,
        config,
        breadcrumbs,
        batchRequestStore,
        userStore,
        userGroupStore,
        snackbarStore,
        catalogueStore,
        roleStore,
        forgotPasswordStore,
        changePasswordStore,
        permissionStore,
        brandStore,
        categoryStore,
        adminStore,
        contactStore,
        productStore,
        voucherStore,
        orderStore,
        dashboardStore,

    }
});
