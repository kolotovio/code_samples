<script setup>
import { computed } from "@vue/reactivity";
import { useStore } from "vuex";
import Header from "./views/inc/Header.vue";
const store = useStore();
const isLogged = computed(() => store.getters["users/isLogged"]);
</script>

<template>
  <div v-if="isLogged" class="w-full h-screen flex flex-col">
    <Header />
    <div class="container mx-auto py-5">
      <router-view />
    </div>
  </div>
  <div v-else class="w-full h-screen flex items-center justify-center">
    <router-view />
  </div>
</template>
