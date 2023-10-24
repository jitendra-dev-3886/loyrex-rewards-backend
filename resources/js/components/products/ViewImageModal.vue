<template>
    <v-dialog
        :value="value"
        content-class="modal-dialog"
        @click:outside="onCancel()"
        @keydown.esc="onCancel"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                <span>View Image</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="onCancel">
                    <v-icon>{{ icons.mdiClose }}</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <a :href="featured_image" target="_blank"
                    ><img
                        :src="featured_image"
                        height="400px"
                        width="400px"
                        @error="onImgError()"
                        class="mt-1 mb-1"
                /></a>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import CommonServices from "../../common_services/common.js";
import { mapState } from "vuex";

export default {
    name: "ViewImageModal",
    mixins: [CommonServices],
    props: {
        value: { default: "" }
    },
    data() {
        return {
            featured_image: ""
        };
    },
    computed: {
        ...mapState({
            productList: state => state.productStore.productList
        })
    },
    created() {
        this.featured_image = this.productList.featured_image;
    },
    methods: {
        onCancel() {
            this.featured_image = "";
            this.$emit("input"); //Close Pop-up
        },
        onImgError() {
            if (this.featured_image) {
                this.featured_image = "/images/default_product_image.png";
            }
        }
    }
};
</script>
