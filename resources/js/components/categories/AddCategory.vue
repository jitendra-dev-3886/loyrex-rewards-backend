<template>
    <v-dialog :value="value" :persistent="loading" @click:outside="onCancel()" @keydown.esc="onCancel()" content-class="modal-dialog">
        <v-card>
            <v-card-title
                class="headline black-bg"
                primary-title
            >
                {{isEditMode ? this.$getConst('TXT_UPDATE') : this.$getConst('TXT_ADD')}} Category
            </v-card-title>

            <v-card-text>
                <form method="POST" name="category-form" role="form" novalidate autocomplete="off" @submit.prevent="addAction">
                    <ErrorBlockServer :errorMessage="errorMessage"></ErrorBlockServer>
                    <v-layout row wrap class="display-block m-0 ">
                        <v-flex xs12>
                            <v-text-field
                                label="Category name *" type="text"
                                name="category"
                                v-model="model.name"
                                :error-messages="getErrorValue('category')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-select
                                clearable
                                v-model="model.parent_id"
                                name="parent_id"
                                item-text="name"
                                item-value="id"
                                :items="parentCategoryList"
                                label="Select parent category"
                            ></v-select>
                        </v-flex>
                        <v-flex xs12 class="mt-4">
                            <v-btn class="btn btn-primary" type="submit" :loading="loading">
                                {{isEditMode ?  $getConst('BTN_UPDATE') : $getConst('BTN_SUBMIT') }}
                            </v-btn>
                            <v-btn class="btn btn-grey ml-3" @click.native="onCancel" :disabled="loading">
                                {{ $getConst('BTN_CANCEL') }}
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script src="./add-category.js"></script>
