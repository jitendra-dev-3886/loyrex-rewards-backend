<template>
    <div class="role-main">
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
                    :show-select="false"
                    v-index="$getConst('CONTACTUS')"
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
                                    <export-btn @click.native="setExport()" ref="exportbtn" :exportProps="exportProps"
                                                v-export="$getConst('CONTACTUS')"></export-btn>
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
                                    @click="onView(item.id)"
                                    v-show="$getConst('CONTACTUS')"
                                >
                                    {{ icons.mdiEye }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst('VIEW_TOOLTIP') }}</span>
                        </v-tooltip>

                    </template>

                </v-data-table>
            </v-tab-item>
        </v-tabs-items>
        <error-modal
            v-model="errorDialog"
            :error-arr="errorArr"
        />
        <delete-modal v-model="modalOpen" :paramProps="paramProps" :confirmation="confirmation"></delete-modal>
        <ContactUs-view-modal v-model="ContactUsViewModal"></ContactUs-view-modal>
    </div>
</template>

<script src="./contact.js"></script>
