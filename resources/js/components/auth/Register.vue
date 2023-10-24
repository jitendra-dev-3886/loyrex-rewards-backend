<template>
  <div :class="{ w70 : !isEditMode }">
    <!--begin::Content header-->
    <div
      class="position-absolute top-0 right-0 text-right mt-5 mb-15 mb-lg-0 flex-column-auto justify-content-center py-5 px-10" v-if="!isEditMode"
    >
      <span class="font-weight-bold font-size-3 text-dark-60">
        Already have an account?
      </span>
      <router-link
        class="font-weight-bold font-size-3 ml-2"
        :to="{ name: 'login' }"
      >
        Sign In!
      </router-link>
    </div>
    <!--end::Content header-->

    <!--begin::Signup-->
    <div class="login-form login-signin">
      <div class="text-center mb-10 mb-lg-20" v-if="!isEditMode">
        <h3 class="font-size-h1">Sign Up</h3>
        <p class="text-muted font-weight-semi-bold">
          Enter your details to create your account
        </p>
      </div>

      <!--begin::Form-->
      <v-form class="form" @submit.prevent="onSubmit"  method="POST" role="form" enctype="multipart/form-data" autocomplete="off">
          <ErrorBlockServer :errorMessage="errorMessage"></ErrorBlockServer>
          <v-layout row wrap class="m-0">
              <v-flex xs12 lg6>
                  <v-text-field type="text"
                      name="name"
                      label="Name*"
                      v-model="model.name"
                      :error-messages="getErrorValue('name')"
                      v-validate="'required'"
                  ></v-text-field>
              </v-flex>
              <v-flex xs12 lg6 class="pl-lg-4 pl-sm-0">
                  <v-text-field
                      label="Mobile No*"
                      type="text"
                      name="mobile_no"
                      v-model="model.mobile_no"
                      :error-messages="getErrorValue('mobile_no')"
                      v-validate="'required|min:10|max:10'"
                  ></v-text-field>
              </v-flex>
              <v-flex xs12 lg6>
                  <v-text-field type="text"
                      name="email"
                      v-model="model.email"
                      label="Email*"
                      :error-messages="getErrorValue('email')"
                      :disabled="isEditMode"
                      v-validate="'required|email'" autocomplete="email"
                  ></v-text-field>
              </v-flex>
              <v-flex xs12 lg6 class="pl-lg-4 pl-sm-0" v-show="!isEditMode">
                  <v-text-field type="password"
                      name="password"
                      label="Password*"
                      v-model="model.password"
                      :error-messages="getErrorValue('password')"
                      v-validate="isEditMode ? '' :'required|min:6'" autocomplete="new-password"
                  ></v-text-field>
              </v-flex>
              <v-flex xs12 lg6 class="pl-lg-4 pl-sm-0" v-show="isEditMode">
                  <v-select
                      label="Role*"
                      name="role_id"
                      v-model="model.role_id"
                      :items="roleList"
                      item-text="name"
                      item-value="id"
                      :error-messages="getErrorValue('role_id')"
                      v-validate="'required'"
                  ></v-select>
              </v-flex>
              <v-flex xs12 lg12>
              <v-layout row wrap class="m-0">
              <v-flex xs12 lg6>
              <template row wrap v-if="model.profile != ''">
                  <v-flex lg6>
                      Existing Profile Image :
                  </v-flex><v-flex lg6>
                  <a :href="model.profile" target="_blank"><img :src="model.profile" width="auto" height="70" class="mb-4"></a>
              </v-flex>
              </template>
              <v-flex xs12>
                  <v-file-input
                      attach
                      v-model="model.profile_upload"
                      :rules="rules"
                      :label="!isEditMode ? 'Profile Image*' : 'Profile Image'"
                      @click:clear="model.profile_upload=null"
                      id="profile" name="profile" ref="profile"
                      :error-messages="getErrorValue('profile')"
                      v-validate="!isEditMode ? 'required|ext:jpeg,png,jpg,gif,svg|size:4000' : 'ext:jpeg,png,jpg,gif,svg|size:4000'">
                  </v-file-input>
              </v-flex>
              </v-flex>
              <v-flex xs12 lg6 class="pl-lg-4 pl-sm-0">
              <v-flex xs12 v-if="isEditMode" class="mb-3">
                  <a @click="onImageModal()">View Gallery Images</a>
              </v-flex>
              <v-flex xs12>
                  <v-file-input multiple name="gallery" v-model="model.gallery"
                                :error-messages="getErrorValue('gallery')"
                                v-validate="isEditMode ? 'ext:jpeg,png,jpg,gif,svg|size:4000' :'required|ext:jpeg,png,jpg,gif,svg|size:4000'" :label="!isEditMode ? 'Gallery*' : 'Gallery'"></v-file-input>
              </v-flex>
              </v-flex>
              </v-layout>
              </v-flex>
              <v-flex xs12 lg6>
                  <v-menu
                      v-model="dobMenu"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      class="display-inline-blc" min-width="unset"
                  >
                      <template v-slot:activator="{ on }">
                          <v-text-field
                              label="DOB*"
                              readonly
                              v-on="on"
                              single-line
                              v-model="computedDateFormatted"
                              :error-messages="getErrorValue('dob')"
                              v-validate="'required'" name="dob"
                          ></v-text-field>
                      </template>
                      <v-date-picker v-model="model.dob" :max="todayDate"
                                     @input="menu = false"></v-date-picker>
                  </v-menu>
              </v-flex>
              <v-flex xs12 lg6 class="pl-lg-4 pl-sm-0">
                  <v-text-field type="text"
                      name="address"
                      label="Address*"
                      v-model="model.address"
                      :error-messages="getErrorValue('address')"
                      v-validate="'required'"
                  ></v-text-field>
              </v-flex>
              <v-flex xs12 lg4>
                  <v-select
                      label="Country*"
                      name="country_id"
                      v-model="model.country_id"
                      :items="countryList"
                      item-text="name"
                      item-value="id"
                      :error-messages="getErrorValue('country_id')"
                      v-validate="'required'"
                      @change="getState()"
                  ></v-select>
              </v-flex>
              <v-flex xs12 lg4 class="pl-lg-4 pl-sm-0">
                  <v-select
                      label="State*"
                      name="state_id"
                      v-model="model.state_id"
                      :items="stateList"
                      item-text="name"
                      item-value="id"
                      :error-messages="getErrorValue('state_id')"
                      v-validate="'required'"
                      @change="getCity()"
                  ></v-select>
              </v-flex>
              <v-flex xs12 lg4 class="pl-lg-4 pl-sm-0">
                  <v-select
                      label="City*"
                      name="city_id"
                      v-model="model.city_id"
                      :items="cityList"
                      item-text="name"
                      item-value="id"
                      :error-messages="getErrorValue('city_id')"
                      v-validate="'required'"
                  ></v-select>
              </v-flex>
              <v-flex xs12 lg4 class="mt-3">
                  <label>Gender*</label>
                  <v-radio-group row v-model="model.gender"
                                 name="gender"
                                 :error-messages="getErrorValue('gender')"
                                 v-validate="'required'" class="p-0 mt-1">
                      <v-radio label="Male" value="1"></v-radio>
                      <v-radio label="Female" value="0"></v-radio>
                  </v-radio-group>
              </v-flex>
              <v-flex xs12 lg8 class="mt-3 pl-lg-4 pl-sm-0">
                  <label>Hobby*</label>
                  <v-row class="ml-0">
                      <template v-for="(hList,index) in hobbyList" >
                          <v-checkbox :id="'hobby'+hList.id"
                                      :key="'hobby'+index"
                                      :label="hList.name"
                                      :name="'hobby'+hList.id"
                                      :value="hList.id"
                                      v-model="model.hobby" class="mt-0 mr-2"
                                      v-validate="'required'" :error="getErrorCount('hobby' +hList.id)"></v-checkbox>
                      </template>
                  </v-row>
              </v-flex>
          </v-layout>

        <!--begin::Action-->
        <div class="form-group d-flex flex-wrap flex-center">
            <v-btn class="btn btn-primary font-weight-bold px-9 py-4 my-3 font-size-3 mx-4" type="submit"
                   :loading="isSubmitting" ref="submitBtn">{{isEditMode ?  $getConst('BTN_UPDATE') : $getConst('BTN_SUBMIT') }}</v-btn>
          <v-btn v-if="isEditMode"
            @click="onCancel()"
            class="btn btn-grey font-weight-bold px-9 py-4 my-3 font-size-3 mx-4 ml-3"
          >
              {{ $getConst('BTN_CANCEL') }}
          </v-btn>
        </div>
        <!--end::Action-->
      </v-form>
      <!--end::Form-->
    </div>
    <!--end::Signup-->
      <snackbar v-model="snackbar"></snackbar>
      <gallery-image-modal v-model="imageModal"></gallery-image-modal>
  </div>
</template>

<script src="./register.js"></script>
