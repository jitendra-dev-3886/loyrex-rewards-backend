<template>
    <div>
        <!--begin::Content header-->
        <!--end::Content header-->

        <!--begin::Signin-->
        <div class="login-form login-signin">
            <div class="text-center mb-10 mb-lg-20">
                <h3 class="font-size-h1">Log In</h3>
                <p class="text-muted font-weight-semi-bold">
                    You have been logged off. Please enter your password to get Login again.
                </p>
            </div>


            <!--begin::Form-->
            <v-form class="form" @submit.prevent="onSubmit" novalidate autocomplete="off">
                <ErrorBlockServer :errorMessage="errorMessage"></ErrorBlockServer>
                <v-layout row wrap class="display-block m-0">
                    <div class="text-center">
                        <!--<img :src="userProfilePicture" class="img-responsive rounded-circle logoff-user">-->
                        <h3 class="logoff-user-name mt-3">{{userFullName}}</h3>
                        <v-text-field
                                label="Password*" type="password"
                                name="password"
                                v-model.trim="loginDetail.password"
                                :error-messages="getErrorValue('password')"
                                v-validate="'required|min:6'"
                                :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                                :type="showPassword ? 'text' : 'password'"
                                @click:append="showPassword = !showPassword"
                        ></v-text-field>
                    </div>
                </v-layout>

                <!--begin::Action-->
                <div class="form-group d-flex flex-wrap flex-center">
                    <v-layout>
                        <v-flex xs12>
                            <router-link
                                id="kt_login_forgot"
                                class="text-dark-60 text-hover-primary
                                        my-3 mr-2 float-right mt-0"
                                :to="{ name: 'login' }"
                            >
                            Logout?
                            </router-link>
                        </v-flex>
                    </v-layout>
                    <v-btn ref="kt_login_signin_submit" class="btn btn-primary w100 font-weight-bold px-9 py-4 my-3 font-size-3 mx-4" type="submit" :loading="isSubmitting">
                        Continue
                    </v-btn>
                    <!--                    <v-btn class="btn btn-primary" type="submit" :loading="isSubmitting">{{// $getConst('BTN_SUBMIT')}}</v-btn>-->
                </div>
                <!--end::Action-->
            </v-form>
            <!--end::Form-->
        </div>
        <!--end::Signin-->
        <snackbar v-model="snackbar"></snackbar>
        <error-modal
            v-model="errorDialog"
            :error-arr="errorArr"
        />
    </div>
</template>

<style lang="scss" scoped>
    .spinner.spinner-right {
        padding-right: 3.5rem !important;
    }
</style>


<script src="./logoff.js"></script>
