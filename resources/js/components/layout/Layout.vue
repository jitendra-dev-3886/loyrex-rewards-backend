<template>
    <div class="d-flex flex-column flex-root">
        <!-- begin:: Header Mobile -->
        <KTHeaderMobile></KTHeaderMobile>
        <!-- end:: Header Mobile -->

        <Loader v-if="loaderEnabled" v-bind:logo="loaderLogo"></Loader>

        <!-- begin::Body -->
        <div class="d-flex flex-row flex-column-fluid page">
            <!-- begin:: Aside Left -->
            <KTAside v-if="asideEnabled" @modal-open-second = "modalOpenThird"></KTAside>
            <!-- end:: Aside Left -->

            <div id="kt_wrapper" class="d-flex flex-column flex-row-fluid wrapper">
                <!-- begin:: Header -->
                <KTHeader></KTHeader>
                <!-- end:: Header -->

                <!-- begin:: Content -->
                <div
                    id="kt_content"
                    class="content d-flex flex-column flex-column-fluid pt-0"
                >
                    <!-- begin:: Content Head -->

                    <!-- begin:: Content Body -->
                    <div class="d-flex flex-column-fluid">
                        <div
                            :class="{
                'container-fluid': contentFluid,
                container: !contentFluid
              }"
                        >
                            <transition name="fade-in-up">
                                <router-view ref="layoutRef"/>
                            </transition>
                        </div>
                    </div>
                </div>
                <KTFooter></KTFooter>
            </div>
        </div>
        <KTScrollTop></KTScrollTop>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";
    import KTAside from "../../components/layout/aside/Aside.vue";
    import KTHeader from "../../components/layout/header/Header.vue";
    import KTHeaderMobile from "../../components/layout/header/HeaderMobile.vue";
    import KTFooter from "../../components/layout/footer/Footer.vue";
    import HtmlClass from "../../common_services/services/htmlclass.service.js";
    import KTScrollTop from "../../components/layout/extras/ScrollTop";
    import Loader from "../../components/layout/extras/Loader.vue";
    import {
        ADD_BODY_CLASSNAME
    } from "../../store/htmlclass.module.js";

    export default {
        name: "Layout",
        components: {
            KTAside,
            KTHeader,
            KTHeaderMobile,
            KTFooter,
            KTScrollTop,
            Loader
        },
        data: function () {
            return {
                isModalOpen: false
            }
        },
        // mixins: [sconfig],
        beforeMount() {
            // show page loading
            this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");

            // initialize html element classes
            HtmlClass.init(this.layoutConfig());
        },
        mounted() {
        },
        methods: {
            modalOpenThird() {
                if(this.$route.name == 'admin') {
                    this.$refs.layoutRef.openAdminModalOnSamePage();
                }
                if(this.$route.name == 'users') {
                    this.$refs.layoutRef.openUserModalOnSamePage();
                }
                if(this.$route.name == 'voucher') {
                    this.$refs.layoutRef.openVoucherModalOnSamePage();
                }
            }
        },
        computed: {
            ...mapGetters([
                "breadcrumbs",
                "pageTitle",
                "layoutConfig"
            ]),

            /**
             * Check if the page loader is enabled
             * @returns {boolean}
             */
            loaderEnabled() {
                return !/false/.test(this.layoutConfig("loader.type"));
            },

            /**
             * Check if container width is fluid
             * @returns {boolean}
             */
            contentFluid() {
                return this.layoutConfig("content.width") === "fluid";
            },

            /**
             * Page loader logo image using require() function
             * @returns {string}
             */
            loaderLogo() {
                return process.env.BASE_URL + this.layoutConfig("loader.logo");
            },

            /**
             * Check if the left aside menu is enabled
             * @returns {boolean}
             */
            asideEnabled() {
                return !!this.layoutConfig("aside.self.display");
            },

            /**
             * Set the right toolbar display
             * @returns {boolean}
             */
            toolbarDisplay() {
                // return !!this.layoutConfig("toolbar.display");
                return true;
            },

            /**
             * Set the subheader display
             * @returns {boolean}
             */
            subheaderDisplay() {
                return !!this.layoutConfig("subheader.display");
            }
        }
    };
</script>
