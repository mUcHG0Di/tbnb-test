<style scoped>
/*
TODO: Convert to @apply
*/

table >>> th {
  font-weight: 500;
  font-size: 0.75rem;
  line-height: 1rem;
  padding-top: 0.75rem;
  padding-bottom: 0.75rem;
  padding-left: 1.5rem;
  padding-right: 1.5rem;
  text-align: left;
  --tw-text-opacity: 1;
  color: rgba(107, 114, 128, var(--tw-text-opacity));
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

table >>> td {
  font-size: 0.875rem;
  line-height: 1.25rem;
  padding-top: 1rem;
  padding-bottom: 1rem;
  padding-left: 1.5rem;
  padding-right: 1.5rem;
  --tw-text-opacity: 1;
  color: rgba(107, 114, 128, var(--tw-text-opacity));
  white-space: nowrap;
}

table >>> tr:hover td {
  --tw-bg-opacity: 1;
  background-color: rgba(249, 250, 251, var(--tw-bg-opacity));
}
</style>

<template>
  <div class="p-2 shadow">
    <div class="flex space-x-4">

      <slot
        name="tableFilter"
        :hasFilters="hasFilters"
        :filters="filters"
        :changeFilterValue="changeFilterValue"
      >
        <TableFilter v-if="hasFilters" :filters="filters" :on-change="changeFilterValue" />
      </slot>

      <slot
        name="tableGlobalSearch"
        :search="search"
        :changeGlobalSearchValue="changeGlobalSearchValue"
      >
        <div class="flex-grow">
          <TableGlobalSearch
            v-if="search && search.global"
            :value="search.global.value"
            :on-change="changeGlobalSearchValue"
          />
        </div>
      </slot>

      <slot
        name="excelExport"
      >
        <div class="relative">
            <button
                type="button"
                aria-haspopup="true"
                class="inline-flex justify-center w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                @click="$emit('exportToExcel')"
            >
                <v-icon small>mdi-file-excel-outline</v-icon>
                <span class="ml-2">Excel</span>
            </button>
        </div>
      </slot>

      <slot
        name="tableAddSearchRow"
        :hasSearchRows="hasSearchRows"
        :search="search"
        :newSearch="newSearch"
        :enableSearch="enableSearch"
      >
        <TableAddSearchRow
          v-if="hasSearchRows"
          :rows="search"
          :new="newSearch"
          :on-add="enableSearch"
        />
      </slot>

      <slot
        name="tableColumns"
        :hasColumns="hasColumns"
        :columns="columns"
        :changeColumnStatus="changeColumnStatus"
      >
        <TableColumns v-if="hasColumns" :columns="columns" :on-change="changeColumnStatus" />
      </slot>
    </div>

    <slot
      name="tableSearchRows"
      :hasSearchRows="hasSearchRows"
      :search="search"
      :newSearch="newSearch"
      :disableSearch="disableSearch"
      :changeSearchValue="changeSearchValue"
    >
      <TableSearchRows
        ref="rows"
        v-if="hasSearchRows"
        :rows="search"
        :new="newSearch"
        :on-remove="disableSearch"
        :on-change="changeSearchValue"
      />
    </slot>

    <slot name="tableWrapper" :meta="meta">
      <TableWrapper :class="{'mt-2': !onlyData}">
        <slot name="table">
          <table class="min-w-full bg-white divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <slot name="head" />
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
              <slot name="body" />
            </tbody>
          </table>
        </slot>

        <slot name="pagination">
          <Pagination :meta="meta" />
        </slot>
      </TableWrapper>
    </slot>
  </div>
</template>

<script>
import Pagination from "@protonemedia/inertiajs-tables-laravel-query-builder/js/Tailwind2/Pagination";
import { Components } from "@protonemedia/inertiajs-tables-laravel-query-builder";
import TableAddSearchRow from "@protonemedia/inertiajs-tables-laravel-query-builder/js/Tailwind2/TableAddSearchRow.vue";
import TableColumns from "@protonemedia/inertiajs-tables-laravel-query-builder/js/Tailwind2/TableColumns.vue";
import TableFilter from "@protonemedia/inertiajs-tables-laravel-query-builder/js/Tailwind2/TableFilter.vue";
import TableGlobalSearch from "@protonemedia/inertiajs-tables-laravel-query-builder/js/Tailwind2/TableGlobalSearch.vue";
import TableSearchRows from "@protonemedia/inertiajs-tables-laravel-query-builder/js/Tailwind2/TableSearchRows.vue";
import TableWrapper from "@protonemedia/inertiajs-tables-laravel-query-builder/js/Tailwind2/TableWrapper.vue";

export default {
  mixins: [Components.Table],

  components: {
    Pagination,
    TableAddSearchRow,
    TableColumns,
    TableFilter,
    TableGlobalSearch,
    TableSearchRows,
    TableWrapper,
  },
};
</script>
