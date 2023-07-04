<template>
  <v-toolbar dense flat>
    <v-spacer></v-spacer>
    <v-item-group>
      <v-row>
        <v-col>
          <v-btn v-if="showDeleteBtn" depressed @click="$emit('delete')">Удалить элемент</v-btn>
          <v-btn depressed @click.prevent="create">Добавить элемент</v-btn>
        </v-col>
      </v-row>
    </v-item-group>
  </v-toolbar>
</template>

<script>
export default {
  name: 'TableToolbarComponent',
  props: ['route', 'showDeleteBtn'],
  methods: {
    create() {
      return this.$inertia.get(
        this.$route(this.route.route, {
          ...this.getRouteParams(),
        })
      )
    },
    getRouteParams() {
      return this.route.params ?? {}
    },
  },
}
</script>
