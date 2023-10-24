<template>
  <span v-show="errorList.has(validationField)" class="help-block">
    <span v-for="item in validatonArray" :key="item.key">
      <span style="color: red;" v-if="getErrorRule(validationField) == item.key">{{item.value}}</span>
    </span>
  </span>

</template>

<script>
    import CommonServices from '../common_services/common.js';

    export default {
        props: [
            "validationField",
            "errorList",
            "validatonArray",
        ],
        mixins: [CommonServices]
    };
</script>
