<script setup>
import { ref } from "@vue/reactivity";
import { useStore } from "vuex";
import FormField from "../../components/forms/FormField.vue";
import FormButton from "../../components/forms/FormButton.vue";

const store = useStore();
const user = ref({ email: "", password: "" });
const Signin = () => store.dispatch("users/signin", user.value);
</script>
<template>
  <div class="grid gap-y-3">
    <h3 class="leading-none text-2xl mb-3">Вход</h3>
    <FormField
      fieldType="email"
      fieldName="email"
      fieldTitle="E-mail"
      fieldPlaceholder="ваш@email.com"
      v-model:email="user.email"
    />
    <FormField
      fieldType="password"
      fieldName="password"
      fieldTitle="Пароль"
      fieldPlaceholder="ваш пароль"
      v-model:password="user.password"
    />
    <FormButton buttonTitle="Войти" @clickOnButton="Signin" />
    <router-link :to="{ name: 'Signup' }">Регистрация</router-link>
  </div>
</template>