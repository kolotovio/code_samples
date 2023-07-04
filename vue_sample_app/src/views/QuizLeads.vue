<script setup>
import { computed } from "@vue/reactivity";
import { onBeforeMount } from "@vue/runtime-core";
import { useStore } from "vuex";

const store = useStore();
const leads = computed(() => store.getters["leads/getLeads"]);
onBeforeMount(() => store.dispatch("leads/fetchLeads"));
</script>
<template>Quiz Leads</template>