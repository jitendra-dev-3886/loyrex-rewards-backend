<template>
    <form
        method="POST"
        name="import-csv-form"
        class="import-csv-form"
        role="form"
        novalidate
        autocomplete="off"
    >
        <v-layout row wrap class="flex-center mb-6">
            <div class="example-drag">
                <div class="upload">
                    <div v-if="files.length" class="mb-5 text-center">
                        <div v-for="(file, index) in files" :key="file.id">
                            <div class="mb-1">
                                <b>File Name:</b> {{ file.name }}
                            </div>
                            <div class="mb-1">
                                <b>File Size:</b> {{ file.size }}
                            </div>
                            <div
                                v-if="
                                    invalidFileError && file.type != 'text/csv'
                                "
                                style="color: red;"
                            >
                                <strong>{{ invalidFileError }}</strong>
                            </div>
                            <!-- <div
                                style="color: red;"
                                v-if="file.error"
                                class="mb-1"
                            >
                                <b>Error:</b> {{ file.error }}
                            </div> -->
                            <div v-if="file.success" class="mb-1">
                                success
                            </div>
                            <div v-else-if="file.active" class="mb-1">
                                active
                            </div>
                            <div v-else>
                                <ErrorBlock
                                    class="mt-4"
                                    validationField="file_upload"
                                    :errorList="errors"
                                    :validatonArray="
                                        validationMessages.file_upload
                                    "
                                ></ErrorBlock>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div>
                            <div class="text-center p-5">
                                <img
                                    src="/images/drag-and-drop-icon.png"
                                    class="img-responsive drag-drop-icon mb-4 mt-4"
                                />
                                <h4 class="mb-6">
                                    <template
                                        v-if="
                                            importProps.zipName == 'zipUpload'
                                        "
                                    >
                                        {{ $getConst("ZIP_FILE_UPLOAD_MSG") }}
                                    </template>
                                    <template v-else>
                                        {{ $getConst("FILE_UPLOAD_MSG") }}
                                    </template>
                                </h4>
                                <div class="position-relative">
                                    <span class="position-absolute or-txt"
                                        >or</span
                                    >
                                    <v-divider></v-divider>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-show="$refs.upload && $refs.upload.dropActive"
                        class="drop-active"
                    >
                        <h3>{{ $getConst("DRAG_AND_DROP_MSG") }}</h3>
                    </div>

                    <div class="example-btn">
                        <pre>{{ importProps.zipName }}</pre>
                        <file-upload
                            class="btn btn-grey font-weight-bold font-size-3 w100"
                            :multiple="importProps.multiple"
                            :drop="true"
                            :drop-directory="true"
                            v-model="files"
                            name="file_upload"
                            :accept="
                                importProps.zipName == 'zipUpload'
                                    ? '.zip'
                                    : '.csv'
                            "
                            v-validate="getFileUploadValidation"
                            ref="upload"
                        >
                            <i class="fa fa-plus"></i>
                            <template v-if="importProps.zipName == 'zipUpload'">
                                {{ $getConst("SELECT_ZIP_LABEL") }}
                            </template>
                            <template v-else>
                                {{ $getConst("SELECT_FILE_LABEL") }}
                            </template>
                        </file-upload>
                        <v-text-field
                            v-if="importProps.modelName == 'voucher'"
                            class="mt-4"
                            label="Enter User's Email ID"
                            v-model="voucherEmail"
                            :error-messages="getErrorValue('email')"
                            name="email"
                            type="email"
                            maxlength="191"
                            v-validate="'email'"
                        ></v-text-field>
                    </div>
                    <div class="flex-center">
                        <v-btn
                            :loading="isLoading"
                            color="primary"
                            class="btn font-weight-bold font-size-3 w100 mt-4"
                            :disabled="files.length == 0"
                            @click.prevent="uploadCsv"
                        >
                            {{ $getConst("FILE_SUBMIT") }}
                        </v-btn>
                    </div>
                    <div class="d-block mt-3" v-if="!this.importProps.zipName">
                        <span class="float-left mb-5">
                            <a class="font-size-5" @click="downloadSampleFile()"
                                >Download sample</a
                            >
                        </span>
                        <span class="float-right"> Max size: 5MB</span>
                    </div>
                    <div class="d-block mt-3" v-if="this.importProps.zipName">
                        <span class="float-left mb-5">
                            <a class="font-size-5" @click="downloadZipFile()"
                                >Download Sample zip</a
                            >
                        </span>
                        <span class="float-right"> Max size: 40MB</span>
                    </div>
                </div>
            </div>
        </v-layout>
        <error-modal :errorArr="errorArr" v-model="errorDialog"></error-modal>
    </form>
</template>

<script>
/**
 * sample csv import file
 */
import userCSV from "../../assets/samples/user_sample.csv";
import productCSV from "../../assets/samples/product_sample.csv";
import voucherCSV from "../../assets/samples/voucher_sample.csv";
import orderCSV from "../../assets/samples/orders.csv";
import CommonServices from "../common_services/common.js";
import ErrorModal from "./ErrorModal";
import { mapState } from "vuex";
import FileUpload from "vue-upload-component";
import ErrorBlock from "./ErrorBlock";

export default {
    name: "DragAndDropFile",
    data() {
        return {
            errorArr: [],
            errorDialog: false,
            files: [],
            voucherEmail: "",
            invalidFileError: "",
            validationMessages: {
                file_upload: [
                    { key: "size", value: "File size should be less than 5 MB" }
                ],
                email: [{ key: "email", value: "Valid email required" }]
            },
            isLoading: false,
            downloadLink: ""
        };
    },
    components: {
        ErrorModal,
        FileUpload,
        voucherCSV,
        ErrorBlock
    },
    props: ["importProps"],
    mixins: [CommonServices],
    computed: {
        ...mapState({}),
        getFileUploadValidation() {
            let valdationrule = "size:5000|ext:csv";
            if (this.importProps.zipName == "zipUpload") {
                valdationrule = `size:40000|ext:zip`;
            }
            return valdationrule;
        }
    },
    methods: {
        /**
         * Download csv
         **/
        downloadSampleFile() {
            let file_url = "";
            if (this.importProps.modelName == "user") {
                file_url = userCSV;
            }
            if (this.importProps.modelName == "product") {
                file_url = productCSV;
            }
            if (this.importProps.modelName == "voucher") {
                file_url = voucherCSV;
            }
            if (this.importProps.modelName == "order") {
                file_url = orderCSV;
            }

            if (file_url) {
                this.downloadFile(file_url, "DOWNLOAD_SAMPLE_CSV");
            }
        },

        /**
         * Dowload zip
         */
        downloadZipFile() {
            this.$store.dispatch("productStore/downloadZipFile").then(
                response => {
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        this.downloadLink = response.data.downloadLink;
                    }
                },
                error => {
                    this.errorArr = this.getAPIErrorMessage(error.response);
                    this.errorDialog = true;
                }
            );
            if (this.downloadLink) {
                this.downloadFile(this.downloadLink, "DOWNLOAD_ZIP_CSV");
            }
        },

        /**
         * upload csv
         */
        uploadCsv() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.invalidFileError = "";
                    var apiName = "import";
                    var formData = new FormData();
                    if (this.importProps.zipName == "zipUpload") {
                        apiName = "importZip";
                    }
                    if (this.importProps.multiple == false) {
                        if (this.files[0].file instanceof File) {
                            formData.append("file", this.files[0].file);
                            if (this.voucherEmail) {
                                formData.append("email", this.voucherEmail);
                            }
                        }
                    } else {
                        for (var index in this.files) {
                            if (this.files[index].file instanceof File) {
                                formData.append(
                                    "file[" + parseInt(index) + "]",
                                    this.files[index].file
                                );
                            }
                        }
                    }
                    this.isLoading = true;
                    this.$store
                        .dispatch(
                            this.importProps.store + "/" + apiName,
                            formData,
                            {
                                headers: {
                                    "Content-Type": "multipart/form-data"
                                }
                            }
                        )
                        .then(
                            response => {
                                this.isLoading = false;
                                if (response.error) {
                                    this.errorArr = response.data.error;
                                    this.errorDialog = true;
                                } else {
                                    // if no error this code will execute
                                    this.$store.commit(
                                        "snackbarStore/setMsg",
                                        this.$getConst("FILE_UPLOAD")
                                    );
                                    this.files = [];
                                    this.invalidFileError = "";
                                    this.voucherEmail = "";
                                }
                            },
                            error => {
                                this.isLoading = false;
                                this.errorArr = this.getAPIErrorMessage(
                                    error.response
                                );
                                this.errorDialog = true;
                            }
                        );
                } else {
                    this.invalidFileError = this.$getConst("UPLOAD_CSV");
                }
            });
        }
    }
};
</script>

<style scoped></style>
