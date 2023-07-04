<script setup>
</script>
<template>
  <div
    class="
      absolute
      left-0
      top-0
      z-50
      w-full
      min-h-screen
      overflow-hidden
      flex
      items-center
      justify-center
      bg-gray-900 bg-opacity-40
    "
  >
    <div>
      <div class="relative">
        <button
          class="absolute bottom-0 left-full"
          @click.stop="$emit('emitButtonClickClose')"
        >
          Close
        </button>
      </div>
      <div class="bg-white rounded">
        <slot name="body" />
      </div>
    </div>
  </div>
</template>