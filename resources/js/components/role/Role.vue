<template>
    <div class="role-main">
        <v-tabs v-model="tab" class="mb-5" @click.native="refreshData()">
            <v-tab href="#tab1">
                <p class="mt-2">Listing</p>
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
                        v-index="$getConst('ROLE')"
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
                                            <v-btn color="primary" dark class="mr-2" @click="addrole()" v-store="$getConst('ROLE')" v-on="on"><v-icon small>{{ icons.mdiPlus }}</v-icon></v-btn>
                                        </template>
                                        <span>{{ $getConst('ADD_TOOLTIP') }}</span>
                                    </v-tooltip>

                                    <export-btn @click.native="setExport()" ref="exportbtn" :exportProps="exportProps" v-export="$getConst('ROLE')"></export-btn>
                                    <template v-if="selected.length>1">
                                    <multi-delete @click.native="multipleDelete()" @multiDelete="getData(), selected = []" ref="multipleDeleteBtn" :deleteProps="deleteProps" v-deleteAll="$getConst('ROLE')"></multi-delete>
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon v-if="item.id != 1"
                                        small
                                        v-on="on"
                                        class="mr-2"
                                        @click="editItem(item.id)"
                                        v-update = "$getConst('ROLE')"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst('EDIT_TOOLTIP') }}</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                            <v-icon v-if="item.id != 1"
                                    small
                                    v-on="on"
                                    @click="deleteItem(item.id)"
                                    v-destroy = "$getConst('ROLE')"
                            >
                                {{ icons.mdiDelete }}
                            </v-icon>
                            </template>
                            <span>{{ $getConst('DELETE_TOOLTIP') }}</span>
                        </v-tooltip>
                    </template>

                </v-data-table>
            </v-tab-item>
        </v-tabs-items>
        <error-modal
            v-model="errorDialog"
            :error-arr="errorArr"
        />
        <add-role v-model="addRoleModal"></add-role>
        <delete-modal  v-model="modalOpen" :paramProps="paramProps" :confirmation="confirmation"></delete-modal>
    </div>
</template>

<script src="./role.js"></script>
