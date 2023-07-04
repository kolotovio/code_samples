<script>
export default {
  inheritAttrs: false,
};
</script>
<script setup>
import { computed, useAttrs } from "@vue/runtime-core";

const attrs = useAttrs();
const emit = defineEmits();
const props = defineProps({
  fieldType: { type: String, default: "text" },
  fieldName: { type: String },
  fieldTitle: { type: String },
  fieldPlaceholder: { type: String },
});
const fieldValue = computed({
  get: () => attrs[props.fieldName],
  set: (value) => {
    emit(`update:${props.fieldName}`, value);
  },
});
</script>
<template>
  <div class="flex flex-col items-start" v-if="props.fieldType != 'checkbox'">
    <label :for="props.fieldName" class="text-sm leading-none mb-1">{{
      props.fieldTitle
    }}</label>
    <input
      :type="props.fieldType"
      :name="props.fieldName"
      :id="props.fieldName"
      :placeholder="props.fieldPlaceholder"
      class="border rounded leading-none px-3 py-2 w-full"
      v-model="fieldValue"
    />
  </div>
  <div v-else class="flex flex-row items-center">
    <input
      :type="props.fieldType"
      :name="props.fieldName"
      :id="props.fieldName"
      v-model="fieldValue"
    />
    <label :for="props.fieldName" class="ml-2">{{ props.fieldTitle }}</label>
  </div>
</template>