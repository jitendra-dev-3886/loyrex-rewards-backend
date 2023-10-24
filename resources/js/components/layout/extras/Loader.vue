<template>
    <!-- begin::Page loader -->
    <div class="page-loader page-loader-logo">
        <div class="progress progress--indeterminate">
            <div class="bar"></div>
        </div>
    </div>
    <!-- end::Page Loader -->
</template>

<script>
    export default {
        name: "Loader",
        props: {
            logo: String,
            spinnerClass: String
        }
    };
</script>
