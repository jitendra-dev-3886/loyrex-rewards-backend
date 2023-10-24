import Vue from "vue";
import Vuex from "vuex";

// import auth from "./auth.module";
import htmlClass from "../../../store/htmlclass.module";
import config from "../../../store/config.module";
import breadcrumbs from "../../../store/breadcrumbs.module";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    // auth,
    htmlClass,
    config,
    breadcrumbs
  }
});
