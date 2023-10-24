<template>
    <v-dialog
        persistent
        :value="value"
        content-class="modal-dialog modal-lg ps-product-detail-modal"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                <h4 class="m-t-10 m-b-20">
                    Edit Images - {{ detailViewData.name }}
                </h4>
            </v-card-title>

            <v-card-text>
                <v-tabs v-model="model1">
                    <v-tab ripple href="#tab1">
                        Featured Image
                        {{ detailViewData.featured_image != "" ? 1 : 0 }}/1
                    </v-tab>
                    <v-tab ripple href="#tab2">
                        Additional Images
                        {{ detailViewData.upload_images.length }}/4
                    </v-tab>
                </v-tabs>

                <v-tabs-items v-model="model1">
                    <v-tab-item value="tab1">
                        <v-card flat>
                            <v-card-text>
                                <div class="box-padd">
                                    <form
                                        method="POST"
                                        name="addFeatureImage"
                                        id="addFeatureImage"
                                        role="form"
                                        data-vv-scope="addFeatureImage"
                                        @submit.prevent="uploadImages(true)"
                                    >
                                        <v-layout row wrap>
                                            <v-flex xs12 sm10 lg10>
                                                <VDropZone
                                                    v-model="featured_image_key"
                                                    :maxFilesize="1"
                                                    id="featured_image_key"
                                                    name="featured_image_key"
                                                    ref="featured_image_key"
                                                    :limit="1"
                                                    :alreadyUploaded="
                                                        isFeatureImageDeleted
                                                            ? 0
                                                            : 1
                                                    "
                                                    :label="
                                                        'Featured Image* Max size: 4MB'
                                                    "
                                                    v-validate="'required'"
                                                    dataVVScope="addFeatureImage."
                                                    @is-uploading="
                                                        isUploading = $event
                                                    "
                                                    @removed-file="
                                                        removeFeatureImage(
                                                            'featured_image_key'
                                                        )
                                                    "
                                                    @upload-success="
                                                        singleImageUploadSuccess(
                                                            $event
                                                        )
                                                    "
                                                >
                                                </VDropZone>
                                            </v-flex>
                                            <v-flex xs12 sm2 lg2 class="pl-2">
                                                <v-btn
                                                    color="primary"
                                                    type="submit"
                                                    v-uploadImage="
                                                        $getConst('PRODUCT')
                                                    "
                                                    class="btn btn-theme float-right float-xs-none w100 m-r-0"
                                                    :loading="addImagesLoading"
                                                    :disabled="isUploading"
                                                    >Upload Image
                                                </v-btn>
                                            </v-flex>

                                            <template
                                                v-if="!isFeatureImageDeleted"
                                            >
                                                <div
                                                    class="table-responsive table table-cp scrollbar height22 pd-images-table"
                                                >
                                                    <table class="table">
                                                        <thead>
                                                            <tr
                                                                class="no-border"
                                                            >
                                                                <th>
                                                                    Existing
                                                                    Featured
                                                                    Image
                                                                </th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <a
                                                                        :href="
                                                                            detailViewData.featured_image
                                                                        "
                                                                        target="_blank"
                                                                        ><img
                                                                            :src="
                                                                                detailViewData.featured_image
                                                                            "
                                                                            width="auto"
                                                                            height="100"
                                                                            class="mb-4"
                                                                    /></a>
                                                                </td>
                                                                <td>
                                                                    <v-tooltip
                                                                        bottom
                                                                    >
                                                                        <template
                                                                            v-slot:activator="{
                                                                                on,
                                                                                attrs
                                                                            }"
                                                                        >
                                                                            <v-icon
                                                                                small
                                                                                v-on="
                                                                                    on
                                                                                "
                                                                                class="mr-2"
                                                                                @click="
                                                                                    confirmDelete(
                                                                                        false,
                                                                                        false,
                                                                                        true
                                                                                    )
                                                                                "
                                                                                v-deleteImage="
                                                                                    $getConst(
                                                                                        'PRODUCT'
                                                                                    )
                                                                                "
                                                                            >
                                                                                {{
                                                                                    icons.mdiDelete
                                                                                }}
                                                                            </v-icon>
                                                                        </template>
                                                                        <span>{{
                                                                            $getConst(
                                                                                "DELETE_TOOLTIP"
                                                                            )
                                                                        }}</span>
                                                                    </v-tooltip>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </template>
                                        </v-layout>
                                    </form>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item value="tab2">
                        <v-card flat>
                            <v-card-text>
                                <div class="box-padd">
                                    <form
                                        method="POST"
                                        name="addMultipleImages"
                                        id="addMultipleImages"
                                        role="form"
                                        data-vv-scope="addMultipleImages"
                                        @submit.prevent="uploadImages(false)"
                                    >
                                        <ErrorBlockServer
                                            :errorMessage="errorMessage"
                                        ></ErrorBlockServer>
                                        <v-layout
                                            row
                                            wrap
                                            v-if="
                                                detailViewData.upload_images &&
                                                    detailViewData.upload_images
                                                        .length < 5
                                            "
                                        >
                                            <v-flex xs12 sm10 lg10>
                                                <VDropZone
                                                    v-model="
                                                        productImages.images
                                                    "
                                                    :maxFilesize="4"
                                                    :upload-multiple="true"
                                                    :limit="4"
                                                    :alreadyUploaded="
                                                        detailViewData
                                                            .upload_images
                                                            .length
                                                    "
                                                    id="upload_images"
                                                    name="upload_images"
                                                    ref="upload_images"
                                                    :label="
                                                        'Upload Image* Max size: 4MB'
                                                    "
                                                    v-validate="'required'"
                                                    dataVVScope="addMultipleImages."
                                                    @is-uploading="
                                                        isUploading = $event
                                                    "
                                                    @removed-file="
                                                        removemultiUploadImage(
                                                            $event
                                                        )
                                                    "
                                                    @upload-success="
                                                        multiImageUploadSuccess(
                                                            $event
                                                        )
                                                    "
                                                >
                                                </VDropZone>
                                            </v-flex>
                                            <v-flex xs12 sm2 lg2 class="pl-2">
                                                <v-btn
                                                    color="primary"
                                                    type="submit"
                                                    v-uploadImage="
                                                        $getConst('PRODUCT')
                                                    "
                                                    class="btn btn-theme float-right float-xs-none w100 m-r-0"
                                                    :loading="addImagesLoading"
                                                    :disabled="isUploading"
                                                    >Upload Images
                                                </v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </form>

                                    <div
                                        class="table-responsive table table-cp scrollbar height22 pd-images-table"
                                    >
                                        <table class="table">
                                            <thead>
                                                <tr class="no-border">
                                                    <th>Image</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                v-if="
                                                    detailViewData &&
                                                        detailViewData.upload_images
                                                "
                                            >
                                                <template
                                                    v-if="
                                                        detailViewData
                                                            .upload_images
                                                            .length != 0
                                                    "
                                                >
                                                    <tr
                                                        v-for="(img,
                                                        index) in detailViewData.upload_images"
                                                    >
                                                        <td>
                                                            <a
                                                                :href="
                                                                    img.filename
                                                                "
                                                                target="_blank"
                                                                ><img
                                                                    style="max-width: 120px;"
                                                                    class="img-responsive"
                                                                    :src="
                                                                        img.filename
                                                                    "
                                                            /></a>
                                                        </td>
                                                        <td style="width:10%;">
                                                            <v-tooltip bottom>
                                                                <template
                                                                    v-slot:activator="{
                                                                        on
                                                                    }"
                                                                >
                                                                    <v-icon
                                                                        small
                                                                        v-on="
                                                                            on
                                                                        "
                                                                        class="mr-2"
                                                                        @click="
                                                                            confirmDelete(
                                                                                img.id,
                                                                                index,
                                                                                false
                                                                            )
                                                                        "
                                                                        v-deleteImage="
                                                                            $getConst(
                                                                                'PRODUCT'
                                                                            )
                                                                        "
                                                                    >
                                                                        {{
                                                                            icons.mdiDelete
                                                                        }}
                                                                    </v-icon>
                                                                </template>
                                                                <span>{{
                                                                    $getConst(
                                                                        "DELETE_TOOLTIP"
                                                                    )
                                                                }}</span>
                                                            </v-tooltip>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <tr v-else>
                                                    <td
                                                        colspan="2"
                                                        style="text-align: center"
                                                    >
                                                        {{
                                                            $getConst(
                                                                "NO_DATA_MSG"
                                                            )
                                                        }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                </v-tabs-items>

                <v-btn
                    color="secondary"
                    class="btn btn-grey m-b-10 m-t-10"
                    @click="onCancel"
                    >Cancel</v-btn
                >
            </v-card-text>
        </v-card>
        <error-modal v-model="errorDialog" :errorArr="errorArr"></error-modal>
        <delete-confirm
            @delete="deleteImage"
            :paramProps="paramProps"
            v-model="deleteConfirm"
        ></delete-confirm>
    </v-dialog>
</template>

<script src="./edit-images-modal.js"></script>
