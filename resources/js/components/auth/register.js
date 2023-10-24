import CommonServices from '../../common_services/common.js';
import ErrorBlockServer from "../../partials/ErrorBlockServer";
import ErrorModal from "../../partials/ErrorModal";
import {mapState} from 'vuex'
import Snackbar from "../../partials/Snackbar.vue"
import GalleryImageModal from "../user/GalleryImageModal"

export default {
    name: "Register",
    components: {
        ErrorModal,
        ErrorBlockServer,
        Snackbar,
        GalleryImageModal
    },
    data() {
        return {
            errorArr: [],
            errorDialog: false,
            errorMessage: '',
            show_password: false,
            validationMessages: {
                "name": [{key: 'required', value: 'Name required'}],
                "email": [{key: 'required', value: 'Email required'}, {
                    key: 'email',
                    value: 'Please enter valid email'
                }],
                "password": [{key: 'required', value: 'Password required'}, {
                    key: 'min',
                    value: 'Password length should be at least 6'
                }],
                "mobile_no": [{key: 'required', value: 'Mobile Number required'}, {key: 'min', value: 'Mobile Number must be 10 digits'}, {key: 'max', value: 'Mobile Number must be 10 digits'}],
                "profile": [{key: 'required', value: 'Profile Image required'}, {
                    key: 'size',
                    value: 'File size should be less than 4 MB!'
                }, {
                    key: 'ext',
                    value: 'Please upload jpeg,png,jpg,gif,svg format only'
                }],
                "gender": [{key: 'required', value: 'Gender required'}],
                "dob": [{key: 'required', value: 'Date of Birth required'}],
                "address": [{key: 'required', value: ['Address required']}],
                "country_id": [{key: 'required', value: 'Country required'}],
                "role_id": [{key: 'required', value: 'Role required'}],
                "state_id": [{key: 'required', value: 'State required'}],
                "city_id": [{key: 'required', value: 'City required'}],
                "gallery": [{key: 'required', value: 'Gallery required'}, {
                    key: 'size',
                    value: 'File size should be less than 4 MB!'
                }, {
                    key: 'ext',
                    value: 'Please upload jpeg,png,jpg,gif,svg format only'
                }],
                "hobby": [{key: 'url', value: 'Hobby required'}]
            },
            isSubmitting: false,
            dobMenu: false,
            todayDate: new Date().toISOString().slice(0,10),
            imageModal: false,
        };
    },
    computed: {
        ...mapState({
            model: state => state.userStore.model,
            isEditMode: state => state.userStore.editId > 0,
            snackbar: state => state.snackbarStore.snackbar,
            countryList: state => state.countryStore.countryList,
            cityList: state => state.cityStore.cityList,
            stateList: state => state.stateStore.stateList,
            hobbyList: state => state.hobbyStore.hobbyList,
            roleList: state => state.roleStore.roledropdownlist,
        }),
        computedDateFormatted () {
            return this.getDateFormat(this.model.dob)
        },
    },
    mixins: [CommonServices],
    methods: {
        /**
         * Register Submit Method
         */
        onSubmit() {
            this.$validator.validate().then(valid => {
                var self = this;
                if (valid) {
                    self.isSubmitting = true;
                    let formData = '';
                    var apiName = "register";
                    var editId = '';
                    var msgType= self.$getConst('REGISTER_SUCCESS');

                    formData = new FormData();
                    for (var key in self.model) {
                        formData.append(key, self.model[key]);
                    }

                    // for profile Image
                    formData.delete('profile');
                    if (self.model.profile_upload && self.model.profile_upload != null && self.model.profile_upload instanceof File) {
                        formData.append('profile', self.model.profile_upload);
                    }

                    // For Edit User
                    if (self.$store.state.userStore.editId > 0) {
                        apiName = "edit";
                        editId = self.$store.state.userStore.editId;
                        msgType= self.$getConst('UPDATE_ACTION');
                        // console.log(self.model);

                        for (var index in self.model.hobby) {
                            formData.append('hobby[' + parseInt(index) + ']', self.model.hobby[index]);
                        }

                        formData.delete('gallery');
                        for (var index2 in self.model.gallery) {
                            if (self.model.gallery[index2] instanceof File) {
                                formData.append('gallery[' + parseInt(index2) + ']', self.model.gallery[index2]);
                            }
                        }

                    } else {
                        // Register

                        // Multiple Gallery array
                        formData.delete('gallery');
                        if (self.model.gallery.length > 0) {
                            self.model.gallery.map(function (g) {
                                formData.append('gallery[]', g);
                            });
                        }

                        // Multiple Hobby array
                        formData.delete('hobby');
                        if (self.model.hobby.length > 0) {
                            self.model.hobby.map(function (h) {
                                formData.append('hobby[]', h);
                            });
                        }
                    }
                    self.$store.dispatch("userStore/" + apiName, {model: formData, editId: editId},
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }).then(response => {
                        if (response.error) {
                            self.isSubmitting = false;
                            self.errorMessage = response.data.error;
                        } else {
                            self.isSubmitting = false;
                            if (self.$store.state.userStore.editId > 0) {
                                // Emit while update
                                self.$emit('register-form-emit', response.data);
                            } else {
                               this.onCancel();
                            }
                            // Success message
                            self.$store.commit("snackbarStore/setMsg", msgType);
                        }
                    }, error => {
                        self.isSubmitting = false;
                        self.errorMessage = self.getAPIErrorMessage(error.response);
                    });
                }
            });
        },

        /**
         * For view and delete Gallery image
         */
        onImageModal(){
            this.imageModal = true;
        },

        /**
         * State filter from country
         */
        getState() {
            this.$store.commit("cityStore/setCityList", []);
            this.model.state_id = '';
            this.model.city_id = '';
            let filter = encodeURIComponent(JSON.stringify({"country_id": [this.model.country_id]}));
            this.$store.dispatch('stateStore/getAll', {page:1, limit:1000, filter:filter,query:''}).then(response => {
                if (response.error) {
                    this.errorMessage = response.data.error;
                } else {
                    //set state list
                    this.$store.commit("stateStore/setStateList", response.data.data);
                }
            }, function (error) {
                this.errorMessage = this.getAPIErrorMessage(error.response);
            });
        },

        /**
         * City filter from state
         */
        getCity() {
            this.model.city_id = '';
            let filter = encodeURIComponent(JSON.stringify({"state_id": [this.model.state_id]}));
            this.$store.dispatch('cityStore/getAll', {page:1, limit:1000, filter:filter,query:''}).then(response => {
                if (response.error) {
                    this.errorMessage = response.data.error;
                } else {
                    //set city list
                    this.$store.commit("cityStore/setCityList", response.data.data);
                }
            }, function (error) {
                this.errorMessage = this.getAPIErrorMessage(error.response);
            });
        },

        /**
         * Cancel button
         */
        onCancel() {
            // Reset data
            if (this.$store.state.userStore.editId > 0) {
                this.$emit('cancel');
            } else {
                this.onModalClear('userStore','clearStore');
            }

        }
    },
    mounted() {
        this.$store.commit("stateStore/setStateList", []);
        this.$store.commit("cityStore/setCityList", []);
        /**
         * Batch request of Treatment (Max 10 request is allowed at a time)
         */
        var api = 'getBatchRegister';
        var requestArray = [{
            "url": 'api/v1/countries?page=1&per_page=5000',
            "request_id": "countryList"
        }, {
            "url": "api/v1/hobbies?page=1&per_page=5000",
            "request_id": "hobbyList"
        }];
        if(this.isEditMode){
            requestArray.push({
                "url": "api/v1/roles?page=1&per_page=5000",
                "request_id": "roleList"
            });
            requestArray.push({
                "url": "api/v1/states?page=1&per_page=5000&filter="+encodeURIComponent(JSON.stringify({"country_id": [this.model.country_id]})),
                "request_id": "stateList"
            });
            requestArray.push({
                "url": "api/v1/cities?page=1&per_page=5000&filter="+encodeURIComponent(JSON.stringify({"state_id": [this.model.state_id]})),
                "request_id": "cityList"
            });
            api = 'getBatchUser';
        }
        this.$store.dispatch('batchRequestStore/'+api, requestArray).then(response => {
        }, error => {
            //Error Handling for batch request
            this.errorArr = this.getModalAPIerrorMessage(error);
            this.errorDialog = true;
        });

    },
};
