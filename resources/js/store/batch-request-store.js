import {HTTP} from "../common_services/api-services";
import qs from 'qs'

var baseUrl = '/api/v1/batch_request';
var authBaseUrl = '/api/v1/auth_batch_request';

function mapParams(param) {
    return param;
}
export default {
    namespaced: true,
    state: {},
    mutations: {},
    actions: {
        getBatchProduct({commit, rootState}, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl, {
                    params: {
                        request: mapParams(param),
                        noAuth : true
                    },
                    paramsSerializer: params => {
                        return qs.stringify(params)
                    }
                }).then(response => {
                    var obj = response.data.response;
                    commit('brandStore/setList', obj.brandList.data, {root: true});
                    commit('categoryStore/setParentCategoryList', obj.categoryList.data, {root: true});
                    commit('catalogueStore/setList', obj.catalogueList.data, {root: true});
                    Object.keys(obj).forEach(function (key) {
                        var val = obj[key];
                        if (!val.hasOwnProperty('data')) {
                            return reject(response.data.response);
                        }
                    });
                    resolve(response);
                }).catch(e => {
                    reject(e);
                })
            })
        },
        getBatchUser({commit, rootState}, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl, {
                    params: {
                        request: mapParams(param),
                    },
                    paramsSerializer: params => {
                        return qs.stringify(params)
                    }
                }).then(response => {
                    var obj = response.data.response;
                    commit('categoryStore/setParentCategoryList', obj.categoryList.data, {root: true});
                    Object.keys(obj).forEach(function (key) {
                        var val = obj[key];
                        if (!val.hasOwnProperty('data')) {
                            return reject(response.data.response);
                        }
                    });
                    resolve(response);
                }).catch(e => {
                    reject(e);
                })
            })
        },
        getBatchVoucher({commit, rootState}, param) {
            return new Promise((resolve, reject) => {
                HTTP.get(baseUrl, {
                    params: {
                        request: mapParams(param),
                        noAuth : true
                    },
                    paramsSerializer: params => {
                        return qs.stringify(params)
                    }
                }).then(response => {
                    var obj = response.data.response;
                    commit('userStore/setUserList', obj.userList.data, {root: true});
                    commit('categoryStore/setParentCategoryList', obj.categoryList.data, {root: true});
                    Object.keys(obj).forEach(function (key) {
                        var val = obj[key];
                        if (!val.hasOwnProperty('data')) {
                            return reject(response.data.response);
                        }
                    });
                    resolve(response);
                }).catch(e => {
                    reject(e);
                })
            })
        },
    },
    getters: {}
}
