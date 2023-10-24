<template>
    <v-app id="appMain" class="appMain" light>
    <router-view :key="$route.fullPath"></router-view>
    </v-app>
</template>


<style lang="scss">
    @import "../../assets/plugins/flaticon/flaticon.css";
    @import "../../assets/plugins/flaticon2/flaticon.css";
    @import "../../assets/plugins/keenthemes-icons/font/ki.css";
</style>
<script>
    import { OVERRIDE_LAYOUT_CONFIG } from "../store/config.module";
export default {
    name:'',
    data() {
        return {
        }
    },
    mounted() {
        /**
         * this is to override the layout config using saved data from localStorage
         * remove this to use config only from static json (@/core/config/layout.config.json)
         */
        this.$store.dispatch(OVERRIDE_LAYOUT_CONFIG);
    },
    created() {
    },
}
</script>



