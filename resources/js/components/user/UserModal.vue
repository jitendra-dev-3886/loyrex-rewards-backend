<template>
    <v-dialog
        :value="value"
        content-class="modal-lg modal-dialog"
        scrollable
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
    >
        <v-card>
            <v-card-title class="headline black-bg mb-4" primary-title>
                <span
                    >{{
                        isEditMode
                            ? this.$getConst("TXT_UPDATE")
                            : this.$getConst("TXT_CREATE")
                    }}
                    User</span
                >
                <v-spacer></v-spacer>
                <span v-if="isEditMode"
                    >Reward Points :
                    {{ calculatedRewardPoints | formatNumber }}</span
                >
            </v-card-title>

            <v-card-text>
                <v-form
                    class="form"
                    @submit.prevent="onSubmit"
                    method="POST"
                    role="form"
                    enctype="multipart/form-data"
                    autocomplete="off"
                >
                    <ErrorBlockServer
                        :errorMessage="errorMessage"
                    ></ErrorBlockServer>
                    <v-layout row wrap class="m-0">
                        <v-flex xs12 lg4>
                            <v-text-field
                                type="text"
                                name="first_name"
                                label="First name *"
                                v-model="model.first_name"
                                :error-messages="getErrorValue('first_name')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex lg4 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                type="text"
                                name="last_name"
                                label="Last name *"
                                v-model="model.last_name"
                                :error-messages="getErrorValue('last_name')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 lg4 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                type="text"
                                name="job_title"
                                label="Job title *"
                                v-model="model.job_title"
                                :error-messages="getErrorValue('job_title')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 lg4>
                            <v-text-field
                                label="Contact no *"
                                type="text"
                                name="contact_no"
                                :loading="checkEmailLoading"
                                v-model.trim="model.contact_number"
                                validate-on-blur
                                :error-messages="getErrorValue('contact_no')"
                                v-validate="
                                    'required|min:10|max:10|contact_check'
                                "
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 lg4 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                label="Email *"
                                type="text"
                                name="email"
                                :loading="checkEmailLoading"
                                v-model="model.email"
                                validate-on-blur
                                :error-messages="getErrorValue('email')"
                                v-validate="'required|email|email_check'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex
                            xs12
                            lg4
                            v-show="!isEditMode"
                            class="pl-lg-4 pl-sm-0"
                        >
                            <v-text-field
                                :type="show_password ? 'text' : 'password'"
                                @click:append="show_password = !show_password"
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
                                    required: isEditMode ? false : true,
                                    regex: isEditMode
                                        ? ''
                                        : /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{8,32}$)/
                                }"
                                autocomplete="new-password"
                            ></v-text-field>
                        </v-flex>
                        <!--   <v-flex xs12 lg4 :class="{'pl-lg-4 pl-sm-0': isEditMode}">
                            <v-autocomplete
                                :items="parentCategoryList"
                                item-value="id"
                                item-text="name"
                                v-model="model.category_id"
                                label="Select product category *"
                                name="category_id"
                                @change="getProductsByCategory"
                                :error-messages="getErrorValue('category_id')"
                                v-validate="'required'"
                                multiple
                            >
                                <template v-slot:prepend-item>
                                    <v-list-item
                                        ripple
                                        @click="toggle"
                                    >
                                        <v-list-item-action>
                                            <v-icon></v-icon>
                                            <v-icon :color="model.category_id.length > 0 ? 'indigo darken-4' : ''">{{ icon }}</v-icon>
                                        </v-list-item-action>
                                        <v-list-item-content>
                                            <v-list-item-title>Select All</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider class="mt-2"></v-divider>
                                </template>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 lg4 :class="{'pl-lg-4 pl-sm-0': !isEditMode}">
                            <v-autocomplete
                                :items="categoryProductList"
                                @change="onChangeProduct"
                                item-value="id"
                                item-text="name"
                                v-model="model.product_id"
                                label="Product mapping"
                                name="product_id"
                                multiple
                            >
                            </v-autocomplete>
                        </v-flex>
 -->
                        <v-flex
                            xs12
                            lg4
                            v-if="isEditMode"
                            class="pl-lg-4 pl-sm-0"
                        >
                            <v-radio-group
                                @change="onChangePoints(true)"
                                name="reward_point_type"
                                row
                                v-model="model.action_type"
                            >
                                <v-radio
                                    label="Credit Point"
                                    value="1"
                                ></v-radio>
                                <v-radio
                                    label="Debit Point"
                                    value="0"
                                ></v-radio>
                            </v-radio-group>
                        </v-flex>
                        <v-flex xs12 lg4>
                            <v-text-field
                                :label="
                                    isEditMode
                                        ? 'Reward Point'
                                        : 'Reward Point *'
                                "
                                type="number"
                                min="0"
                                name="reward_point"
                                class="user-group-height-reward"
                                v-validate="
                                    isEditMode
                                        ? 'decimal:0|min_value:0|max:6'
                                        : 'required|decimal:0|min_value:0|max:6'
                                "
                                @change="onChangePoints(false)"
                                :disabled="
                                    isEditMode ? model.action_type == '' : false
                                "
                                :error-messages="getErrorValue('reward_point')"
                                v-model="model.reward_points"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 lg4 class="pl-lg-4 pl-sm-0">
                            <v-autocomplete
                                :items="userGroupList"
                                item-text="name"
                                item-value="id"
                                clearable
                                v-model="model.userGroup_id"
                                label="Select user group*"
                                name="userGroupList"
                                class="user-group-height"
                                multiple
                                :error-messages="getErrorValue('userGroupList')"
                                v-validate="'required'"
                            >
                                <template v-slot:prepend-item>
                                    <v-list-item ripple @click="toggle_group">
                                        <v-list-item-action>
                                            <v-icon></v-icon>
                                            <v-icon
                                                :color="
                                                    model.userGroup_id.length >
                                                    0
                                                        ? 'indigo darken-4'
                                                        : ''
                                                "
                                                >{{ groupIcon }}</v-icon
                                            >
                                        </v-list-item-action>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Select All</v-list-item-title
                                            >
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider class="mt-2"></v-divider>
                                </template>
                            </v-autocomplete>
                        </v-flex>
                    </v-layout>

                    <!--begin::Action-->
                    <div class="mt-4">
                        <v-btn
                            class="btn btn-primary"
                            type="submit"
                            :loading="isSubmitting"
                            ref="submitBtn"
                            >{{
                                isEditMode
                                    ? $getConst("BTN_UPDATE")
                                    : $getConst("BTN_SUBMIT")
                            }}</v-btn
                        >
                        <v-btn @click="onCancel()" class="btn btn-grey ml-3">
                            {{ $getConst("BTN_CANCEL") }}
                        </v-btn>
                    </div>
                    <!--end::Action-->
                </v-form>
            </v-card-text>
        </v-card>
        <error-modal v-model="errorDialog" :errorArr="errorArr"></error-modal>
    </v-dialog>
</template>
<script src="./user-modal.js"></script>

<style>
.user-group-height .v-select__slot {
    height: 49px;
    overflow-y: auto;
    overflow-x: clip;
}
.user-group-height-reward .v-text-field__slot {
    height: 49px;
}
</style>
