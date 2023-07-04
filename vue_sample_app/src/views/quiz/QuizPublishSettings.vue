<script setup>
import { computed } from "@vue/reactivity";
import { useStore } from "vuex";
import FormField from "../../components/forms/FormField.vue";
import FormButton from "../../components/forms/FormButton.vue";

const store = useStore();
const quiz = computed(() => store.getters["quizzes/getQuiz"]);
const quizUpdate = () => store.dispatch("quizzes/updateQuiz", quiz.value);
const quizDelete = () => store.dispatch("quizzes/deleteQuiz", quiz.value.id);
</script>
<template>
  <div>
    <div class="grid gap-y-5 mb-5 justify-items-start">
      <FormField
        fieldType="text"
        fieldName="quiz_slug"
        fieldTitle="Ссылка на квиз"
        fieldPlaceholder="Укажите ссылку на ваш квиз"
        v-model:quiz_slug="quiz.slug"
      />
      <FormField
        fieldType="text"
        fieldName="quiz_title"
        fieldTitle="Заголовок для окна браузера"
        fieldPlaceholder="Укажите заголовок"
        v-model:quiz_title="quiz.title"
      />
      <FormField
        fieldType="checkbox"
        fieldName="quiz_active"
        fieldTitle="Активность квиза"
        v-model:quiz_active="quiz.is_active"
      />
    </div>
    <FormButton buttonTitle="Сохранить" @clickOnButton="quizUpdate" />
    <FormButton buttonTitle="Удалить" @clickOnButton="quizDelete" />
  </div>
</template>