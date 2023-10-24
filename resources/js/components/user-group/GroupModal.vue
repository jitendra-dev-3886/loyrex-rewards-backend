<template>
    <v-dialog :value="value" content-class="modal-dialog" scrollable @click:outside="onCancel()" @keydown.esc="onCancel()">
        <v-card>
            <v-card-title
                class="headline black-bg" primary-title>
                <span>{{isEditMode ? this.$getConst('TXT_UPDATE') : this.$getConst('TXT_CREATE')}} User Group</span>
                <v-spacer></v-spacer>
            </v-card-title>

            <v-card-text>
                <v-form class="form" @submit.prevent="onSubmit"  method="POST" role="form" enctype="multipart/form-data" autocomplete="off">
                    <ErrorBlockServer :errorMessage="errorMessage"></ErrorBlockServer>
                    <v-layout row wrap class="display-block m-0">
                        <v-flex xs12>
                            <v-text-field type="text"
                                          name="name"
                                          label="Group name *"
                                          v-model="userGroupmodel.name"
                                          :error-messages="getErrorValue('name')"
                                          v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-spacer></v-spacer>
                        <v-flex xs12>
                            <v-autocomplete
                                :items="CatalogueList"
                                item-text="name"
                                item-value="id"
                                v-model="userGroupmodel.catalogue_id"
                                label="Select Catalogues *"
                                name="CatalogueList"
                                multiple
                                :error-messages="getErrorValue('CatalogueList')"
                                v-validate="'required'"
                            >
                                <template v-slot:prepend-item>
                                    <v-list-item
                                        ripple
                                        @click="toggle_catalogue"
                                    >
                                        <v-list-item-action>
                                            <v-icon></v-icon>
                                            <v-icon :color="userGroupmodel.catalogue_id && userGroupmodel.catalogue_id.length > 0 ? 'indigo darken-4' : ''">{{ icon }}</v-icon>
                                        </v-list-item-action>
                                        <v-list-item-content>
                                            <v-list-item-title>Select All</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider class="mt-2"></v-divider>
                                </template>
                            </v-autocomplete>
                        </v-flex>

                        <!--                        <v-flex xs12 lg4 :class="{'pl-lg-4 pl-sm-0': isEditMode}">
                                                    <v-autocomplete
                                                        :items="parentCategoryList"
                                                        item-value="id"
                                                        item-text="name"
                                                        v-model="model.category_id"
                                                        label="Select product category *"
                                                        name="category_id"
                                                        @change="getCatalogue()"
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
                                                </v-flex>-->
                        <!--                        <v-flex xs12 lg4 :class="{'pl-lg-4 pl-sm-0': !isEditMode}">-->
                        <!--                            <v-autocomplete-->
                        <!--                                :items="categoryProductList"-->
                        <!--                                @change="onChangeProduct"-->
                        <!--                                item-value="id"-->
                        <!--                                item-text="name"-->
                        <!--                                v-model="model.product_id"-->
                        <!--                                label="Product mapping"-->
                        <!--                                name="product_id"-->
                        <!--                                multiple-->
                        <!--                            >-->
                        <!--                            </v-autocomplete>-->
                        <!--                        </v-flex>-->
                        <v-spacer></v-spacer>
                        <!--                        <v-flex xs12 lg4 :class="{'pl-lg-4 pl-sm-0': isEditMode}">
                                                    <v-autocomplete
                                                        :items="userGroupList"
                                                        item-value="users"
                                                        item-text="users"
                                                        v-model="userGroupmodel.users"
                                                        label="Select users"
                                                        name="users"
                                                        @change="getCatalogue"
                                                        multiple
                                                    >
                                                        <template v-slot:prepend-item>
                                                            <v-list-item
                                                                ripple
                                                                @click="toggle_catalogue"
                                                            >
                                                                <v-list-item-action>
                                                                    <v-icon></v-icon>
                                                                    <v-icon :color="userGroupmodel.users > 0 ? 'indigo darken-4' : ''">{{ icon }}</v-icon>
                                                                </v-list-item-action>
                                                                <v-list-item-content>
                                                                    <v-list-item-title>Select All</v-list-item-title>
                                                                </v-list-item-content>
                                                            </v-list-item>
                                                            <v-divider class="mt-2"></v-divider>
                                                        </template>
                                                    </v-autocomplete>
                                                </v-flex>-->
                    </v-layout>

                    <!--begin::Action-->
                    <div xs12 class="mt-4">
                        <v-btn class="btn btn-primary" type="submit"
                               :loading="isSubmitting" ref="submitBtn">{{isEditMode ?  $getConst('BTN_UPDATE') : $getConst('BTN_SUBMIT') }}</v-btn>
                        <v-btn @click="onCancel()"
                               class="btn btn-grey ml-3">
                            {{ $getConst('BTN_CANCEL') }}
                        </v-btn>
                    </div>
                    <!--end::Action-->
                </v-form>
            </v-card-text>
        </v-card>
        <error-modal v-model="errorDialog" :errorArr="errorArr"></error-modal>
    </v-dialog>
</template>
<script src="./usergroup-modal.js"></script>
