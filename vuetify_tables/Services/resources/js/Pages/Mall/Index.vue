<template>
  <AppLayout>
    <template #content>
      <v-container fluid>
        <v-row>
          <v-col>
            <h1 class="text-h5">Торговые центры</h1>
          </v-col>
        </v-row>
        <Table
          v-bind="malls"
        />
      </v-container>
    </template>
  </AppLayout>
</template>

<script>
import AppLayout from 'platform@/Views/AppLayout.vue'
import Table from 'platform-components@/DataTable/Table.vue'

export default {
  name: 'MallIndexComponent',
  components: { AppLayout, Table },
  props: ['malls'],
    beforeMount() {
        this.removeHistoryUrls();
    }
}
</script>
