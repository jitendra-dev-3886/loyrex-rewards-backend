<template>
    <div>
        <v-tabs v-model="tab" class="mb-5" @change="refreshData()">
            <v-tab href="#tab1" v-index="$getConst('ORDER')">
                <p class="mt-2">Orders</p>
            </v-tab>
            <v-tab href="#tab2" v-importBulk="$getConst('ORDER')">
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
                    :footer-props="footerProps"
                    :server-items-length="pageCount"
                    @update:options="onUpdateOptions"
                    class="elevation-1"
                    :show-select="true"
                    v-index="$getConst('ORDER')"
                    ref="table"
                >
                    <template v-slot:top>
                        <v-layout>
                            <v-flex xs12 sm12 md3 lg3>
                                <v-select
                                    label="Order status"
                                    name="order_status"
                                    v-model="orderStatus"
                                    :items="orderStatusList"
                                    item-text="name"
                                    item-value="order_status"
                                    clearable
                                    class="mt-4 mx-4"
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm12 md6 lg6>
                                <div
                                    class="input-daterange input-group d-inline-block"
                                    id="datepicker"
                                >
                                    <v-layout>
                                        <v-flex xs12 sm12 md6 lg6>
                                            <v-menu
                                                v-model="fromDateMenu"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="unset"
                                                class="d-inline-block"
                                            >
                                                <template
                                                    v-slot:activator="{ on }"
                                                >
                                                    <v-text-field
                                                        :value="
                                                            computedDateFormattedMomentjs(
                                                                fromDate
                                                            )
                                                        "
                                                        label="From date"
                                                        readonly
                                                        v-on="on"
                                                        single-line
                                                        class="mt-4 mx-4"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                    v-model="fromDate"
                                                    @input="
                                                        fromDateMenu = false
                                                    "
                                                ></v-date-picker>
                                            </v-menu>
                                        </v-flex>
                                        <v-flex xs12 sm12 md6 lg6>
                                            <v-menu
                                                v-model="toDateMenu"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="unset"
                                                class="d-inline-block"
                                            >
                                                <template
                                                    v-slot:activator="{ on }"
                                                >
                                                    <v-text-field
                                                        :value="
                                                            computedDateFormattedMomentjs(
                                                                toDate
                                                            )
                                                        "
                                                        label="To date"
                                                        readonly
                                                        v-on="on"
                                                        single-line
                                                        class="mt-4 mx-4"
                                                        :disabled="
                                                            fromDate == ''
                                                        "
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                    v-model="toDate"
                                                    @input="toDateMenu = false"
                                                    :min="fromDate"
                                                ></v-date-picker>
                                            </v-menu>
                                        </v-flex>
                                    </v-layout>
                                </div>
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
                                    <export-btn
                                        @click.native="setExport()"
                                        ref="exportbtn"
                                        :exportProps="exportProps"
                                        v-export="$getConst('ORDER')"
                                    ></export-btn>
                                    <template v-if="selected.length > 1">
                                        <multi-delete
                                            @click.native="multipleDelete()"
                                            @multiDelete="
                                                getData(), (selected = [])
                                            "
                                            ref="multipleDeleteBtn"
                                            :deleteProps="deleteProps"
                                            v-deleteAll="$getConst('ORDER')"
                                        ></multi-delete>
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>

                    <template v-slot:item.items="{ item }">
                        <ul class="decimal-list mt-3">
                            <li v-for="product in item.products">
                                {{ product.product_name }}
                            </li>
                        </ul>
                    </template>

                    <template v-slot:item.category="{ item }">
                        <ul class="no-list-style mt-3">
                            <li v-for="product in item.products">
                                {{ product.category_name }}
                            </li>
                        </ul>
                    </template>

                    <template v-slot:item.order_status="{ item }">
                        {{ item.order_status_text }}
                    </template>

                    <template v-slot:item.total_points="{ item }">
                        <span>{{ item.total_points | formatNumber }}</span>
                    </template>
                    <template v-slot:item.first_name="{ item }">
                        {{ item.first_name }} {{ item.last_name }}
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    class="mr-2"
                                    v-on="on"
                                    @click="editOrder(item)"
                                    v-show="$getConst('ORDER')"
                                >
                                    {{ icons.mdiEye }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("VIEW_TOOLTIP") }}</span>
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
        <delete-modal
            v-model="modalOpen"
            :paramProps="paramProps"
            :confirmation="confirmation"
        ></delete-modal>
    </div>
</template>

<script src="./order.js"></script>
