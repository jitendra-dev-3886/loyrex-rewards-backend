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
                    :server-items-length="pageCount"
                    :footer-props="footerProps"
                    @update:options="onUpdateOptions"
                    class="elevation-1"
                    :show-select="true"
                    v-index="$getConst('USERGROUP')"
                    ref="table"
                >
                    <template v-slot:top>
                        <v-layout>
                            <v-flex xs12 sm12 md4 lg4>
                                <v-text-field v-model="searchModel" @input="onSearch" label="Search" class="mx-4"
                                              prepend-inner-icon="search"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md8 lg8>
                                <div class="float-right mt-4">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn v-store="$getConst('USERGROUP')"
                                                   color="primary"
                                                   dark
                                                   class="mr-2"
                                                   v-on="on"
                                                   @click="addUserGroup()"
                                            ><v-icon small>{{ icons.mdiPlus }}</v-icon></v-btn>
                                        </template>
                                        <span>{{$getConst('ADD_TOOLTIP')}}</span>
                                    </v-tooltip>
                                    <export-btn @click.native="setExport()" ref="exportbtn" :exportProps="exportProps" v-export="$getConst('USERGROUP')"></export-btn>
                                    <template v-if="selected.length>1">
                                        <multi-delete @click.native="multipleDelete()" @multiDelete="getData(), selected = []" ref="multipleDeleteBtn" :deleteProps="deleteProps" v-deleteAll="$getConst('USER')"></multi-delete>
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>
                    <template v-slot:item.no_of_users="{ item }">
                        <div class="cursor-pointer" @click="handleClick(item)">{{item.no_of_users}}</div>
                    </template>

                    <template v-slot:item.catalogue_array="{ item }">
                        <div  v-for="cat in item.catalogue_array">{{cat.name}}</div>

                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2"
                                    @click="onEditView(item.id, false)"
                                    v-show="$getConst('USERGROUP')"
                                >
                                    {{ icons.mdiEye }}
                                </v-icon>
                            </template>
                            <span>{{$getConst('VIEW_TOOLTIP')}}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2"
                                    @click="onEditView(item.id, true)"
                                    v-update="$getConst('USERGROUP')"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{$getConst('EDIT_TOOLTIP')}}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    @click="deleteItem(item)"
                                    v-destroy = "$getConst('USERGROUP')"
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
        <group-modal :paramProps="paramProps" ref="GroupModal" v-model="userDialogue"></group-modal>
        <delete-modal v-model="modalOpen" :paramProps="paramProps" :confirmation="confirmation"></delete-modal>
        <group-view-modal v-model="UsergroupViewModal"></group-view-modal>
    </div>
</template>

<script src="./usergroup.js"></script>

<style scoped>
</style>
