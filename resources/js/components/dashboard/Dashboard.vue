<template>
    <div class="">
        <v-layout row wrap class="m-0 bg-gray-100 card-stretch gutter-b">
            <v-flex xs12 lg12 class="mt-7"></v-flex>
            <!-- Begin::Order count-->
            <v-flex xs12 lg4>
                <div
                    class="bg-light-warning px-6 py-8 rounded-xl ml-7 mr-7 mb-7 onHoverCursor"
                    @click="redirectToPage('orders')"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2"
                    >
                        <inline-svg src="/media/svg/icons/Shopping/Orders.svg" />
                    </span>
                    <p
                        class="text-warning font-weight-bold font-size-h6"
                        v-if="dashboardData.orders"
                    >
                        Total Orders:
                        {{ dashboardData.orders.total_orders | formatNumber }}
                    </p>
                </div>
            </v-flex>
            <v-flex xs12 lg4>
                <div
                    class="bg-light-warning px-6 py-8 rounded-xl ml-7 mr-7 mb-7 onHoverCursor"
                    @click="redirectToPage('orders', 'D')"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2"
                    >
                        <inline-svg
                            src="/media/svg/icons/Shopping/Order-delivered.svg"
                        />
                    </span>
                    <p
                        class="text-warning font-weight-bold font-size-h6"
                        v-if="dashboardData.orders"
                    >
                        Delivered Orders:
                        {{
                            dashboardData.orders.total_delivered_orders
                                | formatNumber
                        }}
                    </p>
                </div>
            </v-flex>
            <v-flex xs12 lg4>
                <div
                    class="bg-light-warning px-6 py-8 rounded-xl ml-7 mr-7 mb-7 onHoverCursor"
                    @click="redirectToPage('orders', 'P')"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2"
                    >
                        <inline-svg
                            src="/media/svg/icons/Shopping/Order-pending.svg"
                        />
                    </span>
                    <p
                        class="text-warning font-weight-bold font-size-h6"
                        v-if="dashboardData.orders"
                    >
                        Pending Orders:
                        {{
                            dashboardData.orders.total_pending_orders
                                | formatNumber
                        }}
                    </p>
                </div>
            </v-flex>
            <!--End::Order Count -->

            <!-- Begin::Product count-->
            <v-flex xs12 lg4>
                <div
                    class="bg-light-success px-6 py-8 rounded-xl ml-7 mr-7 mb-7 onHoverCursor"
                    @click="redirectToPage('products')"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-success d-block my-2"
                    >
                        <inline-svg src="/media/svg/icons/Shopping/Box2.svg" />
                    </span>
                    <p
                        class="text-success font-weight-bold font-size-h6"
                        v-if="dashboardData.products"
                    >
                        Total Products:
                        {{
                            dashboardData.products.total_products | formatNumber
                        }}
                    </p>
                </div>
            </v-flex>
            <v-flex xs12 lg4>
                <div
                    class="bg-light-success px-6 py-8 rounded-xl ml-7 mr-7 mb-7 onHoverCursor"
                    @click="redirectToPage('products', 'A')"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-success d-block my-2"
                    >
                        <inline-svg
                            src="/media/svg/icons/Shopping/In-stock.svg"
                        />
                    </span>
                    <p
                        class="text-success font-weight-bold font-size-h6"
                        v-if="dashboardData.products"
                    >
                        Available Products:
                        {{
                            dashboardData.products.total_available_products
                                | formatNumber
                        }}
                    </p>
                </div>
            </v-flex>
            <v-flex xs12 lg4>
                <div
                    class="bg-light-success px-6 py-8 rounded-xl ml-7 mr-7 mb-7 onHoverCursor"
                    @click="redirectToPage('products', 'O')"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-success d-block my-2"
                    >
                        <inline-svg
                            src="/media/svg/icons/Shopping/Out-of-stock.svg"
                        />
                    </span>
                    <p
                        class="text-success font-weight-bold font-size-h6"
                        v-if="dashboardData.products"
                    >
                        Out Of Stock Products:
                        {{
                            dashboardData.products.total_out_of_stock_products
                                | formatNumber
                        }}
                    </p>
                </div>
            </v-flex>
            <!-- End::Product Count -->

            <!-- Begin::UserAndVoucher count-->
            <v-flex xs12 lg4>
                <div
                    class="bg-light-primary px-6 py-8 rounded-xl ml-7 mr-7 mb-7 onHoverCursor"
                    @click="redirectToPage('users')"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-primary d-block my-2"
                    >
                        <inline-svg src="/media/svg/icons/General/Users.svg" />
                    </span>
                    <p
                        class="text-primary font-weight-bold font-size-h6"
                        v-if="dashboardData.users"
                    >
                        Total Users:
                        {{ dashboardData.users.total_users | formatNumber }}
                    </p>
                </div>
            </v-flex>
            <v-flex xs12 lg4>
                <div
                    class="bg-light-primary px-6 py-8 rounded-xl ml-7 mr-7 mb-7 onHoverCursor"
                    @click="redirectToPage('vouchers')"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-primary d-block my-2"
                    >
                        <inline-svg src="/media/svg/icons/Shopping/Sale2.svg" />
                    </span>
                    <p
                        class="text-primary font-weight-bold font-size-h6"
                        v-if="dashboardData.vouchers"
                    >
                        Total Vouchers:
                        {{
                            dashboardData.vouchers.total_vouchers | formatNumber
                        }}
                    </p>
                </div>
            </v-flex>
            <v-flex xs12 lg4>
                <div
                    class="bg-light-primary px-6 py-8 rounded-xl ml-7 mr-7 mb-7 onHoverCursor"
                    @click="redirectToPage('vouchers', 'R')"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-primary d-block my-2"
                    >
                        <inline-svg src="/media/svg/icons/Shopping/Gift.svg" />
                    </span>
                    <p
                        class="text-primary font-weight-bold font-size-h6"
                        v-if="dashboardData.vouchers"
                    >
                        Redeemed Vouchers:
                        {{
                            dashboardData.vouchers.total_voucher_redeem
                                | formatNumber
                        }}
                    </p>
                </div>
            </v-flex>
            <!-- End::UserAndVoucher Count-->
        </v-layout>
        <!--begin::Dashboard-->
        <div class="row">
            <div class="col-xxl-12">
                <!--begin::Advance Table Widget 2-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark"
                            >
                                Recent Orders
                            </span>
                            <!--                            <span class="text-muted mt-3 font-weight-bold font-size-sm">-->
                            <!--&lt;!&ndash;                              More than 400+ new members&ndash;&gt;-->
                            <!--                            </span>-->
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-3 pb-0">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-vertical-center"
                            >
                                <thead>
                                    <tr>
                                        <th
                                            class="p-0 font-weight-bolder text-md-h6"
                                            style="min-width: 150px"
                                        >
                                            Order Date
                                        </th>
                                        <th
                                            class="p-0 font-weight-bolder text-md-h6"
                                            style="min-width: 150px"
                                        >
                                            Name
                                        </th>
                                        <th
                                            class="p-0 font-weight-bolder text-md-h6"
                                            style="min-width: 150px"
                                        >
                                            Points
                                        </th>
                                        <th
                                            class="p-0 font-weight-bolder text-md-h6"
                                            style="min-width: 150px"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="p-0 font-weight-bolder text-md-h6"
                                            style="min-width: 50px"
                                        >
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    v-if="
                                        dashboardData.orders &&
                                            dashboardData.orders.last_orders
                                                .length > 0
                                    "
                                >
                                    <template
                                        v-for="(order, i) in dashboardData
                                            .orders.last_orders"
                                    >
                                        <tr v-bind:key="i">
                                            <td class="pl-0">
                                                <span
                                                    class="font-weight-regular d-block font-size-lg"
                                                >
                                                    {{
                                                        computedDateFormattedMomentjs(
                                                            order.created_at
                                                        )
                                                    }}
                                                </span>
                                            </td>
                                            <td class="pl-0">
                                                <span
                                                    class="d-block font-weight-regular font-size-lg"
                                                >
                                                    {{ order | getFullName }}
                                                </span>
                                            </td>
                                            <td class="pl-0">
                                                <span
                                                    class="font-weight-regular d-block font-size-lg"
                                                >
                                                    {{
                                                        order.total_points
                                                            | formatNumber
                                                    }}
                                                </span>
                                            </td>
                                            <td class="pl-0">
                                                <span
                                                    class="font-weight-regular d-block font-size-lg"
                                                >
                                                    {{
                                                        order.order_status_text
                                                    }}
                                                </span>
                                            </td>
                                            <td class="text-left pr-0">
                                                <v-tooltip bottom>
                                                    <template
                                                        v-slot:activator="{
                                                            on,
                                                            attrs
                                                        }"
                                                    >
                                                        <v-btn
                                                            v-on="on"
                                                            class="btn btn-icon btn-light btn-sm"
                                                            @click="
                                                                orderDetail(
                                                                    order.id
                                                                )
                                                            "
                                                        >
                                                            <span
                                                                class="svg-icon svg-icon-md svg-icon-primary"
                                                            >
                                                                <!--begin::Svg Icon-->
                                                                <inline-svg
                                                                    style=" pointer-events: none;"
                                                                    src="/media/svg/icons/Navigation/Up-right.svg"
                                                                ></inline-svg>
                                                                <!--end::Svg Icon-->
                                                            </span>
                                                        </v-btn>
                                                    </template>
                                                    <span>View Order</span>
                                                </v-tooltip>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td
                                            class="pa-5 text-center"
                                            colspan="5"
                                        >
                                            <span
                                                class="d-block font-weight-regular font-size-lg"
                                            >
                                                {{ $getConst("NO_DATA_MSG") }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Body-->
                    <!--end::Advance Table Widget 2-->
                </div>
            </div>
        </div>

        <!--        <div class="row">
            <div class="col-xxl-12">

                <div
                    class="card card-custom card-stretch card-stretch-half gutter-b"
                >
                    <div class="card-body d-flex flex-column p-0">
                        <div
                            class="d-flex align-items-center justify-content-between card-spacer flex-grow-1"
                        >
                            <div class="d-flex flex-column mr-2">
                                <a
                                    href="#"
                                    class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5"
                                >
                                    User Activity
                                </a>
                                <span class="text-muted font-weight-bold mt-2">
                                    {{
                                        `${
                                            dashboardData.users
                                                .users_activity_chart.label[0]
                                        } - ${
                                            dashboardData.users
                                                .users_activity_chart.label[
                                                dashboardData.users
                                                    .users_activity_chart.label
                                                    .length - 1
                                            ]
                                        }`
                                    }}
                                </span>
                            </div>

                        </div>

                        <apexchart
                            ref="areaChart"
                            class="card-rounded-bottom"
                            :options="chartOptions"
                            :series="series"
                            type="area"
                        ></apexchart>

                    </div>

                </div>

            </div>
        </div>-->

        <div class="row">
            <div class="col-xxl-12">
                <!--begin::Stats Widget 7-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Body-->
                    <div class="card-body d-flex flex-column p-0">
                        <div
                            class="d-flex align-items-center justify-content-between card-spacer flex-grow-1"
                        >
                            <div class="d-flex flex-column mr-2">
                                <p
                                    class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5"
                                >
                                    Orders Status
                                </p>
                                <span class="text-muted font-weight-bold mt-2">
                                    This Month
                                </span>
                            </div>
                            <!--                            <span class="symbol symbol-light-success symbol-45">-->
                            <!--                                <span class="symbol-label font-weight-bolder font-size-h6">+57</span>-->
                            <!--                            </span>-->
                        </div>
                        <!--begin::Chart-->
                        <div
                            class="align-center p-15"
                            v-if="
                                dashboardData.orders &&
                                    dashboardData.orders.orders_pie_chart &&
                                    dashboardData.orders.orders_pie_chart.data
                            "
                        >
                            <apexchart
                                ref="pieChart"
                                type="pie"
                                height="300"
                                class="card-rounded-bottom"
                                :options="chartOptionsPie"
                                :series="
                                    dashboardData.orders.orders_pie_chart.data
                                "
                            ></apexchart>
                        </div>
                        <!--end::Chart-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Stats Widget 7-->
            </div>
        </div>
        <error-modal v-model="errorDialog" :errorArr="errorArr"></error-modal>
        <!--End:: Dashboard-->
    </div>
</template>
<script src="./dashboard.js"></script>
<style>
.onHoverCursor {
    cursor: pointer;
}
</style>
