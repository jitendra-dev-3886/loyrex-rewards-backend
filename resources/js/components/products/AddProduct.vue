<template>
    <v-dialog
        :value="value"
        :persistent="loading"
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
        content-class="modal-lg modal-dialog"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                {{
                    isEditMode
                        ? this.$getConst("TXT_UPDATE")
                        : this.$getConst("TXT_ADD")
                }}
                Product Catalogue
            </v-card-title>

            <v-card-text>
                <form
                    method="POST"
                    name="product-form"
                    role="form"
                    novalidate
                    autocomplete="off"
                    @submit.prevent="onSubmit"
                >
                    <ErrorBlockServer
                        :errorMessage="errorMessage"
                    ></ErrorBlockServer>
                    <v-layout row wrap class="m-0">
                        <v-flex xs12 lg4>
                            <v-select
                                clearable
                                v-model="model.category_id"
                                name="category_id"
                                item-text="name"
                                item-value="id"
                                :items="parentCategoryList"
                                label="Select Category *"
                                :error-messages="getErrorValue('category_id')"
                                @change="getSubCategory('addProduct')"
                                v-validate="'required'"
                            ></v-select>
                        </v-flex>
                        <v-flex xs12 lg4 class="pl-lg-4 pl-sm-0">
                            <v-select
                                clearable
                                v-model="model.sub_category_id"
                                name="sub_category_id"
                                item-text="name"
                                item-value="id"
                                :items="subCategoryList"
                                label="Sub Category"
                                :loading="isSubCategoryLoading"
                                :error-messages="
                                    getErrorValue('sub_category_id')
                                "
                            ></v-select>
                        </v-flex>
                        <v-flex xs12 lg4 class="pl-lg-4 pl-sm-0">
                            <v-autocomplete
                                :items="catalogueList"
                                item-value="id"
                                item-text="name"
                                v-model="model.catalogue_id"
                                label="Select Catalogues *"
                                name="catalogue_id"
                                :error-messages="getErrorValue('catalogue_id')"
                                v-validate="'required'"
                                multiple
                            >
                                <template v-slot:prepend-item>
                                    <v-list-item ripple @click="toggle">
                                        <v-list-item-action>
                                            <v-icon></v-icon>
                                            <v-icon
                                                :color="
                                                    model.catalogue_id &&
                                                    model.catalogue_id.length >
                                                        0
                                                        ? 'indigo darken-4'
                                                        : ''
                                                "
                                                >{{ icon }}</v-icon
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
                        <v-flex xs12 lg4>
                            <v-select
                                clearable
                                v-model="model.available_status"
                                name="available_status"
                                item-text="name"
                                item-value="id"
                                :items="availabilityList"
                                label="Availability *"
                                :error-messages="
                                    getErrorValue('available_status')
                                "
                                v-validate="'required'"
                            ></v-select>
                        </v-flex>
                        <v-flex xs12 lg4 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                label="Product name *"
                                type="text"
                                name="name"
                                v-model="model.name"
                                :error-messages="getErrorValue('name')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 lg4 class="pl-lg-4 pl-sm-0">
                            <v-select
                                clearable
                                v-model="model.brand_id"
                                name="brand_id"
                                item-text="name"
                                item-value="id"
                                :items="brandList"
                                label="Brand Name *"
                                :error-messages="getErrorValue('brand_id')"
                                v-validate="'required'"
                            ></v-select>
                        </v-flex>
                        <v-flex xs12 lg4>
                            <v-text-field
                                label="Points *"
                                type="number"
                                name="point"
                                v-model="model.point"
                                :error-messages="getErrorValue('point')"
                                v-validate="'required|min_value:0'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 lg8 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                label="Description *"
                                type="text"
                                name="description"
                                v-model="model.description"
                                :error-messages="getErrorValue('description')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 :class="isEditMode ? 'lg12' : 'lg12 pt-2'">
                            <v-layout row wrap class="m-0">
                                <v-flex xs12 v-if="!isEditMode">
                                    <label for="featured_image"
                                        >Featured Image*</label
                                    >
                                    <VDropZone
                                        v-model="model.featured_image_key"
                                        :maxFilesize="1"
                                        :limit="1"
                                        id="featured_image_key"
                                        type="file"
                                        name="featured_image_key"
                                        ref="featured_image_key"
                                        :label="
                                            'Max file upload: 1 / Max file size: 4MB'
                                        "
                                        v-validate="
                                            isEditMode ? '' : 'required'
                                        "
                                        @is-uploading="isUploading = $event"
                                        @removed-file="
                                            removeFeatureImage(
                                                'featured_image_key'
                                            )
                                        "
                                        @upload-success="
                                            singleImageUploadSuccess(
                                                $event,
                                                'featured_image_key'
                                            )
                                        "
                                    >
                                    </VDropZone>
                                </v-flex>

                                <!-- <template
                                    row
                                    wrap
                                    v-if="
                                        model.featured_image != '' && isEditMode
                                    "
                                >
                                    <v-flex lg6>
                                        Existing Featured Image : </v-flex
                                    ><v-flex lg6>
                                        <a
                                            :href="model.featured_image"
                                            target="_blank"
                                            ><img
                                                :src="model.featured_image"
                                                width="auto"
                                                height="70"
                                                class="mb-4"
                                        /></a>
                                    </v-flex>
                                </template> -->
                            </v-layout>
                        </v-flex>

                        <v-flex xs12 :class="isEditMode ? 'lg12' : 'lg12 pt-2'">
                            <v-layout row wrap class="m-0">
                                <v-flex xs12 v-if="!isEditMode">
                                    <label for="upload_images"
                                        >Upload Images*</label
                                    >
                                    <VDropZone
                                        v-model="multipleImages"
                                        :maxFilesize="4"
                                        type="file"
                                        :upload-multiple="true"
                                        :limit="4"
                                        :alreadyUploaded="alreadyUploadedImages"
                                        id="upload_images"
                                        name="upload_images"
                                        ref="upload_images"
                                        :label="
                                            'Max file uploads: 4 / Max file size: 4MB'
                                        "
                                        v-validate="
                                            isEditMode ? '' : 'required'
                                        "
                                        @is-uploading="isUploading = $event"
                                        @removed-file="
                                            removeMultiUploadedImages($event)
                                        "
                                        @upload-success="
                                            multiImageUploadSuccess(
                                                $event,
                                                'upload_images'
                                            )
                                        "
                                    >
                                    </VDropZone>
                                </v-flex>
                                <!-- <v-flex xs12 v-if="isEditMode" class="mb-3">
                                    <a @click="onImageModal()"
                                        >Click here to view Upload Images</a
                                    >
                                </v-flex> -->
                            </v-layout>
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
        <upload-image-modal
            :product_id="editId"
            v-model="imageModal"
        ></upload-image-modal>
    </v-dialog>
</template>

<script src="./add-product.js"></script>
