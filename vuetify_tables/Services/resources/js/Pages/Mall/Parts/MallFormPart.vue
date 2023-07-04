<template>
  <v-form v-if="form && form.errors">
    <v-text-field
      outlined
      name="name"
      label="Название"
      type="text"
      v-model="form.name"
      :error-messages="form.errors.name"
    />
    <v-text-field
      outlined
      name="id_1c"
      label="Код 1С"
      type="text"
      v-model="form.id_1c"
      :error-messages="form.errors.id_1c"
    />
  </v-form>
</template>

<script>
export default {
  name: 'MallFormPartComponent',
  props: {
    value: {
      default: () => {},
    },
  },
  data: () => ({
    form: null,
  }),
  mounted() {
    if (!this.value) {
      this.form = this.$inertia.form({
        name: '',
        id_1c: null,
      })
    } else {
      this.form = this.$inertia.form(this.value)
    }
    this.$emit('input', this.form)
  },
}
</script>
