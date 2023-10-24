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
                    Voucher</span
                >
                <v-spacer></v-spacer>
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
                        <v-flex xs12 lg6>
                            <v-text-field
                                type="text"
                                name="name"
                                label="Voucher name *"
                                v-model="model.name"
                                :error-messages="getErrorValue('name')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs6 lg3 class="pl-lg-4 pl-sm-0">
                            <v-menu
                                v-model="menu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="unset"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="computedValidityDateFormatted"
                                        label="Valid till *"
                                        readonly
                                        v-on="on"
                                        name="valid_till"
                                        :disabled="model.validity_status == '1'"
                                        v-validate="
                                            model.validity_status == '0'
                                                ? 'required'
                                                : ''
                                        "
                                        :error-messages="
                                            getErrorValue('valid_till')
                                        "
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    :min="getCurrentDatePickerFormat()"
                                    v-model="tempValidityDate"
                                    @input="menu = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs6 lg2 class="pl-2">
                            <v-checkbox
                                label="No validity"
                                name="no_validity"
                                true-value="1"
                                false-value="0"
                                @change="
                                    () => {
                                        tempValidityDate =
                                            model.validity_status == '1'
                                                ? ''
                                                : tempValidityDate;
                                    }
                                "
                                v-model="model.validity_status"
                            ></v-checkbox>
                        </v-flex>
                        <v-flex xs12 lg6>
                            <v-select
                                :items="voucherTypes"
                                item-value="id"
                                item-text="name"
                                v-model="model.voucher_type"
                                label="Select voucher type *"
                                name="voucher_type"
                                v-validate="'required'"
                                :error-messages="getErrorValue('voucher_type')"
                            >
                            </v-select>
                        </v-flex>
                        <v-flex
                            xs12
                            lg6
                            class="pl-lg-4 pl-sm-0"
                            v-show="model.voucher_type == '1'"
                        >
                            <v-text-field
                                type="number"
                                name="points"
                                label="Points *"
                                min="0"
                                v-model="model.points"
                                :error-messages="getErrorValue('points')"
                                v-validate="
                                    model.voucher_type == '1'
                                        ? 'required|decimal|min_value:0'
                                        : ''
                                "
                            ></v-text-field>
                        </v-flex>
                        <v-flex
                            xs12
                            lg6
                            v-show="model.voucher_type == '0'"
                            :class="{
                                'pl-lg-4 pl-sm-0': model.voucher_type == '0'
                            }"
                        >
                            <v-select
                                :items="parentCategoryList"
                                item-value="id"
                                item-text="name"
                                v-model="model.category_id"
                                label="Select product category *"
                                name="category_id"
                                @change="getProductsByCategory"
                                :error-messages="getErrorValue('category_id')"
                                v-validate="
                                    model.voucher_type == '0' ? 'required' : ''
                                "
                            >
                            </v-select>
                        </v-flex>

                        <v-flex
                            xs12
                            lg6
                            :class="{
                                'pl-lg-4 pl-sm-0': model.voucher_type == '1'
                            }"
                            v-show="model.voucher_type == '0'"
                        >
                            <v-select
                                :items="categoryProductList"
                                item-value="id"
                                item-text="name"
                                v-model="model.product_id"
                                label="Select product *"
                                name="product_id"
                                :error-messages="getErrorValue('product_id')"
                                v-validate="
                                    model.voucher_type == '0' ? 'required' : ''
                                "
                            >
                            </v-select>
                        </v-flex>

                        <v-flex
                            xs12
                            lg6
                            :class="{
                                'pl-lg-4 pl-sm-0':
                                    model.voucher_type == '0' ||
                                    model.voucher_type == ''
                            }"
                        >
                            <v-combobox
                                :items="userList"
                                item-value="id"
                                v-model="model.user"
                                @change="assignUserChange"
                                label="Assign user *"
                                :loading="isLoadingUsers"
                                :search-input.sync="search"
                                name="user_id"
                                placeholder="Type name, contact no. or email to search users.."
                                :error-messages="getErrorValue('user_id')"
                                v-validate="'required'"
                                no-filter
                            >
                                <template v-slot:selection="data">
                                    {{ data.item | getFullName }}
                                </template>
                                <template v-slot:item="data">
                                    <v-list-item-content class="pa-1">
                                        <v-list-item-title class="text-wrap"
                                            >{{ data.item | getFullName }}
                                        </v-list-item-title>
                                        <v-list-item-subtitle>{{
                                            data.item.email
                                        }}</v-list-item-subtitle>
                                        <v-list-item-subtitle>{{
                                            data.item.contact_number
                                        }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                </template>
                            </v-combobox>
                        </v-flex>
                        <v-flex
                            xs12
                            lg6
                            :class="{
                                'pl-lg-4 pl-sm-0': model.voucher_type == '1'
                            }"
                            v-if="!isEditMode"
                        >
                            <v-text-field
                                type="text"
                                name="no_of_vouchers"
                                label="No of voucher *"
                                v-model.trim="model.no_of_vouchers"
                                :disabled="model.user && model.user.id != '0'"
                                :error-messages="
                                    getErrorValue('no_of_vouchers')
                                "
                                v-validate="
                                    model.user && model.user.id != '0'
                                        ? ''
                                        : 'required|decimal|min_value:1|max_value:100|numeric'
                                "
                            ></v-text-field>
                        </v-flex>
                        <v-flex
                            xs12
                            lg6
                            :class="{
                                'pl-lg-4 pl-sm-0':
                                    (model.voucher_type == '0' &&
                                        !isEditMode) ||
                                    model.voucher_type == '' ||
                                    (model.voucher_type == '1' && isEditMode)
                            }"
                        >
                            <v-text-field
                                type="text"
                                name="contact_number"
                                label="Contact No"
                                v-model="model.contact_number"
                                :error-messages="
                                    getErrorValue('contact_number')
                                "
                                v-validate="'min:10|max:10'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex
                            xs12
                            lg6
                            :class="{
                                'pl-lg-4 pl-sm-0':
                                    (model.voucher_type == '1' &&
                                        !isEditMode) ||
                                    (model.voucher_type == '0' && isEditMode)
                            }"
                        >
                            <v-select
                                :items="statusTypes"
                                item-value="id"
                                item-text="name"
                                v-model="model.status"
                                label="Voucher status *"
                                :disabled="
                                    model.voucher_redeem == '1' ? true : false
                                "
                                name="voucher_status"
                                @change="getProductsByCategory"
                                :error-messages="
                                    getErrorValue('voucher_status')
                                "
                                v-validate="'required'"
                            >
                            </v-select>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea
                                label="Description"
                                id="description"
                                type="text"
                                autocomplete="off"
                                name="description"
                                maxlength="500"
                                v-model="model.description"
                            ></v-textarea>
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
                            }}
                        </v-btn>
                        <v-btn @click="onCancel()" class="btn btn-grey ml-3">
                            {{ $getConst("BTN_CANCEL") }}
                        </v-btn>
                    </div>
                    <!--end::Action-->
                </v-form>
            </v-card-text>
            <error-modal
                v-model="errorDialog"
                :errorArr="errorArr"
            ></error-modal>
            <voucher-links-modal
                v-model="voucherLinksModal"
                :voucher-detail="voucherDetail"
            ></voucher-links-modal>
        </v-card>
    </v-dialog>
</template>
<script src="./voucher-modal.js"></script>
