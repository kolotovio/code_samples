<template>
  <div class="row">
    <div class="col">
      <v-select
        v-model="selectedColumns"
        :items="searchColumns"
        v-on:change="searchItems"
        label="Колонки для поиска"
        multiple
        return-object
      >
        <template v-slot:selection="{ item }">
          <v-chip small>
            <span>{{ item.text }}</span>
          </v-chip>
        </template>
      </v-select>
    </div>
    <div class="col col-5">
      <v-text-field v-model="searchStr" label="Поисковая строка" @input="searchItems" clearable></v-text-field>
    </div>
  </div>
</template>

<script>
import debounce from 'lodash/debounce'

export default {
  name: 'TableTopSearch',
  props: ['currentRoute', 'searchColumns', 'searchSettings'],
  data: vm => ({
    selectedColumns: [],
    searchStr: '',
    isLoading: false,
  }),
  mounted() {
    this.searchStr = this.searchSettings.value
    this.selectedColumns = this.searchColumns.filter(item => this.searchSettings.columns.includes(item.value))
  },
  methods: {
    search: debounce(function (result) {
      this.$emit('search', result)
    }, 1000),
    searchItems() {
      if (!this.selectedColumns.length || !this.searchStr) return

      let result = {
        columns: [],
        value: this.searchStr,
      }

      for (var index in this.selectedColumns) {
        result.columns.push(this.selectedColumns[index].value)
      }

      this.search(result)
    },
  },
  watch: {
    searchStr: function (newValue, oldValue) {
      if ((oldValue !== '' || oldValue !== null) && newValue === null) {
        this.search({})
      }
    },
  },
}
</script>
