import { mapGetters, mapState } from "vuex";
import VueApexCharts from "vue-apexcharts";
import ErrorModal from "../../partials/ErrorModal";
import CommonServices from "../../common_services/common";
import { REMOVE_BODY_CLASSNAME } from "../../store/htmlclass.module";

export default {
    name: "dashboard",
    components: {
        apexchart: VueApexCharts,
        ErrorModal
    },
    mixins: [CommonServices],
    data() {
        return {
            errorArr: [],
            errorDialog: false,
            strokeColor: "#D13647",
            chartOptions: {},
            series: [
                {
                    name: "User Activity",
                    data: []
                }
            ],
            seriesPie: [],
            chartOptionsPie: {
                chart: {
                    width: 380,
                    type: "pie"
                },
                labels: [],
                responsive: [
                    {
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: "bottom"
                            }
                        }
                    }
                ]
            }
        };
    },
    computed: {
        ...mapGetters(["layoutConfig"]),
        ...mapState({
            dashboardData: state => state.dashboardStore.dashboardData
        })
    },
    mounted() {
        this.$store.dispatch("dashboardStore/getDashboard").then(
            response => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                if (response.error) {
                    this.errorArr = response.data.error;
                    this.errorDialog = true;
                } else {
                    // setting pie chart labels
                    if (response.data.orders) {
                        if (this.$refs["pieChart"]) {
                            this.$refs["pieChart"].updateOptions({
                                labels: response.data.orders.orders_pie_chart
                                    ? response.data.orders.orders_pie_chart
                                          .label
                                    : ""
                            });
                        }
                    }
                    if (response.data.users) {
                        this.chartOptions = {
                            chart: {
                                type: "area",
                                height: 150,
                                toolbar: {
                                    show: false
                                },
                                zoom: {
                                    enabled: false
                                },
                                sparkline: {
                                    enabled: true
                                }
                            },
                            plotOptions: {},
                            legend: {
                                show: false
                            },
                            dataLabels: {
                                enabled: false
                            },
                            fill: {
                                type: "solid",
                                opacity: 1
                            },
                            stroke: {
                                curve: "smooth",
                                show: true,
                                width: 3,
                                colors: [
                                    this.layoutConfig(
                                        "colors.theme.base.success"
                                    )
                                ]
                            },
                            xaxis: {
                                categories:
                                    response.data.users.users_activity_chart
                                        .label,
                                axisBorder: {
                                    show: false
                                },
                                axisTicks: {
                                    show: false
                                },
                                labels: {
                                    show: false,
                                    style: {
                                        colors: this.layoutConfig(
                                            "colors.gray.gray-500"
                                        ),
                                        fontSize: "12px",
                                        fontFamily: this.layoutConfig(
                                            "font-family"
                                        )
                                    }
                                },
                                crosshairs: {
                                    show: false,
                                    position: "front",
                                    stroke: {
                                        color: this.layoutConfig(
                                            "colors.gray.gray-300"
                                        ),
                                        width: 1,
                                        dashArray: 3
                                    }
                                },
                                tooltip: {
                                    enabled: true,
                                    formatter: undefined,
                                    offsetY: 0,
                                    style: {
                                        fontSize: "12px",
                                        fontFamily: this.layoutConfig(
                                            "font-family"
                                        )
                                    }
                                }
                            },
                            yaxis: {
                                labels: {
                                    show: false,
                                    style: {
                                        colors: this.layoutConfig(
                                            "colors.gray.gray-500"
                                        ),
                                        fontSize: "12px",
                                        fontFamily: this.layoutConfig(
                                            "font-family"
                                        )
                                    }
                                }
                            },
                            states: {
                                normal: {
                                    filter: {
                                        type: "none",
                                        value: 0
                                    }
                                },
                                hover: {
                                    filter: {
                                        type: "none",
                                        value: 0
                                    }
                                },
                                active: {
                                    allowMultipleDataPointsSelection: false,
                                    filter: {
                                        type: "none",
                                        value: 0
                                    }
                                }
                            },
                            tooltip: {
                                style: {
                                    fontSize: "12px",
                                    fontFamily: this.layoutConfig("font-family")
                                },
                                y: {
                                    formatter: function(val) {
                                        return val + " times";
                                    }
                                }
                            },
                            colors: [
                                this.layoutConfig("colors.theme.light.success")
                            ],
                            markers: {
                                colors: [
                                    this.layoutConfig(
                                        "colors.theme.light.success"
                                    )
                                ],
                                strokeColor: [
                                    this.layoutConfig(
                                        "colors.theme.base.success"
                                    )
                                ],
                                strokeWidth: 3
                            },
                            grid: {
                                show: false,
                                padding: {
                                    left: 0,
                                    right: 0
                                }
                            }
                        };
                        if (this.$refs["areaChart"]) {
                            this.$refs["areaChart"].updateSeries([
                                {
                                    name: "User Activity",
                                    data:
                                        response.data.users.users_activity_chart
                                            .data
                                }
                            ]);
                        }
                    }
                }
            },
            error => {
                this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                this.errorArr = this.getAPIErrorMessage(error.response);
                this.errorDialog = true;
            }
        );
    },
    methods: {
        orderDetail(id) {
            this.$store.commit("orderStore/setEditId", id);
            //get by id to open and edit the role of particular id
            this.$store.dispatch("orderStore/getById", id).then(
                response => {
                    this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
                    if (response.data.error) {
                        this.errorArr = response.data.error;
                        this.errorDialog = true;
                    } else {
                        this.$router.push({ name: "order-detail" });
                    }
                },
                error => {
                    this.errorArr = this.getModalAPIerrorMessage(error);
                    this.errorDialog = true;
                }
            );
        },

        //where flag P=Pending, D=Delivered, A=Available Product, O='Out of stock'
        redirectToPage(section, flag) {
            if (section == "orders") {
                this.$router.push({
                    name: "orders",
                    params: { flag: flag }
                });
            } else if (section == "products") {
                this.$router.push({
                    name: "product-catalogue",
                    params: { flag: flag }
                });
            } else if (section == "users") {
                this.$router.push({
                    name: "users",
                    params: {}
                });
            } else if (section == "vouchers") {
                this.$router.push({
                    name: "voucher",
                    params: { flag: flag }
                });
            } else {
            }
        }
    }
};
