<template>
    <ul class="menu-nav">
        <router-link
            to="/dashboard"
            v-slot="{ href, navigate, isActive, isExactActive }"
        >
            <li
                v-index="$getConst('DASHBOARD')"
                aria-haspopup="true"
                data-menu-toggle="hover"
                class="menu-item"
                :class="[
                    isActive && 'menu-item-active',
                    isExactActive && 'menu-item-active'
                ]"
            >
                <a :href="href" class="menu-link" @click="navigate">
                    <i class="menu-icon flaticon2-architecture-and-city"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
        </router-link>
        <li
            aria-haspopup="true"
            data-menu-toggle="hover"
            v-index="$getConst('USER')"
            class="menu-item menu-item-submenu"
            :class="{
                'menu-item-open':
                    hasActiveChildren('users') ||
                    hasActiveChildren('user-group')
            }"
        >
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon flaticon-users" />
                <span class="menu-text">User Management</span>
                <i class="menu-arrow" />
            </a>
            <div class="menu-submenu">
                <span class="menu-arrow" />
                <ul class="menu-subnav">
                    <li aria-haspopup="true" class="menu-item menu-item-parent">
                        <span class="menu-link">
                            <span class="menu-text">User Management</span>
                        </span>
                    </li>

                    <router-link
                        v-slot="{ href, navigate, isActive, isExactActive }"
                        to="/user-group"
                    >
                        <li
                            v-index="$getConst('USERGROUP')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                                isActive && 'menu-item-active',
                                isExactActive && 'menu-item-active'
                            ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span />
                                </i>
                                <span class="menu-text"
                                    >Manage User Groups</span
                                >
                            </a>
                        </li>
                    </router-link>
                    <router-link
                        v-slot="{ href, navigate, isActive, isExactActive }"
                        to="/users"
                    >
                        <li
                            v-index="$getConst('USER')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                                isActive && 'menu-item-active',
                                isExactActive && 'menu-item-active'
                            ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span />
                                </i>
                                <span class="menu-text">Manage Users</span>
                            </a>
                        </li>
                    </router-link>
                </ul>
            </div>
        </li>
        <li
            aria-haspopup="true"
            data-menu-toggle="hover"
            class="menu-item menu-item-submenu"
            :class="{
                'menu-item-open':
                    hasActiveChildren('category') ||
                    hasActiveChildren('brand') ||
                    hasActiveChildren('catalogue')
            }"
        >
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon flaticon2-list-2" />
                <span class="menu-text">Catalogue Management</span>
                <i class="menu-arrow" />
            </a>
            <div class="menu-submenu">
                <span class="menu-arrow" />
                <ul class="menu-subnav">
                    <li aria-haspopup="true" class="menu-item menu-item-parent">
                        <span class="menu-link">
                            <span class="menu-text">Catalogue Management</span>
                        </span>
                    </li>
                    <router-link
                        v-slot="{ href, navigate, isActive, isExactActive }"
                        to="/catalogue"
                    >
                        <li
                            v-index="$getConst('CATALOGUE')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                                isActive && 'menu-item-active',
                                isExactActive && 'menu-item-active'
                            ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span />
                                </i>
                                <span class="menu-text">Manage Catalogue</span>
                            </a>
                        </li>
                    </router-link>
                    <router-link
                        v-slot="{ href, navigate, isActive, isExactActive }"
                        to="/category"
                    >
                        <li
                            v-index="$getConst('CATEGORY')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                                isActive && 'menu-item-active',
                                isExactActive && 'menu-item-active'
                            ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span />
                                </i>
                                <span class="menu-text">Manage Categories</span>
                            </a>
                        </li>
                    </router-link>
                    <router-link
                        v-slot="{ href, navigate, isActive, isExactActive }"
                        to="/brand"
                    >
                        <li
                            v-index="$getConst('BRAND')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                                isActive && 'menu-item-active',
                                isExactActive && 'menu-item-active'
                            ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span />
                                </i>
                                <span class="menu-text">Manage Brands</span>
                            </a>
                        </li>
                    </router-link>
                    <router-link
                        v-slot="{ href, navigate, isActive, isExactActive }"
                        to="/product-catalogue"
                    >
                        <li
                            v-index="$getConst('PRODUCT')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                                isActive && 'menu-item-active',
                                isExactActive && 'menu-item-active'
                            ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span />
                                </i>
                                <span class="menu-text">Manage Products</span>
                            </a>
                        </li>
                    </router-link>
                </ul>
            </div>
        </li>
        <router-link
            to="/voucher"
            v-slot="{ href, navigate, isActive, isExactActive }"
        >
            <li
                v-index="$getConst('VOUCHER')"
                aria-haspopup="true"
                data-menu-toggle="hover"
                class="menu-item"
                :class="[
                    isActive && 'menu-item-active',
                    isExactActive && 'menu-item-active'
                ]"
            >
                <a :href="href" class="menu-link" @click="navigate">
                    <i class="menu-icon flaticon-file-2"></i>
                    <span class="menu-text">Voucher Management</span>
                </a>
            </li>
        </router-link>
        <router-link
            to="/orders"
            v-slot="{ href, navigate, isActive, isExactActive }"
        >
            <li
                v-index="$getConst('ORDER')"
                aria-haspopup="true"
                data-menu-toggle="hover"
                class="menu-item"
                :class="[
                    isActive && 'menu-item-active',
                    isExactActive && 'menu-item-active'
                ]"
            >
                <a :href="href" class="menu-link" @click="navigate">
                    <i class="menu-icon flaticon2-layers-2"></i>
                    <span class="menu-text">Order Management</span>
                </a>
            </li>
        </router-link>

        <router-link
            to="/admin"
            v-slot="{ href, navigate, isActive, isExactActive }"
        >
            <li
                v-index="$getConst('ADMINUSER')"
                aria-haspopup="true"
                data-menu-toggle="hover"
                class="menu-item"
                :class="[
                    isActive && 'menu-item-active',
                    isExactActive && 'menu-item-active'
                ]"
            >
                <a :href="href" class="menu-link" @click="navigate">
                    <i class="menu-icon flaticon-user"></i>
                    <span class="menu-text">Admin Management</span>
                </a>
            </li>
        </router-link>

        <router-link
            to="/contact-us"
            v-slot="{ href, navigate, isActive, isExactActive }"
        >
            <li
                v-index="$getConst('CONTACTUS')"
                aria-haspopup="true"
                data-menu-toggle="hover"
                class="menu-item"
                :class="[
                    isActive && 'menu-item-active',
                    isExactActive && 'menu-item-active'
                ]"
            >
                <a :href="href" class="menu-link" @click="navigate">
                    <i class="menu-icon flaticon-user"></i>
                    <span class="menu-text">Contact Us Management</span>
                </a>
            </li>
        </router-link>
    </ul>
</template>

<script>
export default {
    name: "KTMenu",
    methods: {
        hasActiveChildren(match) {
            return this.$route["path"].indexOf(match) !== -1;
        }
    }
};
</script>
