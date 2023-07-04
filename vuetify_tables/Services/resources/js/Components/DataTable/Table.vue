<template>
  <div>
    <template v-if="!items">
      <v-skeleton-loader type="table-heading, table-tbody, table-tfoot" />
    </template>
    <template v-else>
      <v-data-table
        show-select
        must-sort
        v-model="selected"
        :items="items"
        :headers="tableHeaders"
        :footer-props="footerProps"
        :items-per-page="pagination.perPage"
        :server-items-length="pagination.total"
        :page="pagination.currentPage"
        :multi-sort="defaultMultiSort"
        :sort-by.sync="defaultSortColumn"
        :sort-desc.sync="defaultSortDesc"
        @click:row="edit"
        @update:items-per-page="onCountChanges"
        @update:page="onPageChanges"
        @update:sort-by="onChangeSort"
        @update:sort-desc="onChangeSortDesc"
        v-bind="$attrs"
        v-on="$listeners"
        fixed-header
        :height="items.length > 7 ? '70vh' : '100%'"
      >
        <template v-slot:top>
          <div>
            <v-select v-model="tableHeaders" :items="headers" label="Колонки таблицы" multiple return-object>
              <template v-slot:selection="{ item }">
                <v-chip small>
                  <span>{{ item.text }}</span>
                </v-chip>
              </template>
            </v-select>
          </div>
            <template v-if="searchHeaders && searchHeaders.length > 0">
                <TableTopSearch :current-route="routes.index.route" @search="searchItems" :search-columns="searchHeaders" :search-settings="settings.search" />
            </template>
          <TableTopToolbar :route="routes.create" :showDeleteBtn="showDeleteBtn" @delete="deleteItems" />
        </template>
        <template v-slot:[`item.active`]="{ item }">
          <td class="text-start">{{ prepareBoolText(item.active) }}</td>
        </template>
        <template v-slot:[`item.issuing_center`]="{ item }">
          <td class="text-start">{{ prepareBoolText(item.issuing_center) }}</td>
        </template>
        <template v-slot:[`item.online`]="{ item }">
          <td class="text-start">{{ prepareBoolText(item.online) }}</td>
        </template>
        <template v-slot:[`item.is_for_reserve`]="{ item }">
          <td class="text-start">{{ prepareBoolText(item.is_for_reserve) }}</td>
        </template>
        <template v-slot:[`item.monobrand`]="{ item }">
          <td class="text-start">{{ prepareBoolText(item.monobrand) }}</td>
        </template>
        <template v-slot:[`item.try_on`]="{ item }">
          <td class="text-start">{{ prepareBoolText(item.try_on) }}</td>
        </template>
        <template v-slot:[`item.outlet`]="{ item }">
          <td class="text-start">{{ prepareBoolText(item.outlet) }}</td>
        </template>
        <template v-slot:[`item.gender_use`]="{ item }">
          <td class="text-start">{{ prepareBoolText(item.gender_use) }}</td>
        </template>
        <!-- Pass on all named slots -->
        <slot v-for="slot in Object.keys($slots)" :name="slot" :slot="slot" />
        <!-- Pass on all scoped slots -->
        <template v-for="slot in Object.keys($scopedSlots)" :slot="slot" slot-scope="scope">
          <slot :name="slot" v-bind="scope" />
        </template>
      </v-data-table>
      <div>
        <v-row>
          <v-col>
            <v-pagination
              v-model="pagination.currentPage"
              :length="paginatorLength"
              :total-visible="7"
              @input="onPageChanges"
            />
          </v-col>
        </v-row>
      </div>
    </template>
  </div>
</template>

<script>
import TableTopToolbar from './TableTopToolbar.vue'
import isArray from 'lodash/isArray'
import TableTopSearch from "./TableTopSearch";

export default {
  components: {TableTopSearch, TableTopToolbar },
  props: ['items', 'headers', 'pagination', 'settings', 'routes', 'tab', 'back', 'searchHeaders'],
  data: vm => ({
    selected: [],
    tableHeaders: [],
    footerProps: {
      itemsPerPageOptions: vm.settings.defaultPerPageItems,
    },
    defaultSortColumn: null, //['id'],
    defaultSortDesc: null, //[false],
    defaultMultiSort: false,
  }),
  beforeMount() {
    if (localStorage.getItem(this.storageTableHeadersKey) == null) {
      this.tableHeaders = this.headers
    } else {
      this.tableHeaders = JSON.parse(localStorage.getItem(this.storageTableHeadersKey))
    }
    this.defaultSortColumn = Object.keys(this.settings.defaultSort)
    this.defaultSortDesc = Object.values(this.settings.defaultSort).map(el => {
      if (el === 'asc') {
        return false
      }
      return true
    })
  },
  mounted() {
    this.addUrlToLocalStorage()
  },
  computed: {
    storageTableCurrentPageKey() {
      return this.routes.index.route + '.currentPage'
    },
    storageTableHeadersKey() {
      return this.routes.index.route + '.headers'
    },
    sort() {
      if (!isArray(this.defaultSortColumn)) {
        return {
          [this.defaultSortColumn]: this.defaultSortDesc ? 'desc' : 'asc',
        }
      }

      let result = {}
      for (let index in this.defaultSortColumn) {
        result[this.defaultSortColumn[index]] = this.defaultSortDesc[index] ? 'desc' : 'asc'
      }
      return result
    },
    paginatorLength() {
      return Math.ceil(this.pagination.total / this.pagination.perPage)
    },
    showDeleteBtn() {
      return this.selected.length > 0
    },
  },
  watch: {
    tableHeaders(val) {
      localStorage.setItem(this.storageTableHeadersKey, JSON.stringify(val))
    },
    pagination: {
      handler(val) {
        if (val.currentPage) {
          localStorage.setItem(this.storageTableCurrentPageKey, val.currentPage)
        }
      },
      deep: true,
    },
  },
  methods: {
    addUrlToLocalStorage() {
      let query = {}
      if (this.tab !== undefined) {
        query.tab = this.tab
      }
      this.setCurrentUrlToLocalStorage(this.routes.index.route, query)
    },
    prepareBoolText(val) {
      return val ? 'Да' : 'Нет'
    },
    getRouteParams(route) {
      if (this.routes[route] === undefined) {
        throw new Error("Route: '" + route + "' not found")
      }

      return this.routes[route].params ?? {}
    },
    deleteItems() {
      let arr = this.selected.map(item => item.id)
      let backUrl = this.getBackUrl(
        this.routes.index.route,
        this.$route(this.routes.index.route, {
          ...this.getRouteParams('index'),
        })
      )

      this.$inertia.delete(
        this.$route(this.routes.delete.route, {
          _query: { ids: arr },
          backUrl: backUrl,
          ...this.getRouteParams('delete'),
        }),
        {
          onFinish: () => this.clearSelected(),
        }
      )
    },
    clearSelected() {
      this.selected = []
    },
    edit(item) {
      this.$inertia.get(
        this.$route(this.routes.edit.route, {
          [this.getNamedId()]: item.id,
          ...this.getRouteParams('edit'),
        })
      )
    },
    getNamedId() {
      return this.routes.edit.namedId ?? 'id'
    },
    onCountChanges(val) {
      this.$inertia.get(
        this.$route(this.routes.index.route, {
          _query: this.getCommonQuery({perPage: val}),
          ...this.getRouteParams('index'),
        })
      )
    },
    onPageChanges(val) {
      this.$inertia.get(
        this.$route(this.routes.index.route, {
          _query: this.getCommonQuery({page: val}),
          ...this.getRouteParams('index'),
        })
      )
    },
    onChangeColumnSort() {
      this.$inertia.get(
        this.$route(this.routes.index.route, {
          _query: this.getCommonQuery(),
          ...this.getRouteParams('index'),
        })
      )
    },
    onChangeSort(val) {
      if (
        (isArray(val) && val[0] === 'activeText') ||
        val[0] === 'issuingCenterText' ||
        val[0] === 'onlineText' ||
        val[0] === 'isForReserveText' ||
        val[0] === 'monobrandText' ||
        val[0] === 'tryOnText' ||
        val[0] === 'outletText'
      ) {
        this.defaultSortColumn = ['active']
        this.defaultSortDesc = [true]
      } else {
        this.defaultSortColumn = val
      }
      this.onChangeColumnSort()
    },
    onChangeSortDesc(val) {
      this.defaultSortDesc = val
      this.onChangeColumnSort()
    },
      searchItems(val) {
          this.settings.search = val;

          this.$inertia.get(
              this.$route(this.routes.index.route, {
                  _query: this.getCommonQuery(),
                  ...this.getRouteParams('index'),
              })
          )
      },
      getCommonQuery(addQuery) {
        var query = {
            page: this.pagination.currentPage,
            perPage: this.pagination.perPage,
            sort: this.sort,
            tab: this.tab,
        }

        if (this.settings.search.value && this.settings.search.columns) {
            query.search = {
                columns: this.settings.search.columns,
                value: this.settings.search.value,
            }
        }
        if (this.tab) {
            query.tab = this.tab;
        }

        for(let index in addQuery) {
            query[index] = addQuery[index];
        }

        return query;
      }
  },
}
</script>
