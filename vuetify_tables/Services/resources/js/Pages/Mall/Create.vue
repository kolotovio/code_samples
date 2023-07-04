<template>
  <AppLayout>
    <template #content>
      <v-container fluid>
        <v-row>
          <v-col>
            <h1 class="text-h5">Торговые центры – добавление</h1>
          </v-col>
        </v-row>
        <v-toolbar flat rounded color="indigo lighten-5" class="my-5">
          <v-btn depressed color="info" @click.prevent="back">Назад</v-btn>
          <v-spacer />
        </v-toolbar>
        <v-row>
          <v-col lg="6" class="mx-auto">
            <v-card outlined>
              <v-card-title>Элемент</v-card-title>
              <v-card-text>
                <MallFormPart v-model="form" />
              </v-card-text>
              <v-card-actions>
                <FormToolbarButtons @storeData="save" @cancel="back" />
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </template>
  </AppLayout>
</template>

<script>
import AppLayout from 'platform@/Views/AppLayout.vue'
import MallFormPart from 'platform@/Pages/Mall/Parts/MallFormPart.vue'
import FormToolbarButtons from 'platform@/Components/Forms/FormToolbarButtons.vue'
export default {
  name: 'MallCreateComponent',
  components: { AppLayout, FormToolbarButtons, MallFormPart },
  props: ['routes'],
  data: () => ({
    form: null,
  }),
  methods: {
    back() {
        this.redirectToBackUrl(this.routes.index, this.$route(this.routes.index));
    },
    save() {
      this.form.post(this.$route(this.routes.store))
    },
  },
}
</script>
