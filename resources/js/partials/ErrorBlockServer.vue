<template>
    <v-alert
        dense
        outlined
        type="error" v-if="errorMessage" v-html="errorMessage" style="font-size: 13px"
    >
    </v-alert>
</template>

<script>
    export default {
        props: [
            "errorMessage",
        ],
    };
</script>
