<template>
    <div class="order-detail">
        <v-card>
            <v-card-text>
                <v-layout class="fs">
                    <v-flex xs12 sm12 md12 lg12>
                        <h3 class="mb-4">Order id #{{ model.id }}</h3>
                    </v-flex>
                </v-layout>

                <form
                    method="POST"
                    name="order-status-form"
                    role="form"
                    novalidate
                    autocomplete="off"
                    @submit.prevent="changeOrderStatus()"
                >
                    <v-layout class="fs">
                        <v-flex xs12 sm12 md3 lg3>
                            <v-select
                                label="Status"
                                name="order_status"
                                v-model="model.order_status"
                                :items="orderStatusList"
                                item-text="name"
                                item-value="order_status"
                                clearable
                            ></v-select>
                        </v-flex>
                        <v-flex xs12 sm12 md3 lg4 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                label="Status remark"
                                type="text"
                                name="order_status_remark"
                                v-model="model.order_status_remark"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12 md3 lg3 class="pl-lg-4 pl-sm-0">
                            <v-btn
                                class="btn btn-primary mt-3"
                                type="submit"
                                :loading="statusLoading"
                            >
                                {{ $getConst("ORDER_STATUS_UPDATE_BTN") }}
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </form>
                <v-divider></v-divider>
                <form
                    method="POST"
                    name="delivery-partner-form"
                    role="form"
                    novalidate
                    autocomplete="off"
                    @submit.prevent="changeDeliveryDetail"
                >
                    <ErrorBlockServer
                        :errorMessage="errorMessage"
                    ></ErrorBlockServer>
                    <v-layout class="fs">
                        <v-flex xs12 sm2 md2 lg2>
                            <h6 class="mt-5">Delivery partner</h6>
                        </v-flex>
                        <v-flex xs12 sm3 md3 lg2 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                label="Delivery"
                                type="text"
                                name="courier_name"
                                :disabled="model.order_status == '4'"
                                v-model="model.courier_name"
                                :error-messages="getErrorValue('courier_name')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm3 md3 lg2 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                label="Enter tracking ID"
                                type="text"
                                name="tracking_id"
                                :disabled="model.order_status == '4'"
                                v-model="model.tracking_id"
                                :error-messages="getErrorValue('tracking_id')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm3 md3 lg3 class="pl-lg-4 pl-sm-0">
                            <v-text-field
                                label="Tracking link"
                                type="text"
                                name="courier_link"
                                :disabled="model.order_status == '4'"
                                v-model="model.courier_link"
                                :error-messages="getErrorValue('courier_link')"
                                v-validate="'required'"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12 md3 lg3 class="pl-lg-4 pl-sm-0">
                            <v-btn
                                class="btn btn-primary mt-3"
                                type="submit"
                                :disabled="model.order_status == '4'"
                                :loading="deliveryLoading"
                            >
                                {{ $getConst("ORDER_DELIVERY_UPDATE_BTN") }}
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </form>
                <v-divider></v-divider>
                <template v-for="(product,index) in model.products">
                    <v-layout class="fs mt-7 mb-8" :key="index">
                        <v-flex xs12 sm12 md2 lg1>
                            <img
                                :src="product.featured_image"
                                class="img-responsive"
                                width="80px"
                                height="80px"
                            />
                        </v-flex>
                        <v-flex xs12 sm12 md8 lg9>
                            <h6>{{ product.product_name }}</h6>
                            <h6>{{ product.category_name }}</h6>
                            <h6>Qty: {{ product.quantity }}</h6>
                        </v-flex>
                        <v-flex xs12 sm12 md2 lg2>
                            <h6>{{ product.total_points | formatNumber }}</h6>
                        </v-flex>
                    </v-layout>
                </template>
                <v-layout class="fs mb-6">
                    <v-flex xs12 sm12 md8 lg8 class="address-shipped-block">
                        <h3 class="mb-5">Shipping Address</h3>
                        <table>
                            <tr>
                                <td><h6>Name:</h6></td>
                                <td>
                                    <div class="pl-lg-4 pl-sm-0 text">
                                        {{
                                            model.first_name +
                                                " " +
                                                model.last_name
                                        }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><h6>Address:</h6></td>
                                <td>
                                    <div class="pl-lg-4 pl-sm-0 text">
                                        <div>{{ model.address }}</div>
                                        <div>
                                            {{
                                                model.city +
                                                    ", " +
                                                    model.state +
                                                    "-" +
                                                    model.pin_code
                                            }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><h6>Mobile No.:</h6></td>
                                <td>
                                    <div class="pl-lg-4 pl-sm-0 text">
                                        {{ model.contact_number }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><h6>Email Address:</h6></td>
                                <td>
                                    <div class="pl-lg-4 pl-sm-0 text">
                                        {{ model.email }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </v-flex>
                    <v-flex xs12 sm12 md4 lg4 class="payment-detail-block">
                        <h3 class="mb-5">Payment detail</h3>
                        <div>
                            <h6 class="d-inline-block">Points redeemed:</h6>
                            <div class="pl-lg-4 pl-sm-0 text">
                                {{ model.redeemed_points | formatNumber }}
                            </div>
                        </div>
                        <div>
                            <h6 class="d-inline-block">PG transaction:</h6>
                            <div class="pl-lg-4 pl-sm-0 text">
                                {{ model.payment_amount | formatNumber }}
                            </div>
                        </div>
                        <div>
                            <h6 class="d-inline-block">Ref id#</h6>
                            <div class="pl-lg-4 pl-sm-0 text">
                                {{ model.payment_transaction_id }}
                            </div>
                        </div>
                        <div>
                            <h6 class="d-inline-block">Mode:</h6>
                            <div class="pl-lg-4 pl-sm-0 text">
                                {{ model.payment_mode_text }}
                            </div>
                        </div>
                    </v-flex>
                </v-layout>
                <v-layout>
                    <v-flex xs12 sm12 md12 lg12>
                        <v-btn class="btn btn-primary" @click="orderListing()">
                            Back
                        </v-btn>
                    </v-flex>
                </v-layout>
                <!--                <pre>{{model}} </pre>-->
            </v-card-text>
        </v-card>
    </div>
</template>

<script src="./order-detail.js"></script>
