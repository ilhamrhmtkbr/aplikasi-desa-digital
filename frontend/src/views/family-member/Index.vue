<script setup>
import {storeToRefs} from "pinia";
import {onMounted, ref, watch} from "vue";
import {debounce} from "lodash/function.js";
import {useFamilyMemberStore} from "@/stores/family-member.js";
import Alert from "@/components/ui/Alert.vue";
import {can} from "@/helpers/permissionHelper.js"
import {useAuthStore} from "@/stores/auth.js";
import {calculateAge} from "@/helpers/formatHelper.js";

const authStore = useAuthStore();
const {user} = storeToRefs(authStore)

const stores = useFamilyMemberStore()
const {data, loading, meta, success} = storeToRefs(stores)
const {fetchAllPaginated} = stores

const serverOptions = ref({
  page: 1,
  row_per_page: 10
})

const filters = ref({
  search: null
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
  <header class="flex items-center justify-between">
    <h1 class="font-semibold text-2xl leading-[30px]">Anggota Keluarga</h1>
    <router-link :to="{name:'familyMemberAdd'}" class="flex items-center rounded-2xl py-4 px-6 gap-[10px] bg-desa-dark-green">
      <img src="@/assets/images/icons/add-square-white.svg" class="flex size-6 shrink-0" alt="icon">
      <p class="font-medium text-white">Add New</p>
    </router-link>
  </header>
  <section id="Kepala-Keluarga" class="flex flex-col gap-6 p-6 bg-white rounded-3xl">
    <h2 class="font-medium leading-5 text-desa-secondary">Kepala Keluarga (1)</h2>
    <div class="data rounded-2xl border border-desa-background p-6 flex items-center justify-between">
      <div class="name flex items-center gap-3 min-w-[240px]">
        <div class="flex size-[64px] shrink-0 rounded-full overflow-hidden bg-desa-foreshadow">
          <img :src="user?.head_of_family?.profile_picture" class="w-full h-full object-cover" alt="photo" />
        </div>
        <div class="flex flex-col gap-[6px]">
          <h3 class="font-semibold text-lg leading-[22.5px] line-clamp-1">{{ user?.name }}</h3>
          <div class="flex items-center gap-1">
            <img src="@/assets/images/icons/briefcase-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
            <p class="font-medium leading-5 text-desa-secondary line-clamp-1">{{ user?.head_of_family?.occupation }}</p>
          </div>
        </div>
      </div>
      <div class="nik flex flex-col gap-[6px] min-w-[155px]">
        <div class="flex items-center gap-1">
          <img src="@/assets/images/icons/keyboard-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
          <h3 class="font-medium leading-[17.5px] text-sm text-desa-secondary">NIK</h3>
        </div>
        <p class="font-semibold leading-5">{{ user?.head_of_family?.identify_number }}</p>
      </div>
      <div class="umur flex flex-col gap-[6px] min-w-[92px]">
        <div class="flex items-center gap-1">
          <img src="@/assets/images/icons/timer-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
          <h3 class="font-medium leading-[17.5px] text-sm text-desa-secondary">Umur</h3>
        </div>
        <p class="font-semibold leading-5">{{ calculateAge(user?.head_of_family?.date_of_birth) }} Tahun</p>
      </div>
      <router-link to="" class="shrink-0">
        <div class="rounded-2xl px-6 py-[18px] bg-desa-black font-medium leading-5 text-white">Manage</div>
      </router-link>
    </div>
  </section>
  <section id="Istri" class="flex flex-col gap-6 p-6 bg-white rounded-3xl"
           v-if="data.filter(member => member.relation === 'wife').length > 0">
    <h2 class="font-medium leading-5 text-desa-secondary">
      Istri ({{ data.filter(member => member.relation === 'wife').length }})
    </h2>
    <div class="data rounded-2xl border border-desa-background p-6 flex items-center justify-between"
         v-for="wife in data.filter(member => member.relation === 'wife')">

      <div class="name flex items-center gap-3 min-w-[240px]">
        <div class="flex size-[64px] shrink-0 rounded-full overflow-hidden bg-desa-foreshadow">
          <img :src="wife.profile_picture" class="w-full h-full object-cover" alt="photo" />
        </div>

        <div class="flex flex-col gap-[6px]">
          <h3 class="font-semibold text-lg leading-[22.5px] line-clamp-1">
            {{ wife.user?.name }}
          </h3>
          <div class="flex items-center gap-1">
            <img src="@/assets/images/icons/briefcase-secondary-green.svg" alt="icon"
                 class="size-[18px] shrink-0" />
            <p class="font-medium leading-5 text-desa-secondary line-clamp-1">
              {{ wife.occupation }}
            </p>
          </div>
        </div>
      </div>
      <div class="nik flex flex-col gap-[6px] min-w-[155px]">
        <div class="flex items-center gap-1">
          <img src="@/assets/images/icons/keyboard-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
          <h3 class="font-medium leading-[17.5px] text-sm text-desa-secondary">NIK</h3>
        </div>
        <p class="font-semibold leading-5">{{ wife.identify_number }}</p>
      </div>
      <div class="umur flex flex-col gap-[6px] min-w-[92px]">
        <div class="flex items-center gap-1">
          <img src="@/assets/images/icons/timer-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
          <h3 class="font-medium leading-[17.5px] text-sm text-desa-secondary">Umur</h3>
        </div>
        <p class="font-semibold leading-5">{{ calculateAge(wife.date_of_birth) }} Tahun</p>
      </div>
      <router-link :to="{name: 'familyMemberManage', params: {id: wife.id}}" class="shrink-0">
        <div class="rounded-2xl px-6 py-[18px] bg-desa-black font-medium leading-5 text-white">Manage</div>
      </router-link>
    </div>
  </section>
  <section id="Anak" class="flex flex-col gap-6 p-6 bg-white rounded-3xl"
           v-if="data.filter(member => member.relation === 'child').length > 0">
    <h2 class="font-medium leading-5 text-desa-secondary">
      Anak ({{ data.filter(member => member.relation === 'child').length }})
    </h2>
    <div class="data rounded-2xl border border-desa-background p-6 flex items-center justify-between"
         v-for="child in data.filter(member => member.relation === 'child')">
      <div class="name flex items-center gap-3 min-w-[240px]">
        <div class="flex size-[64px] shrink-0 rounded-full overflow-hidden bg-desa-foreshadow">
          <img src="@/assets/images/photos/kk-photo-3.png" class="w-full h-full object-cover" alt="photo" />
        </div>
        <div class="flex flex-col gap-[6px]">
          <h3 class="font-semibold text-lg leading-[22.5px] line-clamp-1">{{ child?.user?.name }}</h3>
          <div class="flex items-center gap-1">
            <img src="@/assets/images/icons/briefcase-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
            <p class="font-medium leading-5 text-desa-secondary line-clamp-1">{{ child.occupation }}</p>
          </div>
        </div>
      </div>
      <div class="nik flex flex-col gap-[6px] min-w-[155px]">
        <div class="flex items-center gap-1">
          <img src="@/assets/images/icons/keyboard-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
          <h3 class="font-medium leading-[17.5px] text-sm text-desa-secondary">NIK</h3>
        </div>
        <p class="font-semibold leading-5">{{ child.identify_number }}</p>
      </div>
      <div class="umur flex flex-col gap-[6px] min-w-[92px]">
        <div class="flex items-center gap-1">
          <img src="@/assets/images/icons/timer-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
          <h3 class="font-medium leading-[17.5px] text-sm text-desa-secondary">Umur</h3>
        </div>
        <p class="font-semibold leading-5">{{ calculateAge(child.date_of_birth) }} Tahun</p>
      </div>
      <router-link :to="{name: 'familyMemberManage', params: {id: child.id}}" class="shrink-0">
        <div class="rounded-2xl px-6 py-[18px] bg-desa-black font-medium leading-5 text-white">Manage</div>
      </router-link>
    </div>
  </section>
</template>
