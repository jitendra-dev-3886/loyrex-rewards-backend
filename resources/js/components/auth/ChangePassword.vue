<template>
    <v-dialog
        :value="value"
        @input="onCancel"
        @keydown.esc="onCancel"
        @click:outside="onCancel"
        content-class="modal-dialog modal-"
        max-width="320"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                Change Password
            </v-card-title>

            <v-card-text>
                <form
                    method="POST"
                    name="changePassword"
                    role="form"
                    @submit.prevent="onSubmit"
                >
                    <ErrorBlockServer
                        :errorMessage="errorMessage"
                    ></ErrorBlockServer>
                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex lg12>
                            <v-text-field
                                :type="show_old_password ? 'text' : 'password'"
                                @click:append="
                                    show_old_password = !show_old_password
                                "
                                :append-icon="
                                    show_old_password
                                        ? 'visibility'
                                        : 'visibility_off'
                                "
                                label="Current Password*"
                                id="old_password"
                                class=""
                                name="old_password"
                                maxlength="50"
                                v-model.trim="model.old_password"
                                :error-messages="getErrorValue('old_password')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex lg12>
                            <v-text-field
                                :type="show_new_password ? 'text' : 'password'"
                                @click:append="
                                    show_new_password = !show_new_password
                                "
                                :append-icon="
                                    show_new_password
                                        ? 'visibility'
                                        : 'visibility_off'
                                "
                                label="New Password*"
                                id="new_password"
                                class=""
                                name="new_password"
                                maxlength="50"
                                v-model.trim="model.new_password"
                                :error-messages="getErrorValue('new_password')"
                                v-validate="{
                                    required: true,
                                    regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{8,32}$)/
                                }"
                                ref="password"
                            ></v-text-field>
                        </v-flex>
                        <v-flex lg12>
                            <v-text-field
                                :type="
                                    show_new_confirmation_password
                                        ? 'text'
                                        : 'password'
                                "
                                @click:append="
                                    show_new_confirmation_password = !show_new_confirmation_password
                                "
                                :append-icon="
                                    show_new_confirmation_password
                                        ? 'visibility'
                                        : 'visibility_off'
                                "
                                label="Confirm New Password*"
                                id="confirm_password"
                                class=""
                                autocomplete="off"
                                name="confirm_password"
                                maxlength="50"
                                v-model.trim="model.confirm_password"
                                :error-messages="
                                    getErrorValue('confirm_password')
                                "
                                v-validate="'required|confirmed:password'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-btn
                                class="btn btn-primary mt-4"
                                type="submit"
                                :loading="isSubmitting"
                                >{{ $getConst("BTN_SUBMIT") }}</v-btn
                            >
                            <v-btn
                                class="btn btn-grey mt-4 ml-3"
                                @click="onCancel"
                                >{{ $getConst("BTN_CANCEL") }}</v-btn
                            >
                        </v-flex>
                    </v-layout>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script src="./change-password.js"></script>
