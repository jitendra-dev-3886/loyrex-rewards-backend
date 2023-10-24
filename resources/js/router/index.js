import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);
import store from "../store/store";
import { RESET_LAYOUT_CONFIG } from "../store/config.module";

var siteName = " - Admin Panel";
/* Create new instance of VueRouter */
const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            component: () => import("../components/auth/Auth"),
            children: [
                {
                    name: "login",
                    path: "/",
                    component: () => import("../components/auth/Login.vue"),
                    meta: {
                        title: "Login" + siteName
                    }
                },
                {
                    name: "Logoff",
                    path: "/logoff",
                    component: () => import("../components/auth/Logoff.vue"),
                    meta: {
                        title: "Logoff" + siteName
                    }
                },
                {
                    name: "register",
                    path: "/register",
                    component: () => import("../components/auth/Register.vue"),
                    meta: {
                        title: "Register" + siteName
                    }
                }
            ]
        },
        {
            path: "/",
            redirect: "/dashboard",
            component: () => import("../components/layout/Layout.vue"),
            children: [
                {
                    path: "/dashboard",
                    name: "dashboard",
                    component: () =>
                        import("../components/dashboard/Dashboard.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "dashboard",
                        title: "Dashboard" + siteName,
                        pageTitle: "Dashboard"
                    }
                },
                {
                    path: "/users",
                    name: "users",
                    component: () => import("../components/user/Users.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "users",
                        title: "Users" + siteName,
                        pageTitle: "User Management"
                    }
                },
                {
                    path: "/user-group",
                    name: "user-groups",
                    component: () =>
                        import("../components/user-group/UserGroup.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "usergroups",
                        title: "User Groups" + siteName,
                        pageTitle: "User Group Management"
                    }
                },
                {
                    path: "/role",
                    name: "role",
                    component: () => import("../components/role/Role.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "roles",
                        title: "Role" + siteName,
                        pageTitle: "Role Management"
                    }
                },
                {
                    path: "/permission",
                    name: "permission",
                    component: () =>
                        import("../components/permission/Permission.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "permissions",
                        title: "Permission" + siteName,
                        pageTitle: "Permission Management"
                    }
                },
                {
                    path: "/brand",
                    name: "brand",
                    component: () => import("../components/brands/Brand.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "brands",
                        title: "Brand" + siteName,
                        pageTitle: "Brand Management"
                    }
                },
                {
                    path: "/category",
                    name: "category",
                    component: () =>
                        import("../components/categories/Category.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "categories",
                        title: "Category" + siteName,
                        pageTitle: "Category Management"
                    }
                },
                {
                    path: "/product-catalogue",
                    name: "product-catalogue",
                    component: () =>
                        import("../components/products/Product.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "product",
                        title: "Product Catalogue" + siteName,
                        pageTitle: "Product Catalogue Management"
                    }
                },
                {
                    path: "/catalogue",
                    name: "catalogue",
                    component: () =>
                        import("../components/catalogue/Catalogue.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "product",
                        title: "Catalogue" + siteName,
                        pageTitle: "Catalogue Management"
                    }
                },
                {
                    path: "/admin",
                    name: "admin",
                    component: () => import("../components/admin/Admin.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "admin-users",
                        title: "Admin" + siteName,
                        pageTitle: "Admin Management"
                    }
                },

                {
                    path: "/contact-us",
                    name: "contact-us",
                    component: () =>
                        import("../components/contact/Contact.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "managecontacts",
                        title: "Contact" + siteName,
                        pageTitle: "Contact Us Management"
                    }
                },

                {
                    path: "/orders",
                    name: "orders",
                    component: () => import("../components/order/Order.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "orders",
                        title: "Orders" + siteName,
                        pageTitle: "Order Management"
                    }
                },
                {
                    path: "/order-detail",
                    name: "order-detail",
                    component: () =>
                        import("../components/order/OrderDetail.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "orders",
                        title: "Orders" + siteName,
                        pageTitle: "Order Detail"
                    }
                },
                {
                    path: "/voucher",
                    name: "voucher",
                    component: () =>
                        import("../components/voucher/Voucher.vue"),
                    meta: {
                        requiresAuth: true,
                        permission: "vouchers", // Update permission here when APIs are ready and remove this commented line
                        title: "Voucher" + siteName,
                        pageTitle: "Voucher Management"
                    }
                }
            ]
        },
        {
            path: "*",
            component: () => import("../components/PageNotFound.vue"),
            name: "PageNotFound",
            meta: {
                title: `Page Not Found`
            }
        }
    ]
});

router.beforeEach((to, from, next) => {
    var authorization = store.state.userStore.currentUserData.authorization;
    document.title = to.meta.title;

    // reset config to initial state
    store.dispatch(RESET_LAYOUT_CONFIG);
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (authorization) {
            // this.$store.commit("userStore/setDefaultUrl", to.fullPath);
            next();
        } else if (authorization == "") {
            //this.$store.commit("userStore/setDefaultUrl", to.fullPath);
            store.commit("userStore/setDefaultUrl",to.fullPath);
            next("/logoff");
            return;
        } else {
            //this.$store.commit("userStore/setDefaultUrl", "/");
            store.commit("userStore/setDefaultUrl",to.fullPath);
            next("/");
        }
    } else {
        // if (to.path != "/logoff" && authorization == '') {
        //     next('/logoff')
        //     return
        // } else {

        //
        if (
            authorization &&
            authorization != "" &&
            authorization != null &&
            (to.fullPath == "/" || to.fullPath == "/logoff")
        ) {
            if (from.fullPath == "/" || from.fullPath == "/dashboard") {
                next(store.state.userStore.defaultRouteUrl);
            } else {
                next(from.fullPath);
            }
        } else {
            next();
        }
        // }
    }
});

router.beforeResolve((to, from, next) => {
    var permissionData = store.state.permissionStore.userPermissions;
    var access = "index";
    if (to.matched.some(record => record.meta.permission)) {
        var permissionArray = permissionData.filter(
            permission => permission.name == to.meta.permission
        );
        if (permissionArray.length > 0) {
            var subpermissionArray = permissionArray[0].sub_permissions;
            if (to.matched.some(record => record.meta.subpermission)) {
                var subpermissionmain = permissionArray[0].sub_permissions.filter(
                    subpermissionmain =>
                        subpermissionmain.name == to.meta.subpermission
                );
                if (subpermissionmain.length > 0) {
                    subpermissionArray = subpermissionmain[0].sub_permissions;
                } else {
                    next("/");
                    return;
                }
            }
            if (subpermissionArray.length > 0) {
                var subpermission = subpermissionArray.filter(
                    subpermission =>
                        subpermission.name == access &&
                        subpermission.is_permission == "1"
                );
                if (subpermission.length > 0) {
                    next();
                } else {
                    next("/users");
                    store.commit("permissionStore/setPermissionDialog", true);
                }
            }
        } else {
            next("/");
        }
    } else {
        next();
    }
});

// Loading chunk error
router.onError(error => {
    const pattern = /Loading chunk (\d)+ failed/g;
    const isChunkLoadFailed = error.message.match(pattern);
    const targetPath = router.history.pending.fullPath;
    if (isChunkLoadFailed) {
        router.replace(targetPath);
    }
});

export default router;
