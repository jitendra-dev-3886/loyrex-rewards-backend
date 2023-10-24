<template>
    <div>
        <v-tabs v-model="tab" class="mb-5" @change="refreshData()">
            <v-tab href="#tab1" v-index="$getConst('USER')">
                <p class="mt-2">Users</p>
            </v-tab>
            <v-tab href="#tab2" v-importBulk="$getConst('USER')">
                <p class="mt-2">{{ $getConst("IMPORT_TAB_TEXT") }}</p>
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
                    :server-items-length="pageCount"
                    :footer-props="footerProps"
                    @update:options="onUpdateOptions"
                    class="elevation-1"
                    :show-select="true"
                    v-index="$getConst('USER')"
                    ref="table"
                >
                    <template v-slot:top>
                        <v-layout>
                            <v-flex xs12 sm12 md3 lg3>
                                <!--{{category_id}}-->
                                <v-select
                                    v-model="userGroupsId"
                                    name="user_groups"
                                    item-text="name"
                                    item-value="id"
                                    :items="userGroupList"
                                    @change="getUserGroup()"
                                    clearable
                                    label="Group"
                                    class="mt-4 mx-4"
                                ></v-select>
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
                                                v-store="$getConst('USER')"
                                                color="primary"
                                                dark
                                                class="mr-2"
                                                v-on="on"
                                                @click="addUser()"
                                                ><v-icon small>{{
                                                    icons.mdiPlus
                                                }}</v-icon></v-btn
                                            >
                                        </template>
                                        <span>{{
                                            $getConst("ADD_TOOLTIP")
                                        }}</span>
                                    </v-tooltip>
                                    <export-btn
                                        @click.native="setExport()"
                                        ref="exportbtn"
                                        :exportProps="exportProps"
                                        v-export="$getConst('USER')"
                                    ></export-btn>
                                    <template v-if="selected.length > 1">
                                        <multi-delete
                                            @click.native="multipleDelete()"
                                            @multiDelete="
                                                getData(), (selected = [])
                                            "
                                            ref="multipleDeleteBtn"
                                            :deleteProps="deleteProps"
                                            v-deleteAll="$getConst('USER')"
                                        ></multi-delete>
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>
                    <template v-slot:item.userGroup_array="{ item }">
                        <div v-for="group in item.userGroup_array">
                            {{ group.name }}
                        </div>
                    </template>
                    <template v-slot:item.first_name="{ item }">
                        <span>{{
                            item.first_name + " " + item.last_name
                        }}</span>
                    </template>
                    <template v-slot:item.reward_points="{ item }">
                        <span>{{ item.reward_points | formatNumber }}</span>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2"
                                    @click="onEditView(item.id, false)"
                                    v-show="$getConst('USER')"
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
                                    v-update="$getConst('USER')"
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
                                    @click="deleteItem(item.id)"
                                    v-destroy="$getConst('USER')"
                                >
                                    {{ icons.mdiDelete }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("DELETE_TOOLTIP") }}</span>
                        </v-tooltip>
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
        </v-tabs-items>
        <user-modal
            :paramProps="paramProps"
            ref="userModal"
            v-model="userDialogue"
        ></user-modal>
        <delete-modal
            v-model="modalOpen"
            :paramProps="paramProps"
            :confirmation="confirmation"
        ></delete-modal>
        <user-view-modal v-model="userViewModal"></user-view-modal>
    </div>
</template>

<script src="./users.js"></script>

<style>
.elevation-1 table th:nth-child(4),
.elevation-1 table td:nth-child(4) {
    width: 10% !important;
    min-width: 10% !important;
}
.elevation-1 table th:nth-child(5),
.elevation-1 table td:nth-child(5) {
    width: 10% !important;
    min-width: 10% !important;
}
.elevation-1 table th:last-child {
    width: 20% !important;
    min-width: 20% !important;
}
</style>
