<template>
    <div class="d-flex flex-column flex-root">
        <div
            class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-row-fluid bg-white"
            id="kt_login"
        >
            <!--begin::Aside-->
            <div
                class="login-aside d-flex flex-row-auto bgi-size-cover bgi-no-repeat p-10 p-lg-15 align-items-center bgi-position-x-right"
                :style="{ backgroundImage: `url(${backgroundImage})` }"
            >
                <!--begin: Aside Container -->
                <div
                    class="d-flex flex-row-fluid flex-column justify-content-center"
                >
                    <!--begin: Aside header -->
                    <a
                        href="#"
                        class="flex-column-auto  d-flex justify-content-center"
                    >
                        <img
                            src="/images/admin-logo-auth.png"
                            class="img-responsive"
                        />
                    </a>
                    <h3
                        class="font-size-h1 mt-10 mb-5 d-flex justify-content-center"
                    >
                        Welcome to Tech Reward!
                    </h3>
                    <!--end: Aside header -->
                    <!--begin: Aside content -->
                    <div
                        class="flex-column-fluid d-flex flex-column justify-content-center"
                    ></div>
                    <!--end: Aside content -->
                    <!--begin: Aside footer for desktop -->
                    <!--<div
                        class="d-none flex-column-auto d-lg-flex justify-content-between mt-15"
                    >
                        <div class="opacity-70 font-weight-bold text-white">
                            © {{ new Date().getFullYear() }} LoyReX
                        </div>
                    </div>
                    -->
                    <!--end: Aside footer for desktop -->
                </div>
                <!--end: Aside Container -->
            </div>
            <!--begin::Aside-->

            <!--begin::Content-->
            <div
                class="flex-row-fluid d-flex flex-column position-relative p-7 overflow-hidden"
            >
                <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
                    <router-view></router-view>
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

<script>
export default {
    name: "Auth",
    methods: {},
    computed: {
        backgroundImage() {
            return "/images/loyrex-bg.png";
        }
    }
};
</script>
