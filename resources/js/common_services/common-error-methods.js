export default {
    data() {
        return {}
    },
    methods: {
        /**
         * Used error rule check
         * @param field - name of the field
         */
        getErrorRule(field) {
            var error = this.errors.items.find(function (item) {
                if (item.scope) {
                    return item.scope + "." + item.field == field;
                } else {
                    return item.field == field;
                }
            });
            if (error) {
                return error.rule;
            }
        },
        /**
         * Used for displaying error message
         * @param field - name of the field
         * @param indexVal - if in v-for send index
         * @returns Message
         */
        getErrorValue(field, indexVal) {
            let rule = '';
            if (indexVal != null && indexVal != 'undefined') {
                rule = this.getErrorRule(field + "_" + indexVal);
            } else {
                rule = this.getErrorRule(field);
            }
            if (rule) {
                var arr = field.split("."); //with scopes
                if (arr.length == 1) {
                    field = arr[0];
                } else {
                    field = arr[1];
                }
                let index = this.validationMessages[field].findIndex(p => p.key == rule);
                return this.validationMessages[field][index].value;
            } else {
                return;
            }
        },
        /**
         * Used for changing :error property where only color needs to change and no message needs to be shown e.g; checkbox
         * @param field - name of the field
         * @param indexVal - if in v-for send index
         * @returns {boolean}
         */
        getErrorCount(field,errorArr) {
            let rule = '';
            rule = this.getErrorRule(field, errorArr);
            if (rule) {
                return true;
            } else {
                return false;
            }
        },
        /**
         * Used for get API error messsage
         * @param response - response of error
         * @returns error
         */
        getAPIErrorMessage(response) {
            var error = "Something went wrong. Please try again later.";
            if (!response)
                return error;
            if (response.status == 422) {
                error = response.data.errors;
                var error_string = '';
                for (var key in error) {
                    error_string += error[key] + "<br>";
                }
                error = error_string;
                if (response.data.error) {
                    if (response.data.error.errors) {
                        error = this.getErrosFromResponse(response.data.error.errors)
                    } else {
                        error = response.data.error;
                    }
                }
            } else if (response.status == 403) {
                error = response.data;
            } else if (response.status == 401) {
                this.logout();
            }
            return error;
        },
        /**
         * Used for get error code
         * @param response - response of error
         * @returns error
         */
        getErrorCode(response) {
            var error = "Something went wrong. Please try again later.";
            if (response.status == 422) {
                error = response.data.errors;
                var error_string = '';
                for (var key in error) {
                    error_string += error[key] + "<br>";
                }
                error = error_string;
                if (response.data.error) {
                    error = response.data.error;
                }
            } else if (response.status == 403) {
                error = response.data;
            } else if (response.status == 401) {
                this.logout();
            }
            return error;
        },
        /**
         * Used for get errors from response
         * @param response - response of error
         * @returns error
         */
        getErrosFromResponse(response) {
            var err = "";
            Object.keys(response).forEach(function (key) {
                response[key].map(item => err = err + item + "<br/>");
            });
            return err;
        },
        /**
         * Used for get modal API error message
         * @param response - response of error
         * @returns error
         */
        getModalAPIerrorMessage(response) {
            var err = [];
            var self = this;
            Object.keys(response).forEach(function (key) {
                var val = response[key];
                if (!val.hasOwnProperty('data')) {
                    err.push({name: key, message: self.getErrorCode(response[key])})
                }
            });
            return err;
        },
    },
}
