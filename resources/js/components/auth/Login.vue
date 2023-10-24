<template>
    <div>
        <!--begin::Signin-->
        <div class="login-form login-signin">
            <div class="text-center mb-10 mb-lg-20">
                <h3 class="font-size-h1">Happy to see you!</h3>
                <h5 class="text-muted font-weight-semi-bold">
                    You're just a login away from creating a new campaign
                </h5>
            </div>

            <!--begin::Form-->
            <v-form
                class="form"
                role="form"
                @submit.prevent="onSubmit"
                novalidate
                autocomplete="off"
                data-vv-scope="loginform"
            >
                <v-layout row wrap class="display-block m-0">
                    <ErrorBlockServer
                        :errorMessage="errorMessage"
                    ></ErrorBlockServer>
                    <v-flex xs12>
                        <v-text-field
                            autofocus
                            label="Authorised Email ID*"
                            type="text"
                            name="email"
                            v-model="loginDetail.email"
                            :error-messages="getErrorValue('loginform.email')"
                            v-validate="'required|email'"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                        <v-text-field
                            label="Password*"
                            type="password"
                            name="password"
                            v-model.trim="loginDetail.password"
                            :error-messages="
                                getErrorValue('loginform.password')
                            "
                            v-validate="'required|min:6'"
                            :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                            :type="showPassword ? 'text' : 'password'"
                            @click:append="showPassword = !showPassword"
                        ></v-text-field>
                    </v-flex>
                </v-layout>

                <!--begin::Action-->
                <div class="form-group">
                    <v-layout>
                        <v-flex xs12>
                            <a
                                class="text-dark-60 text-hover-primary my-3 mr-2 float-right mt-0"
                                id="kt_login_forgot"
                                @click="forgotPasswordDialog = true"
                            >
                                Forgot Password?
                            </a>
                        </v-flex>
                    </v-layout>

                    <v-btn
                        ref="kt_login_signin_submit"
                        class="btn w100 btn-primary float-right mr-0 mt-5 font-weight-bold px-9 py-4 my-3 font-size-3 mx-4"
                        type="submit"
                        :loading="isSubmitting"
                    >
                        Submit
                    </v-btn>
                    <!--                    <v-btn class="btn btn-primary" type="submit" :loading="isSubmitting">{{// $getConst('BTN_SUBMIT')}}</v-btn>-->
                </div>
                <!--end::Action-->
            </v-form>
            <!--end::Form-->
        </div>
        <!--end::Signin-->
        <forgot-password-modal
            v-model="forgotPasswordDialog"
        ></forgot-password-modal>
        <snackbar v-model="snackbar"></snackbar>
        <permission-dialog v-model="permissionDialog"></permission-dialog>
    </div>
</template>

<script src="./login.js"></script>
