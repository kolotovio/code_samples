<script>
export default {
  inheritAttrs: false,
};
</script>
<script setup>
const props = defineProps({
  buttonTitle: { type: String },
});
</script>
<template>
  <button
    @click.stop="$emit('clickOnButton')"
    class="
      bg-indigo-600
      text-indigo-50
      rounded
      leading-none
      py-3
      px-4
      font-medium
      text-lg
    "
  >
    {{ props.buttonTitle }}
  </button>
</template>