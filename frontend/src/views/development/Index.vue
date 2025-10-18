<script setup>
import {storeToRefs} from "pinia";
import {onMounted, ref, watch} from "vue";
import {debounce} from "lodash/function.js";
import {useDevelopmentStore} from "@/stores/development.js";
import Pagination from "@/components/ui/Pagination.vue";
import CardList from "@/components/development/CardList.vue";
import Alert from "@/components/ui/Alert.vue";
import {can} from "@/helpers/permissionHelper.js"
import {useAuthStore} from "@/stores/auth.js";

const stores = useDevelopmentStore()
const {data, loading, meta, success} = storeToRefs(stores)
const {fetchAllPaginated} = stores

const authStore = useAuthStore()
const {user} = authStore

const serverOptions = ref({
  page: 1,
  row_per_page: 10
})

const filters = ref({
  search: null,
  status: null
})

const fetchData = () => {
  fetchAllPaginated({params: {...serverOptions.value, ...filters.value}})
}

const debounceFetch = debounce(fetchData, 500)

onMounted(() => {
  fetchData()
})

watch(serverOptions, () => {
  fetchData()
}, {deep: true})

watch(filters, () => {
  debounceFetch()
}, {deep: true})
</script>

<template>
  <Alert :message="success" @close="success = null"/>
  <div id="Header" class="flex items-center justify-between">
    <h1 class="font-semibold text-2xl">Pembangunan Desa</h1>
    <router-link :to="{name:'developmentAdd'}"
                 class="flex items-center rounded-2xl py-4 px-6 gap-[10px] bg-desa-dark-green"
                 v-if="can('development-create')">
      <img src="@/assets/images/icons/add-square-white.svg" class="flex size-6 shrink-0" alt="icon">
      <p class="font-medium text-white">Add New</p>
    </router-link>
  </div>
  <section id="TabButtons" class="w-full p-1 bg-desa-foreshadow rounded-full grid grid-cols-2 gap-3"
    v-if="user?.role === 'head-of-family'">
    <button type="button" data-content="All" :class="['tab-btn', 'group', { active: !filters.status }]">
      <div
          class="group-[.active]:bg-desa-dark-green group-[.active]:text-white rounded-full py-[18px] flex justify-center items-center"
          @click="filters.status = null"
      >
        <span>Semua Pembangungan</span>
      </div>
    </button>

    <button type="button" data-content="My-applications" :class="['tab-btn', 'group', { active: filters.status === 'my-applications' }]">
      <div
          class="group-[.active]:bg-desa-dark-green group-[.active]:text-white rounded-full py-[18px] flex justify-center items-center"
          @click="filters.status = 'my-applications'"
      >
        <span>My Applications</span>
      </div>
    </button>
  </section>

  <section id="List-Pembangunan-Desa" class="flex flex-col gap-[14px]">
    <form id="Page-Search" class="flex items-center justify-between">
      <div class="flex flex-col gap-3 w-[370px] shrink-0">
        <label class="relative group peer w-full valid">
          <input v-model="filters.search" type="text" placeholder="Cari nama pembangunan desa"
                 class="appearance-none outline-none w-full h-14 rounded-2xl ring-[1.5px] ring-desa-background focus:ring-desa-black py-4 pl-12 pr-6 gap-2 font-medium placeholder:text-desa-secondary transition-all duration-300">
          <div class="absolute transform -translate-y-1/2 top-1/2 left-4 flex size-6 shrink-0">
            <img src="@/assets/images/icons/box-search-secondary-green.svg"
                 class="size-6 hidden group-has-[:placeholder-shown]:flex" alt="icon">
            <img src="@/assets/images/icons/box-search-black.svg"
                 class="size-6 flex group-has-[:placeholder-shown]:hidden" alt="icon">
          </div>
        </label>
      </div>
      <div class="options flex items-center gap-4">
        <div class="flex items-center gap-[10px]">
          <span class="font-medium leading-5">Show</span>
          <div class="relative">
            <select v-model="serverOptions.row_per_page" name="" id=""
                    class="appearance-none outline-none w-full h-14 rounded-2xl ring-[1.5px] ring-desa-background focus:ring-desa-black py-4 px-6 pr-[52px] gap-2 font-medium placeholder:text-desa-secondary transition-all duration-300">
              <option value="5" selected>5 Entries</option>
              <option value="10">10 Entries</option>
              <option value="20">20 Entries</option>
              <option value="30">30 Entries</option>
              <option value="40">40 Entries</option>
              <option value="50">50 Entries</option>
            </select>
            <img src="@/assets/images/icons/arrow-down-black.svg"
                 class="flex size-6 shrink-0 absolute transform -translate-y-1/2 top-1/2 right-6" alt="icon">
          </div>
        </div>
        <button type="button"
                class="flex items-center gap-1 h-14 w-fit rounded-2xl border border-desa-background bg-white py-4 px-6">
          <img src="@/assets/images/icons/filter-black.svg" class="flex size-6 shrink-0" alt="icon">
          <span class="font-medium leading-5">Filter</span>
        </button>
      </div>
    </form>
    <CardList
        v-for="(item, index) in data"
        :key="item.id"
        :item="item"
    />
  </section>
  <Pagination :server-options="serverOptions" :meta="meta" v-if="!loading"/>
</template>
