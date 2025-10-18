<script setup>
import {onMounted, ref, watch} from "vue";
import {storeToRefs} from "pinia";
import {useRoute} from "vue-router";
import router from "@/router/index.js";
import {useDevelopmentStore} from "@/stores/development.js";
import ModalDelete from "../../components/ui/ModalDelete.vue";
import Alert from "@/components/ui/Alert.vue";
import {calculateAge, formatClientTimezone, formatRupiah, getDateDifference} from "@/helpers/formatHelper.js";
import {can} from "@/helpers/permissionHelper.js"
import {useAuthStore} from "@/stores/auth.js";
import {useFamilyMemberStore} from "@/stores/family-member.js";
import {useDevelopmentApplicantStore} from "@/stores/development-applicant.js";

const showModal = ref(false)
const route = useRoute()

const data = ref({})

const stores = useDevelopmentStore()
const {loading, success} = storeToRefs(stores)
const {destroy, fetchById} = stores

const authStore = useAuthStore()
const {user} = storeToRefs(authStore)

const fetch = async () => {
  data.value = await fetchById(route.params.id)
  formCreate.value.development_id = data.value.id
}

onMounted(fetch)

async function handleDelete() {
  await destroy(route.params.id)
  router.push({name: 'development'})
}

const showModalCreate = ref(false)
const formCreate = ref({
  development_id: null,
  user_id: null
})
const selectedUser = ref({
  name: null
})
const familyMemberStore = useFamilyMemberStore()
const {data: familyMemberData} = storeToRefs(familyMemberStore)
const {fetchAll: fetchFamilyMember} = familyMemberStore
const developmentApplicantStore = useDevelopmentApplicantStore()
const {create} = developmentApplicantStore

async function handleSubmitCreate() {
  await create(formCreate.value)
}

const handleSelectApplicant = (user) => {
  formCreate.value.user_id = user.id
  selectedUser.value = user
  showModalCreate.value = false
}

watch(user, newUser => {
  if (newUser?.role === 'head-of-family'){
    fetchFamilyMember()
  }
}, {immediate: true})
</script>

<template>
  <Alert :message="success" @close="success = null"/>
  <div id="Header" class="flex items-center justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex gap-1 items-center leading-5 text-desa-secondary">
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Pembangunan Desa</p>
        <span>/</span>
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">detail Pembangunan desa</p>
      </div>
      <h1 class="font-semibold text-2xl">Detail Pembangunan Desa</h1>
    </div>
    <div class="flex items-center gap-3">
      <button @click="showModal = true" data-modal="Modal-Delete"
              class="flex items-center rounded-2xl py-4 px-6 gap-[10px] bg-desa-red">
        <p class="font-medium text-white">Hapus Data</p>
        <img src="@/assets/images/icons/trash-white.svg" class="flex size-6 shrink-0" alt="icon">
      </button>
      <router-link :to="{name: 'developmentEdit', params: {id: route.params.id}}"
                   class="flex items-center rounded-2xl py-4 px-6 gap-[10px] bg-desa-black"
                   v-if="can('development-edit')">
        <p class="font-medium text-white">Ubah Data</p>
        <img src="@/assets/images/icons/edit-white.svg" class="flex size-6 shrink-0" alt="icon">
      </router-link>
    </div>
  </div>
  <div class="flex flex-col gap-[14px]" v-if="user?.role === 'admin'">
    <section id="Informasi" class="flex flex-col rounded-3xl p-6 gap-6 bg-white">
      <p class="font-medium leading-5 text-desa-secondary">Informasi Pembangunan</p>
      <div class="flex items-center justify-between">
        <div class="flex w-[120px] h-[100px] shrink-0 rounded-2xl overflow-hidden bg-desa-foreshadow">
          <img :src="data.thumbnail" class="w-full h-full object-cover" alt="photo">
        </div>
        <div class="badge rounded-full p-3 gap-2 flex justify-center shrink-0 bg-desa-yellow"
             v-if="data.status === 'ongoing'">
          <span class="font-semibold text-xs text-white uppercase">Sedang Berjalan</span>
        </div>
        <div class="badge rounded-full p-3 gap-2 flex justify-center shrink-0 bg-desa-green"
             v-if="data.status === 'completed'">
          <span class="font-semibold text-xs text-white uppercase">Selesai</span>
        </div>
      </div>
      <div class="flex flex-col gap-[6px] w-full">
        <p class="font-semibold text-lg leading-[22.5px] line-clamp-1">{{ data.title }}</p>
        <p class="font-medium text-sm text-desa-secondary">
          Penanggung Jawab:
          <span class="font-medium text-base text-desa-dark-green">
                                       {{ data.person_in_charge }}
                                    </span>
        </p>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex items-center justify-between">
        <div class="flex items-center w-full gap-3">
          <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-red/10 items-center justify-center">
            <img src="@/assets/images/icons/wallet-3-red.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1 w-full">
            <p class="font-semibold text-xl leading-[22.5px] text-desa-red">Rp {{ formatRupiah(data.amount) }}</p>
            <span class="font-medium text-desa-secondary">
                                            Dana Pembangunan
                                        </span>
          </div>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="grid grid-cols-2 gap-3">
        <div class="flex items-center w-full gap-3">
          <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-foreshadow items-center justify-center">
            <img src="@/assets/images/icons/calendar-2-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1 w-full">
            <p class="font-semibold text-xl leading-[22.5px] text-desa-dark-green">
              {{ formatClientTimezone(data.start_date) }}</p>
            <span class="font-medium text-desa-secondary">
                                            Tanggal Pelaksanaan
                                        </span>
          </div>
        </div>
        <div class="flex items-center w-full gap-3 justify-end">
          <div class="flex flex-col gap-1 w-full text-right">
            <p class="font-semibold text-xl leading-[22.5px] text-desa-dark-green">
              {{ formatClientTimezone(data.end_date) }}</p>
            <span class="font-medium text-desa-secondary">
                                            Perkiraan Selesai
                                        </span>
          </div>
          <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-foreshadow items-center justify-center">
            <img src="@/assets/images/icons/calendar-2-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="grid grid-cols-2 gap-3">
        <div class="flex items-center w-full gap-3">
          <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-blue/10 items-center justify-center">
            <img src="@/assets/images/icons/profile-2user-blue.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1 w-full">
            <p class="font-semibold text-xl leading-[22.5px] text-desa-blue">
              {{ data.development_applicants?.length ?? '0' }}</p>
            <span class="font-medium text-desa-blue">
                                            Total Pelamar
                                        </span>
          </div>
        </div>
        <div class="flex items-center w-full gap-3 justify-end">
          <div class="flex flex-col gap-1 w-full text-right">
            <p class="font-semibold text-xl leading-[22.5px] text-desa-blue">192 Hari</p>
            <span class="font-medium text-desa-secondary">
                                            Days Needed
                                        </span>
          </div>
          <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-yellow/10 items-center justify-center">
            <img src="@/assets/images/icons/clock-yellow.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex flex-col gap-3">
        <p class="font-medium text-sm text-desa-secondary">Tentang Pembangunan</p>
        <p class="font-medium leading-8">{{ data.description }}</p>
      </div>
    </section>
    <section id="Applicants" class="flex flex-col rounded-3xl p-6 gap-6 bg-white">
      <div class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary">Pengajuan Applicants</p>
        <div id="Tabs-Button" class="grid grid-cols-4 gap-3">
          <button data-content="All" class="tab-btn group active">
            <div
                class="flex items-center justify-center rounded-full border border-desa-background py-[14px] px-[18px] group-hover:bg-desa-black group-[.active]:bg-desa-black transition-setup">
                                            <span
                                                class="font-medium leading-5 text-desa-secondary group-hover:text-white group-[.active]:text-white transition-setup">
                                                SEMUA
                                            </span>
            </div>
          </button>
          <button data-content="All" class="tab-btn group">
            <div
                class="flex items-center justify-center rounded-full border border-desa-background py-[14px] px-[18px] group-hover:bg-desa-black group-[.active]:bg-desa-black transition-setup">
                                            <span
                                                class="font-medium leading-5 text-desa-secondary group-hover:text-white group-[.active]:text-white transition-setup">
                                                MENUNGGU
                                            </span>
            </div>
          </button>
          <button data-content="All" class="tab-btn group">
            <div
                class="flex items-center justify-center rounded-full border border-desa-background py-[14px] px-[18px] group-hover:bg-desa-black group-[.active]:bg-desa-black transition-setup">
                                            <span
                                                class="font-medium leading-5 text-desa-secondary group-hover:text-white group-[.active]:text-white transition-setup">
                                                DITERIMA
                                            </span>
            </div>
          </button>
          <button data-content="All" class="tab-btn group">
            <div
                class="flex items-center justify-center rounded-full border border-desa-background py-[14px] px-[18px] group-hover:bg-desa-black group-[.active]:bg-desa-black transition-setup">
                                            <span
                                                class="font-medium leading-5 text-desa-secondary group-hover:text-white group-[.active]:text-white transition-setup">
                                                DITOLAK
                                            </span>
            </div>
          </button>
        </div>
      </div>
      <div id="Tabs-Content" class="flex flex-col">
        <div id="All" class="flex flex-col gap-6">
          <div v-for="item in data.development_applicants"
               class="card flex flex-col gap-6 rounded-3xl p-6 border border-desa-background bg-white">
            <div class="flex items-center justify-between">
              <p class="flex items-center gap-1">
                <img src="@/assets/images/icons/calendar-2-secondary-green.svg" class="flex size-[18px] shrink-0"
                     alt="icon">
                <span class="font-medium text-sm text-desa-secondary">{{ formatClientTimezone(item.created_at) }}</span>
              </p>
              <div class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-black">
                <span class="font-semibold text-xs text-white uppercase">{{ item.status }}</span>
              </div>
            </div>
            <hr class="border-desa-background">
            <div class="flex items-center gap-6 justify-between">
              <div class="flex items-center gap-3 w-[302px] shrink-0">
                <div class="flex flex-col gap-1">
                  <p class="font-semibold text-lg leading-5 text-desa-black">{{ item.user?.name }}</p>
                </div>
              </div>
              <div class="flex items-center gap-3 justify-end shrink-0"
                   v-if="item.status === 'pending' && can('development-applicant-edit') && can('development-edit')">
                <button
                    class="flex items-center w-[120px] justify-center shrink-0 gap-[10px] rounded-2xl py-4 px-6 bg-desa-red/10">
                  <span class="font-medium text-desa-red">Tolak</span>
                </button>
                <button
                    class="flex items-center w-[120px] justify-center shrink-0 gap-[10px] rounded-2xl py-4 px-6 bg-desa-dark-green">
                  <span class="font-medium text-white">Setuju</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <div class="flex gap-[14px]" v-if="user?.role === 'head-of-family'">
    <div class="w-[calc(545/1000*100%)] h-fit shrink-0 rounded-2xl bg-white p-6 flex flex-col gap-6">
      <h2 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Informasi Pembangunan</h2>
      <section id="Hero" class="flex items-center justify-between">
        <div class="flex justify-center items-center w-[120px] h-[100px] shrink-0 rounded-3xl overflow-hidden">
          <img :src="data.thumbnail" alt="image" class="size-full object-cover"/>
        </div>
        <span class="p-3 rounded-full bg-desa-orange font-semibold text-xs leading-[15px] text-white">
          {{ data.status }}
        </span>
      </section>
      <section id="Title" class="flex flex-col gap-[6px]">
        <h3 class="font-semibold text-xl leading-7">{{ data.name }}</h3>
        <div class="flex items-center gap-1">
          <p class="font-medium leading-5 text-desa-secondary">Penanggung Jawab:</p>
          <p class="font-medium leading-5 text-desa-dark-green">{{ data.person_in_charge }}</p>
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Points" class="flex flex-col gap-6">
        <div class="point flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="p-[14px] shrink-0 bg-[#FF507017] rounded-2xl">
              <img src="@/assets/images/icons/wallet-3-red.svg" alt="icon" class="size-6 shrink-0"/>
            </div>
            <div class="flex flex-col gap-1">
              <p class="font-semibold text-lg leading-[22.5px] text-desa-red">Rp {{ formatRupiah(data.amount) }}</p>
              <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Dana Pembangunan</h3>
            </div>
          </div>
        </div>
        <hr class="border-desa-background"/>
        <div class="point flex items-center gap-3">
          <div class="p-[14px] shrink-0 bg-[#005CAA17] rounded-2xl">
            <img src="@/assets/images/icons/profile-2user-blue.svg" alt="icon" class="size-6 shrink-0"/>
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-lg leading-[22.5px] text-desa-blue">{{ data.development_applicants?.length }}
              Warga</p>
            <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Total Partisipasi</h3>
          </div>
        </div>
        <hr class="border-desa-background"/>
        <div class="point flex items-center gap-3">
          <div class="p-[14px] shrink-0 bg-desa-foreshadow rounded-2xl">
            <img src="@/assets/images/icons/calendar-2-dark-green.svg" alt="icon" class="size-6 shrink-0"/>
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-lg leading-[22.5px] text-desa-dark-green">
              {{ formatClientTimezone(data.start_date) }}</p>
            <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Tanggal Pelaksanaan</h3>
          </div>
        </div>
        <hr class="border-desa-background"/>
      </section>
      <section id="Tentang-Pembangunan" class="flex flex-col gap-3">
        <h2 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Tentang Pembangunan</h2>
        <p class="font-medium leading-8">{{ data.description }}</p>
      </section>
    </div>
    <div class="flex flex-col gap-6 rounded-2xl flex-1 h-fit bg-white p-6">
      <section id="Applicant-Details" class="flex flex-col gap-6">
        <h2 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Applicant Details</h2>
        <div class="point flex items-center gap-3">
          <div class="p-[14px] shrink-0 bg-desa-foreshadow rounded-2xl">
            <img src="@/assets/images/icons/timer-dark-green.svg" alt="icon" class="size-6 shrink-0"/>
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-lg leading-[22.5px] text-desa-dark-green">
              {{ getDateDifference(data.end_date, data.start_date) }} Hari</p>
            <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Days Needed</h3>
          </div>
        </div>
        <hr class="border-desa-background"/>
        <div class="point flex items-center gap-3">
          <div class="p-[14px] shrink-0 bg-desa-foreshadow rounded-2xl">
            <img src="@/assets/images/icons/calendar-tick-dark-green.svg" alt="icon" class="size-6 shrink-0"/>
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-lg leading-[22.5px] text-desa-dark-green">
              {{ formatClientTimezone(data.end_date) }}</p>
            <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Perkiraan Selesai</h3>
          </div>
        </div>
        <hr class="border-desa-background"/>
        <div class="point flex items-center gap-3">
          <div class="p-[14px] shrink-0 bg-desa-foreshadow rounded-2xl">
            <img src="@/assets/images/icons/dollar-square-dark-green.svg" alt="icon" class="size-6 shrink-0"/>
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-lg leading-[22.5px] text-desa-dark-green">Pembayaran diatur di balai desa.</p>
            <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Bayaran Kerja</h3>
          </div>
        </div>
        <hr class="border-desa-background"/>
        <form @submit.prevent="handleSubmitCreate" class="flex flex-col gap-6">
          <button type="button" id="Pelamar-Applicant-Button" class="flex items-center justify-between" @click="showModalCreate = true">
            <div class="flex items-center gap-3">
              <div class="flex items-center justify-center size-[52px] shrink-0 rounded-full bg-desa-foreshadow overflow-hidden">
                <img id="default-Profile-Image" src="@/assets/images/photos/kk-preview.png" alt="image" class="size-full object-cover" />
              </div>
              <div class="flex flex-col gap-1 items-start">
                <p id="default-Profile-Name" class="font-semibold text-lg leading-[22.5px]">Pelamar Applicant</p>
                <div class="flex items-center gap-1">
                  <img id="Icon-If-Filled" src="@/assets/images/icons/briefcase-secondary-green.svg" alt="icon" class="[&.input-is-filled]:block hidden size-[18px] shrink-0" />
                  <h3 id="default-Profile-Status" class="font-medium text-sm leading-[17.5px] text-desa-secondary">{{selectedUser.name}}</h3>
                </div>
              </div>
            </div>
            <img src="@/assets/images/icons/arrow-square-right-secondary-green.svg" alt="icon" class="size-6 shrink-0" />
          </button>
          <hr class="border-desa-background" />
          <button type="submit" class="font-medium leading-5 text-white py-[18px] flex justify-center items-center bg-desa-dark-green rounded-2xl">Confirm & Apply Now</button>
          <!-- modal -->
          <div id="modal" class="fixed inset-0 flex items-center justify-center bg-[#001B1ACC] z-50" v-if="showModalCreate">
            <div class="bg-white rounded-2xl p-4 w-[760px] flex flex-col gap-6">
              <div class="flex items-center justify-between">
                <div class="flex flex-col gap-2">
                  <h3 class="font-semibold text-2xl leading-[30px]">Pilih Pelamar Applicant</h3>
                  <p class="font-medium text-sm leading-[17.5px] text-desa-secondary">Pilih salah satu anggota keluarga</p>
                </div>
                <button @click="showModalCreate = false" id="closeModal" class="py-4 px-6 border border-desa-background rounded-2xl bg-white flex items-center gap-2">
                  <img src="@/assets/images/icons/close-square-secondary-green.svg" alt="icon" class="size-6 shrink-0" />
                  <p class="font-medium leading-5 text-desa-secondary">Tutup</p>
                </button>
              </div>
              <hr class="border-desa-background" />
              <ul id="Profile-List" class="flex flex-col gap-6 max-h-[497px] overflow-y-auto hide-scrollbar px-[1.5px] pb-[2px]">
                <li>
                  <label class="profile flex flex-col gap-3 bg-white rounded-3xl">
                    <h4 class="font-medium leading-[17.5px] text-sm text-desa-secondary">You</h4>
                    <div class="data rounded-2xl border border-desa-background p-6 flex items-center gap-[49px] hover:ring-[1.5px] hover:ring-desa-dark-green transition-all duration-300">
                      <div class="name flex items-center gap-3">
                        <div class="flex size-[64px] shrink-0 rounded-full overflow-hidden bg-desa-foreshadow">
                          <img :src="user?.head_of_family?.profile_picture" class="w-full h-full object-cover" alt="photo" />
                        </div>
                        <div class="flex flex-col gap-[6px]">
                          <h5 class="font-semibold text-lg leading-[22.5px] truncate w-[164px]">{{user?.name}}</h5>
                          <div class="flex items-center gap-1">
                            <img src="@/assets/images/icons/briefcase-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
                            <p class="font-medium leading-5 text-desa-secondary truncate w-[142px]">{{user?.head_of_family?.occupation}}</p>
                          </div>
                        </div>
                      </div>
                      <div class="nik flex flex-col gap-[6px]">
                        <div class="flex items-center gap-1">
                          <img src="@/assets/images/icons/keyboard-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
                          <h5 class="font-medium text-sm leading-[17.5px] text-desa-secondary">NIK</h5>
                        </div>
                        <p class="font-semibold leading-5 truncate w-[155px]">{{ user?.head_of_family?.identify_number }}</p>
                      </div>
                      <div class="umur flex flex-col gap-[6px]">
                        <div class="flex items-center gap-1">
                          <img src="@/assets/images/icons/timer-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
                          <h5 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Umur</h5>
                        </div>
                        <p class="font-semibold leading-5 truncate w-[92px]">{{ calculateAge(user?.head_of_family?.date_of_birth) }} Tahun</p>
                      </div>
                      <input @click="handleSelectApplicant(user)" required type="radio" name="anggota" class="flex size-[30px] shrink-0 accent-desa-dark-green" data-image="assets/images/photos/kk-photo-1.png" data-name="Andi Alif R" data-status="Content Creator" data-nik="2005100592201005" data-umur="32 Tahun" />
                    </div>
                  </label>
                </li>
                <li v-if="familyMemberData.filter(member => member.relation === 'wife').length > 0">
                  <label class="profile flex flex-col gap-3 bg-white rounded-3xl">
                    <h4 class="font-medium leading-[17.5px] text-sm text-desa-secondary">Istri {{familyMemberData.filter(member => member.relation === 'wife').length}}</h4>
                    <div v-for="wife in familyMemberData.filter(member => member.relation === 'wife')" class="data rounded-2xl border border-desa-background p-6 flex items-center gap-[49px] hover:ring-[1.5px] hover:ring-desa-dark-green transition-all duration-300">
                      <div class="name flex items-center gap-3">
                        <div class="flex size-[64px] shrink-0 rounded-full overflow-hidden bg-desa-foreshadow">
                          <img :src="wife.profile_picture" class="w-full h-full object-cover" alt="photo" />
                        </div>
                        <div class="flex flex-col gap-[6px]">
                          <h5 class="font-semibold text-lg leading-[22.5px] truncate w-[164px]">
                            {{ wife.user?.name}}
                          </h5>
                          <div class="flex items-center gap-1">
                            <img src="@/assets/images/icons/briefcase-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
                            <p class="font-medium leading-5 text-desa-secondary truncate w-[142px]">
                              {{wife.occupation}}
                            </p>
                          </div>
                        </div>
                      </div>
                      <div class="nik flex flex-col gap-[6px]">
                        <div class="flex items-center gap-1">
                          <img src="@/assets/images/icons/keyboard-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
                          <h5 class="font-medium text-sm leading-[17.5px] text-desa-secondary">NIK</h5>
                        </div>
                        <p class="font-semibold leading-5 truncate w-[155px]">
                          {{wife.identify_number}}
                        </p>
                      </div>
                      <div class="umur flex flex-col gap-[6px]">
                        <div class="flex items-center gap-1">
                          <img src="@/assets/images/icons/timer-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
                          <h5 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Umur</h5>
                        </div>
                        <p class="font-semibold leading-5 truncate w-[92px]">
                          {{calculateAge(wife.date_of_birth)}} Tahun</p>
                      </div>
                      <input @click="handleSelectApplicant(wife.user)" required type="radio" name="anggota" class="flex size-[30px] shrink-0 accent-desa-dark-green" data-image="assets/images/photos/kk-photo-2.png" data-name="Rosita Luna R" data-status="Ibu Rumah Tangga" data-nik="2005100592201005" data-umur="32 Tahun" />
                    </div>
                  </label>
                </li>
                <li v-if="familyMemberData.filter(member => member.relation === 'child').length > 0">
                  <label class="profile flex flex-col gap-3 bg-white rounded-3xl">
                    <h4 class="font-medium leading-[17.5px] text-sm text-desa-secondary">Istri {{familyMemberData.filter(member => member.relation === 'child').length}}</h4>
                    <div v-for="child in familyMemberData.filter(member => member.relation === 'child')" class="data rounded-2xl border border-desa-background p-6 flex items-center gap-[49px] hover:ring-[1.5px] hover:ring-desa-dark-green transition-all duration-300">
                      <div class="name flex items-center gap-3">
                        <div class="flex size-[64px] shrink-0 rounded-full overflow-hidden bg-desa-foreshadow">
                          <img :src="child.profile_picture" class="w-full h-full object-cover" alt="photo" />
                        </div>
                        <div class="flex flex-col gap-[6px]">
                          <h5 class="font-semibold text-lg leading-[22.5px] truncate w-[164px]">
                            {{ child.user?.name}}
                          </h5>
                          <div class="flex items-center gap-1">
                            <img src="@/assets/images/icons/briefcase-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
                            <p class="font-medium leading-5 text-desa-secondary truncate w-[142px]">
                              {{child?.occupation}}
                            </p>
                          </div>
                        </div>
                      </div>
                      <div class="nik flex flex-col gap-[6px]">
                        <div class="flex items-center gap-1">
                          <img src="@/assets/images/icons/keyboard-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
                          <h5 class="font-medium text-sm leading-[17.5px] text-desa-secondary">NIK</h5>
                        </div>
                        <p class="font-semibold leading-5 truncate w-[155px]">
                          {{child?.identify_number}}
                        </p>
                      </div>
                      <div class="umur flex flex-col gap-[6px]">
                        <div class="flex items-center gap-1">
                          <img src="@/assets/images/icons/timer-secondary-green.svg" alt="icon" class="size-[18px] shrink-0" />
                          <h5 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Umur</h5>
                        </div>
                        <p class="font-semibold leading-5 truncate w-[92px]">
                          {{calculateAge(child?.date_of_birth)}} Tahun</p>
                      </div>
                      <input @click="handleSelectApplicant(child.user)" required type="radio" name="anggota" class="flex size-[30px] shrink-0 accent-desa-dark-green" data-image="assets/images/photos/kk-photo-2.png" data-name="Rosita Luna R" data-status="Ibu Rumah Tangga" data-nik="2005100592201005" data-umur="32 Tahun" />
                    </div>
                  </label>
                </li>
              </ul>
            </div>
          </div>
        </form>
      </section>
    </div>
  </div>

  <ModalDelete
      title="Hapus Data"
      :show="showModal"
      :loading="loading"
      @close="showModal = false"
      @confirm="handleDelete"
  />

</template>
