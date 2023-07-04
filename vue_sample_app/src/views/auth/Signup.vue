<script setup>
import { computed, ref } from "@vue/reactivity";
import FormField from "../../components/forms/FormField.vue";
import FormButton from "../../components/forms/FormButton.vue";
import { useStore } from "vuex";
import { _axios } from "../../axios";

const store = useStore();
const newUser = ref({ email: "", password: "", name: "" });
const signup = () => store.dispatch("users/signup", newUser.value);
</script>
<template>
  <div class="grid gap-y-3">
    <h3 class="leading-none text-2xl mb-3">Регистрация</h3>
    <FormField
      fieldType="text"
      fieldName="name"
      fieldTitle="Имя"
      fieldPlaceholder="ваше имя"
      v-model:name="newUser.name"
    />
    <FormField
      fieldType="email"
      fieldName="email"
      fieldTitle="E-mail"
      fieldPlaceholder="ваш@email.com"
      v-model:email="newUser.email"
    />
    <FormField
      fieldType="password"
      fieldName="password"
      fieldTitle="Пароль"
      fieldPlaceholder="ваш пароль"
      v-model:password="newUser.password"
    />
    <FormButton buttonTitle="Создать новый аккаунт" @clickOnButton="signup" />
    <router-link :to="{ name: 'Signin' }">Вход</router-link>
  </div>
</template>