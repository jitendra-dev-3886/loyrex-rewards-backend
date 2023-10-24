<template>
    <v-dialog :value="value" content-class="modal-dialog" @click:outside="onCancel()" @keydown.esc="onCancel">
        <v-card>
            <v-card-title
                class="headline black-bg"
                primary-title
            >
                Upload Images
            </v-card-title>
            <v-card-text>
                <ErrorBlockServer :errorMessage="errorMessage"></ErrorBlockServer>
                <v-layout row wrap class="display-block m-0 ">
                    <v-flex xs12>
                        <table class="table">
                            <thead>
                            <tr class="no-border">
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody v-if="uploadImageList">
                            <template v-if="uploadImageList.length != 0">
                                <tr v-for="(uploadImage, index) in uploadImageList">
                                    <td>
                                        <a :href="uploadImage.filename" target="_blank">
                                            <img :src="uploadImage.filename" height="70px">
                                        </a>
                                    </td>
                                    <td style="width:25%;">
                                        <v-icon small @click="confirmDelete(uploadImage.id, index)" v-deleteproductimages="$getConst('PRODUCT')">
                                            {{ icons.mdiDelete }}
                                        </v-icon>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="2" style="text-align: center">No Data available</td>
                            </tr>
                            </tbody>
                        </table>
                    </v-flex>
                    <v-btn class="btn btn-grey m-l-10" @click="onCancel()">{{ $getConst('BTN_CANCEL') }}</v-btn>
                </v-layout>
            </v-card-text>
        </v-card>
        <error-modal v-model="errorDialog" :errorArr="errorArr"></error-modal>
        <delete-confirm @delete="deleteImage" :paramProps="paramProps" v-model="deleteConfirm"></delete-confirm>

    </v-dialog>
</template>


<script src="./upload-image-modal.js"></script>
