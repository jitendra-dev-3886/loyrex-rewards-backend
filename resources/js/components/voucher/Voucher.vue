<template>
    <div>
        <v-tabs v-model="tab" class="mb-5" @change="refreshData()">
            <v-tab href="#tab1" v-index="$getConst('VOUCHER')">
                <p class="mt-2">Vouchers</p>
            </v-tab>
            <v-tab href="#tab2" v-importBulk="$getConst('VOUCHER')">
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
                    v-index="$getConst('VOUCHER')"
                    ref="table"
                >
                    <template v-slot:top>
                        <v-layout>
                            <v-flex xs12 sm12 md3 lg2>
                                <v-select
                                    v-model="voucherType"
                                    name="voucher_type"
                                    item-text="name"
                                    item-value="id"
                                    :items="voucherTypes"
                                    clearable
                                    label="Voucher type"
                                    class="mt-4 mx-4"
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm12 md3 lg2>
                                <v-select
                                    v-model="voucherStatus"
                                    name="status_status"
                                    item-text="name"
                                    item-value="id"
                                    clearable
                                    :items="statusTypes"
                                    label="Voucher status"
                                    class="mt-4 mx-4"
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm12 md3 lg3>
                                <v-combobox
                                    :items="userList"
                                    item-value="id"
                                    v-model="userAssign"
                                    label="Assigned user"
                                    :loading="isLoadingUsers"
                                    :search-input.sync="search"
                                    name="user_id"
                                    clearable
                                    no-filter
                                    placeholder="Type name, contact no. or email to search users.."
                                    class="mt-4 mx-4"
                                >
                                    <template v-slot:selection="data">
                                        {{ data.item | getFullName }}
                                    </template>
                                    <template v-slot:item="data">
                                        <v-list-item-content class="pa-1">
                                            <v-list-item-title
                                                class="text-wrap"
                                                >{{
                                                    data.item | getFullName
                                                }}</v-list-item-title
                                            >
                                            <v-list-item-subtitle>{{
                                                data.item.email
                                            }}</v-list-item-subtitle>
                                            <v-list-item-subtitle>{{
                                                data.item.contact_number
                                            }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </template>
                                </v-combobox>
                            </v-flex>
                            <v-flex xs12 sm12 md3 lg3>
                                <v-text-field
                                    v-model="referenceVoucherNo"
                                    name="reference_voucher_no"
                                    label="Reference Voucher No."
                                    class="mt-4 mx-4"
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md3 lg2>
                                <div class="float-right mr-2">
                                    <v-btn
                                        color="primary"
                                        class="btn mt-6 mr-2"
                                        @click="applyFilter(false)"
                                    >
                                        {{ $getConst("APPLY_FILTER_BUTTON") }}
                                    </v-btn>
                                    <v-btn
                                        color="secondary"
                                        class="btn mt-6"
                                        @click="resetFilter()"
                                        >{{ $getConst("RESET_FILTER_BUTTON") }}
                                    </v-btn>
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
                                        <template v-slot:activator="{ on }">
                                            <v-btn
                                                v-store="$getConst('VOUCHER')"
                                                color="primary"
                                                dark
                                                class="mr-2"
                                                v-on="on"
                                                @click="addVoucher(false)"
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
                                        v-export="$getConst('VOUCHER')"
                                    ></export-btn>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>
                    <template v-slot:[`item.id`]="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    class="cursor-pointer"
                                    v-on="on"
                                    small
                                    :color="
                                        item.status == '1'
                                            ? '#444444'
                                            : '#B8B8B8'
                                    "
                                    >{{ icons.mdiCircle }}</v-icon
                                >
                            </template>
                            <span>{{ item.status_text }}</span>
                        </v-tooltip>
                        <span>{{ item.id }}</span>
                    </template>
                    <template v-slot:[`item.link`]="{ item }">
                        <span v-if="item.link"
                            ><a :href="item.link" target="_blank">{{
                                item.link
                            }}</a></span
                        >
                        <span v-else>-</span>
                    </template>
                    <template v-slot:[`item.user_id`]="{ item }">
                        <span
                            v-if="
                                item.user_id &&
                                    item.user_id != '0' &&
                                    item.user_id != null &&
                                    item.user != null
                            "
                            >{{ item.user | getFullName }}</span
                        >
                        <span v-else>N/A</span>
                    </template>
                    <template v-slot:[`item.valid_till`]="{ item }">
                        <span v-if="item.valid_till && item.valid_till != ''"
                            >Valid till
                            {{
                                computedDateFormattedMomentjs(item.valid_till)
                            }}</span
                        >
                        <span v-else>N/A</span>
                    </template>
                    <template v-slot:[`item.points_products`]="{ item }">
                        <span v-if="item.voucher_type == 1">{{
                            item.points | formatNumber
                        }}</span>
                        <span v-else>
                            <template v-for="(product, index) in item.product">
                                <template v-if="index != '0'">, </template>
                                <template>{{ product.name }}</template>
                            </template>
                        </span>
                    </template>
                    <template v-slot:[`item.actions`]="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2 d-inline"
                                    @click="onEditView(item.id, false)"
                                    v-show="$getConst('VOUCHER')"
                                >
                                    {{ icons.mdiEye }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("VIEW_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2 d-inline"
                                    @click="onEditView(item.id, true)"
                                    v-update="$getConst('VOUCHER')"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("EDIT_TOOLTIP") }}</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2 d-inline"
                                    @click="updateStatus(item)"
                                    :disabled="
                                        item.voucher_redeem == '1'
                                            ? true
                                            : false
                                    "
                                    v-update="$getConst('VOUCHER')"
                                >
                                    {{
                                        item.status == "1"
                                            ? icons.mdiCancel
                                            : icons.mdiCheckCircleOutline
                                    }}
                                </v-icon>
                            </template>
                            <span>{{
                                item.status == "1" ? "Deactivate" : "Activate"
                            }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    small
                                    v-on="on"
                                    class="mr-2 d-inline"
                                    @click="onSendMail(item.id)"
                                    v-show="$getConst('VOUCHER')"
                                >
                                    {{ icons.mdiGmail }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("SEND_VOUCHER_MAIL") }}</span>
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
        <voucher-modal
            :paramProps="paramProps"
            ref="voucherModal"
            v-model="voucherDialogue"
        ></voucher-modal>
        <voucher-view-modal
            v-if="voucherViewModal"
            v-model="voucherViewModal"
        ></voucher-view-modal>
        <send-voucher-mail-modal
            v-if="sendVoucherMailModal"
            v-model="sendVoucherMailModal"
        ></send-voucher-mail-modal>
    </div>
</template>

<script src="./voucher.js"></script>

<style scoped></style>
