import constantPlugin from "./constantPlugin";
import moment from "moment-timezone";
import {
    format,
    differenceInMinutes,
    getTime,
    endOfDay,
    add,
    sub,
    parse,
    isMatch,
    isValid
} from "date-fns";

export default {
    data() {
        return {};
    },
    mixins: [constantPlugin],
    methods: {
        /* Current Time */
        currentTime() {
            var current = parseInt(moment.utc().valueOf() / 1000);
            return moment.unix(current).format(this.$getConst("TIME_CONST"));
        },

        /* Current Date */
        currentDate() {
            var current = parseInt(moment.utc().valueOf() / 1000);
            return moment.unix(current).format(this.$getConst("DATE_CONST"));
        },

        /* Current Date Time */
        currentDateTime() {
            var current = parseInt(moment.utc().valueOf() / 1000);
            return moment
                .unix(current)
                .format(this.$getConst("DATE_TIME_CONST"));
        },

        /* Format Date */
        getDateFormat(value) {
            let date = "";
            if (value != "" && value != null) {
                date = moment(String(value)).format(
                    this.$getConst("DATE_CONST")
                );
            }
            return date;
        },

        /* Format Time */
        getTimeFormat(value) {
            let date = "";
            if (value != "" && value != null) {
                date = moment(String(value)).format(
                    this.$getConst("TIME_CONST")
                );
            }
            return date;
        },
        /**
         * Get current date time with seconds
         * @returns {string}
         */
        getCurrentDateTimeWithSecond() {
            var current = parseInt(moment.utc().valueOf() / 1000);
            return moment
                .unix(current)
                .format(this.$getConst("DATE_TIME_WITH_SECONDS"));
        },

        /* Format Date Time */
        getDateTimeFormat(value) {
            let date = "";
            if (value != "" && value != null) {
                date = moment(String(value)).format(
                    this.$getConst("DATE_TIME_CONST")
                );
            }
            return date;
        },
        /**
         *Get current date format if date picker
         * @param date
         * @returns {string}
         */
        getCurrentDatePickerFormat() {
            return moment().format(this.$getConst("DATE_PICKER_CONST"));
        },
        /**
         * Get date format - DD/MM/YYYY
         * @param date
         * @returns {string|string}
         */
        computedDateFormattedMomentjs(date) {
            return date
                ? moment(date).format(this.$getConst("DATE_CONST"))
                : "";
        },

        getDateFormatt(value) {
            return value ? format(new Date(value), "dd-MM-yyyy") : "";
        },

        getDatePickerDateFormat(value) {
            return value ? format(new Date(value), "yyyy-MM-dd") : "";
        }
    },
    beforeCreate() {
        // reset snackbar
        this.$store.commit("snackbarStore/clearStore");
    },
    created() {},
    filters: {}
};
