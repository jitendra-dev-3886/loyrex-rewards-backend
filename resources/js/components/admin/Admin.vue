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
                    v-index = "$getConst('ADMINUSER')"
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
                                            <v-btn v-register = "$getConst('ADMINUSER')"
                                                   color="primary"
                                                   dark
                                                   class="mr-2"
                                                   v-on="on"
                                                   @click="addAdmin()"
                                            ><v-icon small>{{ icons.mdiPlus }}</v-icon></v-btn>
                                        </template>
                                        <span>{{$getConst('ADD_TOOLTIP')}}</span>
                                    </v-tooltip>
                                    <export-btn @click.native="setExport()" ref="exportbtn" :exportProps="exportProps" v-export = "$getConst('ADMINUSER')"></export-btn>
                                    <template v-if="selected.length>1">
                                        <multi-delete @click.native="multipleDelete()" @multiDelete="getData(), selected = []" ref="multipleDeleteBtn" :deleteProps="deleteProps" v-deleteAll = "$getConst('ADMINUSER')"></multi-delete>
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>

                    </template>

                    <template v-slot:item.first_name="{ item }">
                        {{item.first_name + ' ' + item.last_name  }}
                    </template>

                    <template v-slot:item.role_id="{ item }">
                        {{item.role.name }}
                    </template>

                    <template v-slot:item.actions="{ item }">

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2"
                                    @click="onEditView(item.id, false)"
                                    v-show="$getConst('ADMINUSER')"
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
                                    @click="onEditView(item.id,true)"
                                    v-update = "$getConst('ADMINUSER')"
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
                                    @click="deleteItem(item.id)"
                                    v-destroy = "$getConst('ADMINUSER')"
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
        <add-admin v-model="addAdminModal" ref="adminModal"></add-admin>
        <delete-modal  v-model="modalOpen" :paramProps="paramProps" :confirmation="confirmation"></delete-modal>
        <admin-view-modal v-model="adminViewModal"></admin-view-modal>
    </div>
</template>

<script src="./admin.js"></script>
