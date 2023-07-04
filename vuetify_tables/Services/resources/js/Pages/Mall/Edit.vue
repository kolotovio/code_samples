<template>
  <AppLayout>
    <template #content>
      <v-container fluid>
        <v-row>
          <v-col>
            <h1 class="text-h5">{{ pageTitle }}</h1>
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
                <MallFormPart v-if="form" v-model="form" />
              </v-card-text>
              <v-card-actions>
                <FormToolbarButtons isUpdate @updateData="save" @deleteData="remove" @cancel="back" />
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
  name: 'MallEditComponent',
  props: ['item', 'routes'],
  components: { AppLayout, FormToolbarButtons, MallFormPart },
  data: () => ({
    form: null,
    backUrl: null
  }),
  methods: {
    back() {
        this.redirectToUrl(this.backUrl);
    },
    remove() {
      this.$inertia.delete(
        this.$route(this.routes.delete, {
          _query: { ids: [this.item.id], backUrl: this.backUrl },
        })
      )
    },
    save() {
        this.form.transform(data => ({
            ...data,
            backUrl: this.backUrl
        }))

        this.form.put(this.$route(this.routes.update, { id: this.item.id }))
    },
  },
  computed: {
    pageTitle() {
      return `Торговый центр: ${this.item.name} – редактирование`
    },
  },
  mounted() {
    this.form = this.item
    this.backUrl = this.getBackUrl(this.routes.index, this.$route(this.routes.index))
  },
}
</script>
