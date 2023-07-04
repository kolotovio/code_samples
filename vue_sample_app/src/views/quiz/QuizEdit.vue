<script setup>
import { computed } from "@vue/reactivity";
import { onBeforeMount, onBeforeUpdate } from "@vue/runtime-core";
import { useRoute } from "vue-router";
import { useStore } from "vuex";

const store = useStore();
const route = useRoute();
const quiz = computed(() => store.getters["quizzes/getQuiz"]);
onBeforeMount(() => store.commit("quizzes/setQuiz", route.params.id));
</script>
<template>
  <div
    class="
      rounded
      bg-indigo-900
      grid grid-flow-col
      justify-evenly
      items-center
      gap-x-5
      leading-none
      text-indigo-100 text-sm text-center
      py-3
    "
  >
    <div>Стартовая страница</div>
    <div>|</div>
    <div>Вопросы и Ответы</div>
    <div>|</div>
    <div>settigns?</div>
    <div>|</div>
    <div>Форма обратной связи</div>
    <div>|</div>
    <div>
      <router-link
        :to="{ name: 'QuizPublishSettings' }"
        class="hover:text-white"
        >Настройка публикации</router-link
      >
    </div>
  </div>
  <router-view />
</template>