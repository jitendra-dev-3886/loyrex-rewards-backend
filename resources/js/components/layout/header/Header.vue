<template>
    <div>
  <!-- begin:: Header -->
        <div
        id="kt_header"
        ref="kt_header"
        class="header"
        v-bind:class="headerClasses"
      >
        <div
          class="container-fluid d-flex align-items-center justify-content-between"
        >
          <!-- begin:: Header Menu -->
          <div
            class="header-menu-wrapper header-menu-wrapper-left"
            ref="kt_header_menu_wrapper"
          >
            <div
              v-if="headerMenuEnabled"
              id="kt_header_menu"
              ref="kt_header_menu"
              class="header-menu header-menu-mobile"
              v-bind:class="headerMenuClasses"
            >
                <v-layout>
                    <v-flex xs12 sm12 class="headline black-bg" primary-title>
                        <template v-if="getPageTitle"><p v-html="getPageTitle" class="m-0"></p></template>
                    </v-flex>
                </v-layout>
              <!-- example static menu here -->
              <KTMenu></KTMenu>
            </div>
          </div>
          <!-- end:: Header Menu -->
          <KTTopbar></KTTopbar>
        </div>
          <snackbar v-model="snackbar"></snackbar>
          <permission-dialog v-model="permissionDialog"></permission-dialog>
      </div>
        <div class="mobile-title d-flex d-md-none">
            <v-container>
            <v-layout>
                <v-flex xs12 sm12 class="headline black-bg" primary-title>
                    <template v-if="getPageTitle"><h4 v-html="getPageTitle" class="m-0"></h4></template>
                </v-flex>
            </v-layout>
            </v-container>
        </div>
    </div>
  <!-- end:: Header -->
</template>

<script>
import { mapGetters, mapState } from "vuex";
import KTTopbar from "../../../components/layout/header/Topbar.vue";
import KTLayoutHeader from "../../../../assets/js/layout/base/header.js";
import KTLayoutHeaderMenu from "../../../../assets/js/layout/base/header-menu.js";
import KTMenu from "../../../components/layout/header/Menu.vue";
import Snackbar from "../../../partials/Snackbar.vue";
import PermissionDialog from "../../../partials/PermissionDialog";
import CommonServices from "../../../common_services/common";

export default {
  name: "KTHeader",
  components: {
    KTTopbar,
    KTMenu,
      Snackbar,
      PermissionDialog
  },
    mixins:[CommonServices],
  mounted() {
    // Init Desktop & Mobile Headers
    KTLayoutHeader.init("kt_header", "kt_header_mobile");

    // Init Header Menu
    KTLayoutHeaderMenu.init(
      this.$refs["kt_header_menu"],
      this.$refs["kt_header_menu_wrapper"]
    );
  },
  computed: {
    ...mapGetters(["layoutConfig", "getClasses"]),

      ...mapState({
          snackbar: state => state.snackbarStore.snackbar,
          permissionDialog: state => state.permissionStore.permissionDialog,
      }),

    /**
     * Check if the header menu is enabled
     * @returns {boolean}
     */
    headerMenuEnabled() {
      return !!this.layoutConfig("header.menu.self.display");
    },

    /**
     * Get extra classes for header based on the options
     * @returns {null|*}
     */
    headerClasses() {
      const classes = this.getClasses("header");
      if (typeof classes !== "undefined") {
        return classes.join(" ");
      }
      return null;
    },

    /**
     * Get extra classes for header menu based on the options
     * @returns {null|*}
     */
    headerMenuClasses() {
      const classes = this.getClasses("header_menu");
      if (typeof classes !== "undefined") {
        return classes.join(" ");
      }
      return null;
    },

    getPageTitle() {
        return this.$route.meta.pageTitle;
    }
  }
};
</script>
