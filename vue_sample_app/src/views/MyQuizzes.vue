<script setup>
import { computed, ref } from "@vue/reactivity";
import FormButton from "../components/forms/FormButton.vue";
import Modal from "../components/Modal.vue";
import FormField from "../components/forms/FormField.vue";
import { useStore } from "vuex";
import { onBeforeMount } from "@vue/runtime-core";
import { useRouter } from "vue-router";

const store = useStore();
const isShown = ref(false);
const quizName = ref("");
const addNewQuiz = async () => {
  await store.dispatch("quizzes/storeNewQuiz", quizName.value);
  toggleModal(false);
};

const toggleModal = (state) => {
  if (!state) {
    quizName.value = "";
  }
  isShown.value = state;
};
const quizzes = computed(() => store.getters["quizzes/getQuizzes"]);
onBeforeMount(() => store.dispatch("quizzes/fetchQuizzes"));
</script>
<template>
  <Modal v-if="isShown" @emitButtonClickClose="toggleModal(false)">
    <template #body>
      <div>
        <FormField
          fieldType="text"
          fieldName="quiz_name"
          fieldTitle="Название Квиза"
          fieldPlaceholder="Введите любое название"
          v-model:quiz_name="quizName"
        />
        <button @click="toggleModal(false)">Отменить</button>
        <button @click="addNewQuiz">Сохранить</button>
      </div>
    </template>
  </Modal>
  <FormButton buttonTitle="Добавить квиз" @clickOnButton="toggleModal(true)" />
  <div v-if="quizzes.length">
    <ul>
      <li v-for="quiz in quizzes">
        <router-link :to="{ name: 'QuizEdit', params: { id: quiz.id } }">{{
          quiz.name
        }}</router-link>
      </li>
    </ul>
  </div>
</template>