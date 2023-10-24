import Vue from "vue"
import Vuetify from "vuetify"
import 'vuetify/dist/vuetify.min.css'


Vue.use(Vuetify);
const opts = {
    icons: {
        iconfont: 'md',  // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4'
    },
    theme: {
        dark: false,
    },
    themes: {
        light: {
            primary: '#000',
            secondary: '#AAA',
            accent: '#82B1FF',
            error: '#ff2118',
            info: '#424242',
            success: '#cba750',
            warning: '#FFC107'
        }
    },
}
export default new Vuetify(opts);
