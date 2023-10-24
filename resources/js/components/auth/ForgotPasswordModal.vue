<template>
    <v-dialog :value="value" content-class="modal-dialog" @click:outside="onCancel()" @keydown.esc="onCancel">

        <v-card>
            <v-card-title
                class="headline black-bg"
                primary-title
            >
                Forgot Password
            </v-card-title>

            <v-card-text>
                <form method="POST" name="forgotPassword" role="form" @submit.prevent="onSubmit" novalidate data-vv-scope="forgotPassword">
                    <ErrorBlockServer :errorMessage="errorMessage"></ErrorBlockServer>
                    <v-text-field
                                class="mt-4"
                                label="Authorised Email ID*" v-model="email"
                                :error-messages="getErrorValue('forgotPassword.email')"
                                name="email"
                                type="email"
                                maxlength="50"
                                v-validate="'required|email'"
                            ></v-text-field>
                    <v-btn class="btn btn-primary mt-4" :loading="isSubmittingForgotPassword" type="submit">{{$getConst('BTN_SUBMIT')}}</v-btn>
                    <v-btn color="" class="btn btn-grey ml-3 mt-4" @click="onCancel()">{{ $getConst('BTN_CANCEL') }}</v-btn>
                </form>
            </v-card-text>
        </v-card>

    </v-dialog>
</template>


<script src="./forgot-password.js"></script>
