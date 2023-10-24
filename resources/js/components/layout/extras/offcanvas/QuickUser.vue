<template>
  <div class="topbar-item">
    <div
      class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
      id="kt_quick_user_toggle"
    >
      <span
        class="text-muted font-weight-bold font-size-base d-md-inline mr-1"
      >
        Hi,
      </span>
      <span
        class="text-dark-50 font-weight-bolder font-size-base d-md-inline mr-3"
      >
        {{username}}
      </span>
      <span class="symbol symbol-35 symbol-light-success">
        <img v-if="false" alt="Pic" :src="picture" />
        <span v-if="true" class="symbol-label font-size-h5 font-weight-bold text-uppercase">
          {{nameInitials}}
        </span>
      </span>
    </div>

    <div
      id="kt_quick_user"
      ref="kt_quick_user"
      class="offcanvas offcanvas-right p-10"
    >
      <!--begin::Header-->
      <div
        class="offcanvas-header d-flex align-items-center justify-content-between pb-5"
      >
        <h3 class="font-weight-bold m-0">Profile
        </h3>
        <a
          href="#"
          class="btn btn-xs btn-icon btn-light btn-hover-primary"
          id="kt_quick_user_close"
        >
          <i class="ki ki-close icon-xs text-muted"></i>
        </a>
      </div>
      <!--end::Header-->

      <!--begin::Content-->
      <perfect-scrollbar
        class="offcanvas-content pr-5 mr-n5 scroll"
        style="max-height: 90vh; position: relative;"
      >
        <!--begin::Header-->
        <div class="d-flex align-items-center mt-5">
          <div class="d-flex flex-column">
            <a class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary"
            >
              {{username}}
            </a>
            <div class="navi mt-2  mb-2">
              <a href="#" class="navi-item">
                <span class="navi-link p-0 pb-2">
                  <span class="navi-icon mr-1">
                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                      <!--begin::Svg Icon-->
                      <inline-svg
                        src="/media/svg/icons/Communication/Mail-notification.svg"
                      />
                      <!--end::Svg Icon-->
                    </span>
                  </span>
                  <span class="navi-text text-muted text-hover-primary">
                    {{userEmail}}
                  </span>
                </span>
              </a>
            </div>

              <router-link to="/role">
                  <button class="btn btn-light-primary btn-bold w100  mb-2" v-index="$getConst('ROLE')">
                      Role
                  </button>
              </router-link>
              <router-link to="/permission">
                  <button class="btn btn-light-primary btn-bold w100" v-index = "$getConst('PERMISSION')">
                      Permission
                  </button>
              </router-link>
              <v-divider></v-divider>
              <button class="btn btn-light-primary btn-bold mb-2" @click="changePasswordModal = true">
                  Change Password
              </button>
            <button class="btn btn-light-primary btn-bold " @click="logout">
              Sign out
            </button>

          </div>
        </div>
        <!--end::Header-->

      </perfect-scrollbar>
      <!--end::Content-->
    </div>

      <!-- change password Modal -->
      <change-password v-model="changePasswordModal"></change-password>
  </div>
</template>

<style lang="scss" scoped>
#kt_quick_user {
  overflow: hidden;
}
</style>

<script>
// import { LOGOUT } from "../../../../common_services/services/store/auth.module";
import KTLayoutQuickUser from "../../../../../assets/js/layout/extended/quick-user.js";
import KTOffcanvas from "../../../../../assets/js/components/offcanvas.js";
import ChangePassword from "../../../auth/ChangePassword.vue";
import { mapGetters, mapState} from 'vuex';
import CommonServices from '../../../../common_services/common.js';
import idle from '../../../../common_services/idle.js';

export default {
  name: "KTQuickUser",
  data() {
    return {
      changePasswordModal: false,
      username: '',
      nameInitials : '',
      userEmail: '',
    };
  },
  mixins: [CommonServices,idle],
  components:{ChangePassword},
  mounted() {
    this.userEmail = this.$store.state.userStore.currentUserData.email;
    this.username = this.$store.state.userStore.currentUserData.first_name;
    this.nameInitials = this.username.charAt(0);
    // Init Quick User Panel
    KTLayoutQuickUser.init(this.$refs["kt_quick_user"]);
  },
  methods: {
    closeOffcanvas() {
      new KTOffcanvas(KTLayoutQuickUser.getElement()).hide();
    },
      /**
       *  Logout
       */
      logout() {
          this.$store.dispatch('userStore/userLogout').then((response) => {
              if (response.error) {
                  this.errorArr = response.data.error;
                  this.errorDialog = false;
              } else {
                  localStorage.clear();
                  this.$store.commit('userStore/clearUserData');


                  this.$router.push('/');
              }
          }, function (error) {
              this.errorArr = this.getAPIErrorMessage(error.response);
              this.errorDialog = false;
          });
      },
  },
  computed: {
    ...mapGetters({
      currentUserData: 'userStore/currentUserData',

    }),
    picture() {
      if(this.$store.state.userStore.currentUserData.profile != ''){
        return this.$store.state.userStore.currentUserData.profile;
      }else{
        return "/images/profile.png";
      }
    }
  },
};
</script>
