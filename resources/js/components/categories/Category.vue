<template>
    <div>
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
                        v-index = "$getConst('CATEGORY')"
                        ref="table"
                >
                    <template v-slot:top>
                        <v-layout>
                            <v-flex xs12 sm12 md4 lg4>
                                <v-text-field v-model="searchModel" @input="onSearch" label="Search" class="mx-4" prepend-inner-icon="search"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md8 lg8>
                                <div class="float-right mt-4">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn v-store = "$getConst('CATEGORY')"
                                                   color="primary"
                                                   dark
                                                   class="mr-2"
                                                   v-on="on"
                                                   @click="addCategory()"
                                            ><v-icon small>{{ icons.mdiPlus }}</v-icon></v-btn>
                                        </template>
                                        <span>{{$getConst('ADD_TOOLTIP')}}</span>
                                    </v-tooltip>
                                    <export-btn @click.native="setExport()" ref="exportbtn" :exportProps="exportProps" v-export="$getConst('CATEGORY')"></export-btn>
                                    <template v-if="selected.length>1">
                                    <multi-delete @click.native="multipleDelete()" @multiDelete="getData(), selected = []" ref="multipleDeleteBtn" :deleteProps="deleteProps" v-deleteAll = "$getConst('CATEGORY')"></multi-delete>
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>

                    </template>
                    <template v-slot:item.parent_id="{ item }">
                        <div>{{item.parent_category ? item.parent_category.name : '-'}}</div>
                    </template>
                    <template v-slot:item.no_of_items="{ item }">
                        <div class="cursor-pointer" @click="handleClick(item)">{{item.products && item.products.length > 0 ? item.no_of_items : '0'}}</div>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2"
                                    @click="editItem(item.id)"
                                    v-update = "$getConst('CATEGORY')"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{$getConst('EDIT_TOOLTIP')}}</span>
                        </v-tooltip>
                        <v-tooltip bottom v-if="item.products && item.products.length > 0">
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-destroy="$getConst('CATEGORY')"
                                >
                                    {{ icons.mdiDeleteOff }}
                                </v-icon>
                            </template>
                            <span>{{$getConst('DELETE_TOOLTIP')}}</span>
                        </v-tooltip>
                        <v-tooltip bottom v-else>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    @click="deleteItem(item)"
                                    v-destroy="$getConst('CATEGORY')"
                                >
                                    {{ icons.mdiDelete }}
                                </v-icon>
                               
                            </template>
                            <span>{{$getConst('DELETE_TOOLTIP')}}</span>
                        </v-tooltip>
                    </template>
                </v-data-table>
            </v-tab-item>
        </v-tabs-items>
        <add-category v-model="addCategoryModal"></add-category>
        <delete-modal  v-model="modalOpen" :paramProps="paramProps" :confirmation="confirmation"></delete-modal>
    </div>
</template>

<script src="./category.js"></script>
