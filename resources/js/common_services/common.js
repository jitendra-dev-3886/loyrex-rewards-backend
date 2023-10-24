import { mapGetters, mapState } from "vuex";
import constantPlugin from "./constantPlugin";
import commonDateMethods from "./common-date-methods";
import commonErrorMethods from "./common-error-methods";
import moment from "moment-timezone";

import {
    mdiPencil,
    mdiDelete,
    mdiFilter,
    mdiPaperclip,
    mdiExport,
    mdiClose,
    mdiPlus,
    mdiEye,
    mdiGmail,
    mdiDownload,
    mdiUpload,
    mdiImage,
    mdiCircle,
    mdiCancel,
    mdiCheckCircleOutline,
    mdiImageEditOutline,
    mdiDeleteOff 
} from "@mdi/js";

export default {
    data() {
        return {
            rules: [
                value =>
                    !value ||
                    value.size < 4000000 ||
                    "File size should be less than 4 MB!"
            ],
            multipleFileRules: [
                value =>
                    !value.length ||
                    value.reduce((size, file) => size + file.size, 0) <
                        4000000 ||
                    "File size should be less than 4 MB!"
            ],
            emailRules: [
                value => !!value || "E-mail is required",
                value =>
                    /.+@.+\..+/.test(value.email) ||
                    /.+@.+\..+/.test(value) ||
                    "E-mail must be valid"
            ],
            icons: {
                mdiPencil,
                mdiDelete,
                mdiFilter,
                mdiPaperclip,
                mdiExport,
                mdiClose,
                mdiPlus,
                mdiEye,
                mdiGmail,
                mdiDownload,
                mdiUpload,
                mdiImage,
                mdiCircle,
                mdiCancel,
                mdiCheckCircleOutline,
                mdiImageEditOutline,
                mdiDeleteOff
            }
        };
    },
    mixins: [constantPlugin, commonDateMethods, commonErrorMethods],
    computed: {
        ...mapState({
            UserData: state => state.userStore.currentUserData
        }),
        ...mapGetters({})
    },
    methods: {
        /**
         * clear object Method
         * @param object
         */
        clearObject(object) {
            Object.keys(object).forEach(function(key) {
                delete object[key];
            });
        },
        /**
         * Modal clear functionality
         * @param storeName
         * @param stateName
         * @param isOpen - want to open modal or not (true, false)
         */
        onModalClear(storeName, stateName, isOpen) {
            if (!stateName) {
                stateName = "clearStore";
            }
            this.$validator.reset();
            this.isSubmitting = false;
            this.errorMessage = "";
            this.$store.commit(storeName + "/" + stateName);
            if (!isOpen) {
                this.$emit("input"); //Close Pop-up
            }
        },
        /**
         *  Logout
         */
        logout() {
            this.$store.dispatch("userStore/userLogout").then(
                response => {
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = false;
                    } else {
                        localStorage.clear();
                        this.$store.commit("userStore/clearUserData");
                        this.$router.push("/");
                    }
                },
                function(error) {
                    this.errorArr = this.getAPIErrorMessage(error.response);
                    this.errorDialog = false;
                }
            );
        },
        /**
         * Page reset scrolling
         */
        pageReset(storeName, variableName) {
            this.$store.commit(storeName + "" + variableName, 2);
        },
        /**
         * @objectData Object of data from which we need to filter
         * @param Object of filter condition {key : value}
         * @returns {Array list of filtered items}
         */
        filter(objectData, param) {
            let filterData = [];
            Object.keys(param).forEach(function(key) {
                objectData.filter(function(item) {
                    if (item[key] == param[key]) {
                        filterData.push(item);
                    }
                });
            });
            return filterData;
        },

        /**
         * Used for converting object to json format
         * @param param - param that we want to convert
         */
        objToJson(param) {
            let filter = encodeURIComponent(JSON.stringify(param));
            filter = filter.replace(/\\/g, "");
            return filter;
        },

        /**
         * Used for convert to CSV format
         * @param filename - name of file
         * @param data - response data
         * @param type - type of CSV
         * @param extension - extension of CSV
         */
        convertToCSV(
            filename,
            data,
            type = "text/csv;charset=utf-8;",
            extension = ".csv"
        ) {
            var exportedFilename =
                filename +
                "-" +
                this.getCurrentDateTimeWithSecond() +
                extension;
            var blob = new Blob([data], { type: type });
            if (navigator.msSaveBlob) {
                // IE 10+
                navigator.msSaveBlob(blob, exportedFilename);
            } else {
                var link = document.createElement("a");
                if (link.download !== undefined) {
                    // feature detection
                    // Browsers that support HTML5 download attribute
                    var url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", exportedFilename);
                    link.style.visibility = "hidden";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }
        },
        /**
         * Decode and extract url
         */
        extractFileProperties(fileUrl) {
            const splittedFile = decodeURIComponent(
                (fileUrl + "").replace(/\+/g, "%20")
            )
                .split("/")
                .reverse()[0];

            return {
                key: `tmp/${splittedFile}`,
                extension: splittedFile.split(".").reverse()[0]
            };
        },
        /**
         * Use for download a file from its url
         * @param url - url of file which needs to be downloaded
         * @param msg - msg to be displayed in toast
         */
        downloadFile(url, msg) {
            var link = document.createElement("a");
            var filename = url.split("/")[2];
            link.setAttribute("href", url);
            link.setAttribute("download", filename);
            link.style.visibility = "hidden";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            this.$store.commit("snackbarStore/setMsg", this.$getConst(msg));
        },
        /**
         * @param array of selected values in multi-select
         * */
        multipleSelectAllOptionFunctionality(selectedValues) {
            let flag = true;
            if (selectedValues.length > 1 && selectedValues[0] == "0") {
                selectedValues.shift();
                flag = true;
            }
            if (
                selectedValues.length > 1 &&
                selectedValues[selectedValues.length - 1] == "0"
            ) {
                selectedValues = [];
                selectedValues[0] = "0";
                flag = false;
            }
            if (selectedValues.length > 0 && selectedValues[0] == "0") {
                flag = false;
            }
            return { flag: flag, selectedValueArray: selectedValues };
        },
        routeName() {
            return window.location.pathname
                ? window.location.pathname.split("/")[1]
                : "";
        },
        /**
         * Used for converting object to base64 format
         * @param param - param that we want to convert
         */
        convetFiltertoBase64(filter) {
            if (
                filter &&
                Object.keys(filter).length > 0 &&
                filter.constructor === Object
            ) {
                const base64Filter = btoa(
                    unescape(encodeURIComponent(JSON.stringify(filter)))
                );
                return base64Filter;
            }
            return "";
        }
    },
    beforeCreate() {
        // reset snackbar
        this.$store.commit("snackbarStore/clearStore");
    },
    created() {},
    filters: {
        /**
         * Truncate no of character from the text
         * @param value - text
         * @param limit - no of chars which need to remove
         * @returns {string} - Truncated text
         */
        truncateText(value, limit) {
            if (value.length > limit) {
                value = value.substring(0, limit - 3) + "..."; // Here subrtracting 3 from text becoz we added 3 dots
            }
            return value;
        },
        /**
         * @value Object of User data
         * @returns {full name of user}
         */
        getFullName(value) {
            var name = "";
            if (value) {
                if (
                    typeof value.first_name != undefined &&
                    value.first_name != null
                ) {
                    name = value.first_name;
                }
                if (
                    typeof value.last_name != undefined &&
                    value.last_name != null
                ) {
                    name = name + " " + value.last_name;
                }
            }
            return name;
        },
        /**
         * Format number
         * @param value
         */
        formatNumber(value, decimalPoints = 0) {
            if ((value || value == "0") && decimalPoints > 0) {
                return parseFloat(value)
                    .toFixed(2)
                    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
            } else if ((value || value == "0") && decimalPoints == 0) {
                // return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                var x = value.toString();
                var lastThree = x.substring(x.length - 3);
                var otherNumbers = x.substring(0, x.length - 3);
                if (otherNumbers != "") lastThree = "," + lastThree;
                return (
                    otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") +
                    lastThree
                );
            } else {
                return "0";
            }
        }
    }
};
