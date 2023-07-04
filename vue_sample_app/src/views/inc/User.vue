<script setup>
import { computed, ref } from "@vue/reactivity";
import { useStore } from "vuex";

const store = useStore();
const user = computed(() => store.getters["users/getUser"]);
const logout = () => store.dispatch("users/logout");
const isShown = ref(false);
const closeDropdown = () => {
  if (isShown.value) {
    isShown.value = false;
  }
};
</script>
<template>
  <div class="relative">
    <div
      @click="isShown = !isShown"
      v-clickAnyWhere="closeDropdown"
      class="cursor-pointer"
    >
      {{ user?.name }}
    </div>
    <div
      v-if="isShown"
      class="
        absolute
        right-0
        rounded rounded-tr-none
        leading-none
        bg-indigo-100
        whitespace-nowrap
        grid
        gap-y-5
        z-50
      "
    >
      <div class="grid justify-items-start gap-y-1 px-5 mt-4">
        <router-link :to="{ name: 'MyQuizzes' }">Мои Квизы</router-link>
        <router-link :to="{ name: 'QuizLeads' }">Лиды Квизов</router-link>
      </div>
      <div class="flex flex-col px-5">
        <h3
          class="
            uppercase
            text-xs
            font-bold
            text-gray-400
            mb-2
            border-b border-gray-400
            leading-none
            pb-1
          "
        >
          Настройки
        </h3>
        <div class="grid justify-items-start gap-y-1">
          <button>Аккаунт</button>
          <button>Баланс и Оплата</button>
        </div>
      </div>
      <div class="flex flex-col items-start px-5 mb-4">
        <button @click.stop="logout">Выйти</button>
      </div>
    </div>
  </div>
</template>