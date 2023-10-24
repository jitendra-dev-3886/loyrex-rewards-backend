<template>
    <div>
        <v-tabs v-model="tab" class="mb-5" @change="refreshData()">
            <v-tab href="#tab1" v-index="$getConst('PRODUCT')">
                <p class="mt-2">Products</p>
            </v-tab>
            <v-tab href="#tab2" v-importBulk="$getConst('PRODUCT')">
                <p class="mt-2">{{ $getConst("IMPORT_TAB_TEXT") }}</p>
            </v-tab>
            <v-tab href="#tab3">
                <!-- Permission pending -->
                <p class="mt-1">Import Images</p>
            </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item value="tab1">
                <v-data-table
                    v-model="selected"
                    :headers="headers"
                    :items="tableData"
                    :loading="loading"
                    :options.sync="options"
                    :items-per-page="limit"
                    :footer-props="footerProps"
                    :server-items-length="pageCount"
                    @update:options="onUpdateOptions"
                    class="elevation-1"
                    :show-select="true"
                    v-index="$getConst('PRODUCT')"
                    ref="table"
                >
                    <template v-slot:top>
                        <v-layout>
                            <v-flex xs12 sm12 md3 lg3>
                                <!--                                {{category_id}}-->
                                <v-select
                                    v-model="category_id"
                                    name="category"
                                    item-text="name"
                                    item-value="id"
                                    :items="parentCategoryList"
                                    @change="getSubCategory()"
                                    clearable
                                    label="Category"
                                    class="mt-4 mx-4"
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm12 md3 lg3>
                                <!--{{sub_category_id}}-->
                                <v-select
                                    v-model="sub_category_id"
                                    name="sub_category_id"
                                    item-text="name"
                                    item-value="id"
                                    :items="subCategoryList"
                                    :loading="isSubCategoryLoading"
                                    clearable
                                    label="Sub Category"
                                    class="mt-4 mx-4"
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm12 md3 lg3>
                                <v-select
                                    v-model="brand_id"
                                    name="brand"
                                    item-text="name"
                                    item-value="id"
                                    :items="brandList"
                                    clearable
                                    label="Brand"
                                    class="mt-4 mx-4"
                                ></v-select>
                            </v-flex>

                            <v-flex xs12 sm12 md3 lg3>
                                <v-select
                                    v-model="catalogue_id"
                                    name="catalogue"
                                    item-text="name"
                                    item-value="id"
                                    :items="catalogueList"
                                    clearable
                                    label="Catalogue"
                                    class="mt-4 mx-4"
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm12 md3 lg3>
                                <v-text-field
                                    type="text"
                                    name="point"
                                    label="Points"
                                    v-model="point"
                                    class="mt-4 mx-4"
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md3 lg3>
                                <div class="float-right mr-2">
                                    <v-btn
                                        color="primary"
                                        class="btn mt-6 mr-2"
                                        @click="applyFilter(false)"
                                        >{{
                                            $getConst("APPLY_FILTER_BUTTON")
                                        }}</v-btn
                                    >
                                    <v-btn
                                        color="secondary"
                                        class="btn mt-6"
                                        @click="resetFilter()"
                                        >{{
                                            $getConst("RESET_FILTER_BUTTON")
                                        }}</v-btn
                                    >
                                </div>
                            </v-flex>
                        </v-layout>
                        <v-divider class="mx-4"></v-divider>
                        <v-layout>
                            <v-flex xs12 sm12 md4 lg4>
                                <v-text-field
                                    v-model="searchModel"
                                    @input="onSearch"
                                    label="Search"
                                    class="mx-4"
                                    prepend-inner-icon="search"
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md8 lg8>
                                <div class="float-right mt-4">
                                    <v-tooltip bottom>
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-btn
                                                v-store="$getConst('PRODUCT')"
                                                color="primary"
                                                dark
                                                class="mr-2"
                                                v-on="on"
                                                @click="addProduct()"
                                            >
                                                <v-icon small>{{
                                                    icons.mdiPlus
                                                }}</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>{{
                                            $getConst("ADD_TOOLTIP")
                                        }}</span>
                                    </v-tooltip>
                                    <export-btn
                                        @click.native="setExport()"
                                        ref="exportbtn"
                                        :exportProps="exportProps"
                                        v-export="$getConst('PRODUCT')"
                                    ></export-btn>
                                    <template v-if="selected.length > 1">
                                        <multi-delete
                                            @click.native="multipleDelete()"
                                            @multiDelete="
                                                getData(), (selected = [])
                                            "
                                            ref="multipleDeleteBtn"
                                            :deleteProps="deleteProps"
                                            v-deleteAll="$getConst('PRODUCT')"
                                        ></multi-delete>
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2"
                                    @click="onEditView(item.id, false)"
                                    v-show="$getConst('PRODUCT')"
                                >
                                    {{ icons.mdiEye }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("VIEW_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2"
                                    @click="onEditView(item.id, true)"
                                    v-update="$getConst('PRODUCT')"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("EDIT_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2"
                                    @click="onEditImages(item.id)"
                                >
                                    {{ icons.mdiImageEditOutline }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("EDIT_IMAGE_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2"
                                    @click="deleteItem(item)"
                                    v-destroy="$getConst('PRODUCT')"
                                >
                                    {{ icons.mdiDelete }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("DELETE_TOOLTIP") }}</span>
                        </v-tooltip>
                    </template>
                    <template v-slot:item.featured_image="{ item }">
                        <img
                            :src="item.featured_image"
                            :ref="'featuredImage' + item.id"
                            height="100px"
                            width="100px"
                            class="mt-1 mb-1"
                            @error="onImgError(item.id)"
                        />
                    </template>
                    <template v-slot:item.point="{ item }">
                        <span>{{ item.point | formatNumber }}</span>
                    </template>
                    <template v-slot:item.catalogues="{ item }">
                        <template
                            v-for="(catalogue, index) in item.catalogue_array"
                        >
                            <span v-if="index != '0'">, </span>
                            <span>{{ catalogue.name }}</span>
                        </template>
                    </template>
                </v-data-table>
            </v-tab-item>
            <v-tab-item value="tab2">
                <div>
                    <v-card class="mx-auto mb-5">
                        <v-card-text>
                            <drag-and-drop-file
                                :import-props="importProps"
                                ref="dragAndDropFile"
                            ></drag-and-drop-file>
                        </v-card-text>
                    </v-card>
                </div>
            </v-tab-item>
            <v-tab-item value="tab3">
                <div>
                    <v-card class="mx-auto mb-5">
                        <v-card-text>
                            <drag-and-drop-file
                                :import-props="importZipProps"
                                ref="dragAndDropFile"
                            ></drag-and-drop-file>
                        </v-card-text>
                    </v-card>
                </div>
            </v-tab-item>
        </v-tabs-items>
        <add-product
            v-model="addProductModal"
            v-if="addProductModal"
            ref="productModal"
        ></add-product>
        <delete-modal
            v-model="modalOpen"
            :paramProps="paramProps"
            :confirmation="confirmation"
        ></delete-modal>
        <product-view-modal
            v-if="productViewModal"
            v-model="productViewModal"
        ></product-view-modal>
        <edit-images-modal
            v-if="editImagesDialog"
            v-model="editImagesDialog"
        ></edit-images-modal>
    </div>
</template>

<script src="./product.js"></script>
<style>
.elevation-1 table th:first-child,
.elevation-1 table td:first-child {
    min-width: 8% !important;
    width: 8% !important;
}
</style>
