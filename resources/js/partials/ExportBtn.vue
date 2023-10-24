<template>
    <span>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn :loading="isExporting" color="warning" class="mr-2" v-on="on"><v-icon small>{{ icons.mdiExport }}</v-icon></v-btn>
            </template>
            <span>{{ $getConst('EXPORT_TOOLTIP') }}</span>
        </v-tooltip>
        <error-modal :errorArr="errorArr" v-model="errorDialog"></error-modal>
    </span>
</template>

<script>
    import CommonServices from '../common_services/common.js';
    import ErrorModal from '../partials/ErrorModal';

    export default {
        data() {
            return {
                errorArr: [],
                errorDialog: false,
                isExporting: false,
            }
        },
        components: {
            CommonServices,
            ErrorModal
        },
        props: ['value', 'exportProps'],
        mixins: [CommonServices],
        methods: {
            exportToCSV() {
                if (this.exportProps.ids.length > 0) {
                    // eslint-disable-next-line max-len
                    this.exportProps.pagination.filter = this.convetFiltertoBase64({ id: this.exportProps.ids });
                }
                this.isExporting = true;
                this.$store.dispatch(`${this.exportProps.store}/export`, this.exportProps.pagination).then((response) => {
                    this.isExporting = false;
                    if (response.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        this.convertToCSV(this.exportProps.fileName, response.data);
                    }
                }, (error) => {
                    this.isExporting = false;
                    this.errorArr = this.getModalAPIerrorMessage(error);
                    this.errorDialog = true;
                });
            },
        },
        mounted() {
            this.errorMessage = '';
        }
    }
</script>
