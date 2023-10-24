<template>
    <div class="d-flex flex-column flex-root">
        <div
            id="kt_login"
            class="
                login login-1 login-signin-on
                d-flex
                flex-column flex-lg-row flex-row-fluid
                bg-white
            "
        >
            <!--begin::Aside-->
            <div
                class="
                    login-aside
                    d-flex
                    flex-row-auto
                    bgi-size-cover bgi-no-repeat
                    p-10 p-lg-15
                "
                style="display:none !important"
            >
                <!-- :style="{ backgroundColor: `url(${backgroundColor})` }" -->
                <!--begin: Aside Container -->
                <div
                    class="
                        d-flex
                        flex-row-fluid flex-column
                        justify-content-between
                    "
                >
                    <!--begin: Aside header -->
                    <!-- <a href="#" class="flex-column-auto">
                        <img :src="$getConst('LOYREX_LOGO')" />
                    </a> -->
                    <!--end: Aside header -->
                    <!--begin: Aside content -->
                    <div
                        class="
                            flex-column-fluid
                            d-flex
                            flex-column
                            justify-content-center
                        "
                    >
                        <!-- <h3 class="font-size-h1 mt-10 mb-5 text-white">
                            Welcome to Laravel!
                        </h3> -->
                    </div>
                    <!--end: Aside content -->
                    <!--begin: Aside footer for desktop -->
                    <div
                        class="
                            d-none
                            flex-column-auto
                            d-lg-flex
                            justify-content-between
                            mt-15
                        "
                    >
                        <div class="opacity-70 font-weight-bold text-white">
                            © {{ currentYear }} Loyrex
                        </div>
                    </div>
                    <!--end: Aside footer for desktop -->
                </div>
                <!--end: Aside Container -->
            </div>
            <!--begin::Aside-->

            <!--begin::Content-->
            <div
                class="
                    flex-row-fluid
                    d-flex
                    flex-column
                    position-relative
                    p-7
                    overflow-hidden
                "
            >
                <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
                    <v-flex xs12 sm8 md5 lg6 xl6>
                        <div
                            class="
                                text-center
                                mt-15
                                pb-12
                                pa-6
                                grid-view
                                brows-job-list
                            "
                        >
                            <h1
                                class="
                                    font-size-sm-100 font-weight-boldest
                                    text-dark-75
                                    mt-15
                                "
                                style="font-size: 150px"
                            >
                                404
                            </h1>
                            <h5 class="font-20 mb-4 mt-4">
                                We can’t seem to find the page you’re looking
                                for.
                            </h5>
                            <v-btn
                                height="50"
                                block
                                color="primary"
                                :to="
                                    `/${
                                        UserData && UserData.authorization
                                            ? 'dashboard'
                                            : ''
                                    }`
                                "
                                style="
    background-color: #e86924 !important;
    border-color: #e86924 !important;
"
                            >
                                Go To
                                {{
                                    UserData && UserData.authorization
                                        ? "Dashboard"
                                        : "Login"
                                }}
                            </v-btn>
                        </div>
                    </v-flex>
                </div>
            </div>
            <!--end::Content-->
        </div>
    </div>
</template>

<!-- Load login custom page styles -->
<style lang="scss">
/*@import "../../../../assetsassets/sass/pages/login/login-1.scss";*/
</style>

<script lang="ts">
import CommonServices from "../common_services/common.js";

export default {
    name: "PageNotFound",
    data() {
        return {
            currentYear: "",
            backgroundColor: "#00a0dc"
        };
    },
    mixins: [CommonServices],
    props: ["value"],

    mounted() {
        const d = new Date();
        this.currentYear = d.getFullYear();
    }
};
</script>
