<template>
    <div>
        <v-container fluid class="">
            <v-card class="m-b-20">
                <v-flex class="grey-bg p-t-20 p-b-20">
                    <form method="POST" name="permission_form" role="form" enctype="multipart/form-data"
                          id="permission_form">
                        <v-layout row wrap class="pl-5 pr-5">
                            <v-flex lg12 md12 sm12 xs12>
                                <v-select class="mb-5 mt-7" v-getPermissionsByRole="$getConst('ROLEPERMISSION')"
                                          :items="roleList"
                                          item-value="id"
                                          item-text="name"
                                          name="role_id"
                                          v-model="role_id"
                                          label="Role"
                                          @change="getPermissions"
                                          persistent-hint
                                ></v-select>
                            </v-flex>
                        </v-layout>
                        <v-container v-if="permissions.length > 0" v-setUnsetPermissionToRole="$getConst('ROLEPERMISSION')">
                            <!-- Stack the columns on mobile by making one full-width and the other half-width -->
                            <v-row v-for="permission in permissions" :key="permission.id">
                                <v-col cols="12" md="12" class="p-0">
                                    <v-divider class="m-0"></v-divider>
                                </v-col>
                                <v-col cols="12" md="2">
                                    <h6 class="pa-2">{{permission.label}}</h6>
                                </v-col>
                                <v-col cols="12" md="10" sm="12">
                                    <v-row>
                                        <v-col cols="12" md="2" sm="4" xs="12" class="p-lg-0" v-for="(subPermission,index) in permission.sub_permissions"
                                               :key="subPermission.id">
                                            <v-checkbox
                                                class="mt-0"
                                                type="checkbox"
                                                v-model="subPermission.is_permission"
                                                @change="editPermission(subPermission)"
                                                true-value="1"
                                                false-value="0"
                                                :disabled="role_id == 1"
                                                :label="subPermission.label"
                                                v-setUnsetPermissionToRole="$getConst('ROLEPERMISSION')"
                                            ></v-checkbox>
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-container>
                    </form>
                </v-flex>
            </v-card>
        </v-container>
        <error-modal :errorArr="errorArr" v-model="errorDialog"></error-modal>
    </div>

</template>

<script src="./permission.js"></script>
