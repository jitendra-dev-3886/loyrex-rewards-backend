<template>
    <v-dialog
        :value="value"
        :persistent="loading"
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
        content-class="modal-dialog"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                {{
                    isEditMode
                        ? this.$getConst("TXT_UPDATE")
                        : this.$getConst("TXT_ADD")
                }}
                Admin
            </v-card-title>

            <v-card-text>
                <form
                    method="POST"
                    name="admin-form"
                    role="form"
                    novalidate
                    autocomplete="off"
                    @submit.prevent="addAction"
                >
                    <ErrorBlockServer
                        :errorMessage="errorMessage"
                    ></ErrorBlockServer>
                    <v-layout row wrap class="m-0">
                        <v-flex xs12 lg6>
                            <v-text-field
                                label="First name *"
                                type="text"
                                name="first_name"
                                v-model="model.first_name"
                                :error-messages="getErrorValue('first_name')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 lg6 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                label="Last name *"
                                type="text"
                                name="last_name"
                                v-model="model.last_name"
                                :error-messages="getErrorValue('last_name')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 lg6>
                            <v-flex xs12>
                                <v-text-field
                                    autocomplete="off"
                                    label="Email *"
                                    type="text"
                                    name="email"
                                    v-model="model.email"
                                    :loading="checkEmailLoading"
                                    :error-messages="getErrorValue('email')"
                                    v-validate="'required|email|emailcheck'"
                                ></v-text-field>
                            </v-flex>
                        </v-flex>
                        <v-flex xs12 lg6 class="pl-lg-4 pl-sm-0">
                            <v-flex xs12>
                                <!--<v-text-field
                                    autocomplete="off"
                                    label="Password *" type="password"
                                    name="password"
                                    v-model="model.password"
                                    :error-messages="getErrorValue('password')"
                                    v-validate=" isEditMode ? 'min:6' : 'required|min:6'"
                                ></v-text-field>-->
                                <v-text-field
                                    :type="show_password ? 'text' : 'password'"
                                    @click:append="
                                        show_password = !show_password
                                    "
                                    :append-icon="
                                        show_password
                                            ? 'visibility'
                                            : 'visibility_off'
                                    "
                                    label="Password *"
                                    id="password"
                                    name="password"
                                    maxlength="50"
                                    v-model.trim="model.password"
                                    :error-messages="getErrorValue('password')"
                                    v-validate="{
                                        required: isEditMode == false,
                                        min: 8,
                                        regex: password_rules
                                    }"
                                    autocomplete="new-password"
                                ></v-text-field>
                            </v-flex>
                        </v-flex>
                        <v-flex xs12 lg6>
                            <v-select
                                v-model="model.role_id"
                                name="role_id"
                                item-text="name"
                                item-value="id"
                                :items="roleList"
                                label="Role *"
                                :error-messages="getErrorValue('role_id')"
                                v-validate="'required'"
                            ></v-select>
                        </v-flex>
                        <v-flex xs12 lg6 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                label="Contact no. *"
                                type="text"
                                name="contact_number"
                                v-model.trim="model.contact_number"
                                :error-messages="
                                    getErrorValue('contact_number')
                                "
                                v-validate="'required|min:10|max:10'"
                            ></v-text-field>
                        </v-flex>

                        <v-flex xs12 class="mt-4">
                            <v-btn
                                class="btn btn-primary"
                                type="submit"
                                :loading="loading"
                            >
                                {{
                                    isEditMode
                                        ? $getConst("BTN_UPDATE")
                                        : $getConst("BTN_SUBMIT")
                                }}
                            </v-btn>
                            <v-btn
                                class="btn btn-grey ml-3"
                                @click.native="onCancel"
                                :disabled="loading"
                            >
                                {{ $getConst("BTN_CANCEL") }}
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script src="./add-admin.js"></script>
