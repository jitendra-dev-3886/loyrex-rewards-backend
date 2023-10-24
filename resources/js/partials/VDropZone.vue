<template>
    <div>
        <v-card
            :loader-height="1.5"
            :disabled="loading"
            elevation="0"
            :loading="loading"
            tile
            :class="
                `cursor-pointer ${
                    getErrorCount(dataVVScope + name, errors)
                        ? 'dropzone-error'
                        : ''
                }`
            "
        >
            <vue-dropzone
                :ref="name"
                :id="name"
                :key="`${name}${dropzoneOptions.maxFiles}`"
                @vdropzone-removed-file="removeFile($event)"
                @vdropzone-complete="$emit('is-uploading', false)"
                @vdropzone-files-added="$emit('is-uploading', true)"
                @vdropzone-file-added="$emit('is-uploading', true)"
                @vdropzone-s3-upload-error="s3UploadError"
                @vdropzone-s3-upload-success="s3UploadSuccess($event)"
                :awss3="awss3"
                :options="dropzoneOptions"
                :name="name"
                :useCustomSlot="true"
            >
                <div
                    :class="
                        `dropzone-custom-content ${
                            getErrorCount(dataVVScope + name, errors)
                                ? 'error--text'
                                : ''
                        }`
                    "
                >
                    <span> {{ label }} </span>

                    <p class="text-caption" v-if="hint">{{ hint }}</p>
                </div>
            </vue-dropzone>
            <ErrorBlock
                v-if="
                    errorType == 'error-messages' ||
                        errorType == 'errorMessages'
                "
                :validationField="dataVVScope + name"
                :errorList="errors"
                :validatonArray="validationMessages[name]"
            ></ErrorBlock>
        </v-card>
    </div>
</template>

<script>
import CommonServices from "../common_services/common";

import ErrorBlock from "../partials/ErrorBlock.vue";

export default {
    components: {
        ErrorBlock
    },

    data() {
        return {
            awss3: {
                signingURL: "/api/v1/aws-s3-signer-url",
                headers: {
                    Authorization: this.$store.state.userStore.currentUserData
                        .authorization
                        ? "Bearer " +
                          this.$store.state.userStore.currentUserData
                              .authorization
                        : ""
                },
                params: {},
                sendFileToServer: true,
                withCredentials: false
            }
        };
    },
    props: {
        value: { default: "" },
        name: { default: "" },
        label: { default: "UPLOAD IMAGE" },
        uploadMultiple: { default: false },
        limit: { default: 4 },
        maxFilesize: { default: 4 },
        alreadyUploaded: { default: 0 },
        loading: { default: false },
        hint: { default: "" },
        errorType: { default: "error" },
        acceptedFileTypes: {
            default: ".jpeg,.jpg,.png,.bmp,.gif,.svg,.webp,.mp4,.mkv,.3gp,.flv"
        },
        validationMessages: { default: () => "" },
        dataVVScope: { default: () => "" }
    },
    computed: {
        dropzoneOptions: {
            get: function() {
                return {
                    url: "https://httpbin.org/post",
                    thumbnailWidth: 150,
                    maxFilesize: this.maxFilesize ? this.maxFilesize : 10,
                    maxFiles: this.limit - this.alreadyUploaded,
                    addRemoveLinks: true,
                    autoProcessQueue: true,
                    uploadMultiple: this.uploadMultiple,
                    dictDefaultMessage: this.label,
                    acceptedFiles: this.acceptedFileTypes
                };
            }
        }
    },
    mixins: [CommonServices],
    methods: {
        s3UploadError(_error) {
            this.$emit("is-uploading", false);
        },

        s3UploadSuccess(s3ObjectLocation) {
            const uploadedFiles = this.$refs[this.name].dropzone.files;
            uploadedFiles.forEach(file => {
                if (file.s3ObjectLocation == s3ObjectLocation) {
                    if (
                        this.alreadyUploaded != this.limit &&
                        file.accepted &&
                        file.status != "error"
                    ) {
                        this.$emit("upload-success", s3ObjectLocation);
                    }
                }
            });
        },

        removeFile(event) {
            if (event.accepted) {
                this.$emit("removed-file", event);
            }
        },

        removeAllFiles() {
            this.$refs[this.name].removeAllFiles();
        }
    }
};
</script>

<style scoped>
.dropzone-custom-content {
    /* position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center; */
}
.dropzone-error {
    border: 1px solid #ff5252;
}

.dropzone .dz-preview:hover {
    z-index: 10 !important;
}
</style>
