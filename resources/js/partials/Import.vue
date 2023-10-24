<template>
    <div>
        <v-card class=" mx-auto mb-5">
            <v-card-text>
                        <form method="POST" name="import-csv-form" role="form" novalidate autocomplete="off" @submit.prevent="uploadCsv()">

                            <v-layout row wrap>
                                <v-flex xs12 sm12 md4 lg4 class="p-4">
                                    <v-file-input
                                            v-model="file"
                                            name="import_file"
                                            accept="csv"
                                            label="File Upload"
                                            show-size
                                            counter
                                            :prepend-icon="icons.mdiPaperclip"
                                            v-validate="'required|size:4000|ext:csv'"
                                            :error-messages="getErrorValue('import_file')"
                                            @click:clear="file=null"
                                    >
                                        <template v-slot:selection="{ text }">
                                            {{ text }}
                                        </template>
                                    </v-file-input>
                                </v-flex>
                                <v-flex xs12 sm12 md6 lg6 class="p-4">
                                    <v-btn type="submit" :loading="loading" large color="primary"><v-icon small>{{icons.mdiUpload}}</v-icon>Upload</v-btn>
                                    <v-btn class="mt-xs-1" @click.native="downloadSampleFile()"large color="success"><v-icon small>{{icons.mdiDownload}}</v-icon>Download Sample CSV</v-btn>
                                </v-flex>
                            </v-layout>

                        </form>
            </v-card-text>
        </v-card>
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
                v-index="$getConst('USER')"
                ref="table"
        >
            <template v-slot:top>
                <v-layout>
                    <v-flex xs12 sm12 md12 lg12>
                        <p class="mt-4 ml-4 font-size-h4-sm">Import History</p>
                    </v-flex>
                </v-layout>
                <v-layout>
                    <v-flex xs12 sm12 md4 lg4>
                        <v-text-field v-model="searchModel" @input="onSearch" label="Search" class="mx-4 mt-4" prepend-inner-icon="search"></v-text-field>
                    </v-flex>
                </v-layout>
            </template>
            <template v-slot:item.created_at="{ item }">
                <span>{{getDateTimeFormat(item.created_at) }}</span>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-icon
                        small
                        class="mr-2"
                        @click="onView(item.id)"
                        v-update = "$getConst('USER')"
                >
                    {{ icons.mdiEye }}
                </v-icon>
            </template>

        </v-data-table>
        <error-modal :errorArr="errorArr" v-model="errorDialog"></error-modal>
        <import-error-modal :importErrorArr="importErrorArr" v-model="importErrorDialog"></import-error-modal>
    </div>
</template>

<script src="./import.js"></script>
