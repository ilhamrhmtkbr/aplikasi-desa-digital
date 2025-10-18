<script setup>
import {onMounted, ref} from "vue";
import {storeToRefs} from "pinia";
import {useRoute} from "vue-router";
import {formatClientTimezone, formatDate, formatRupiah} from "@/helpers/formatHelper.js"
import router from "@/router/index.js";
import {useSocialAssistanceStore} from "@/stores/social-assistance.js";
import ModalDelete from "@/components/ui/ModalDelete.vue"
import Alert from "@/components/ui/Alert.vue";
import {can} from "@/helpers/permissionHelper.js"
import {useAuthStore} from "@/stores/auth.js";
import {useSocialAssistanceRecipientStore} from "@/stores/social-assistance-recipient.js";

const showModal = ref(false)
const route = useRoute()

const authStore = useAuthStore()
const {user} = storeToRefs(authStore)

const data = ref({})
const formCreate = ref({
  social_assistance_id: null,
  amount: 0,
  reason: null,
  bank: null,
  account_number: null,
})

const stores = useSocialAssistanceStore()
const {loading, success} = storeToRefs(stores)
const {destroy, fetchById} = stores

const fetch = async () => {
  data.value = await fetchById(route.params.id)
  formCreate.value.social_assistance_id = data.value.id
}

async function handleDelete() {
  await destroy(route.params.id)
  router.push({name: 'socialAssistance'})
}

const recipientStore = useSocialAssistanceRecipientStore()
const {create} = recipientStore

async function handleSubmit() {
  await create(formCreate.value)
}

onMounted(fetch)
</script>

<template>
  <Alert :message="success" @close="success = null"/>
  <div id="Header" class="flex items-center justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex gap-1 items-center leading-5 text-desa-secondary">
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Bantuan sosial</p>
        <span>/</span>
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Manage bantuan sosial</p>
      </div>
      <h1 class="font-semibold text-2xl">Manage Bantuan Sosial</h1>
    </div>
    <div class="flex items-center gap-3">
      <button @click="showModal = true" data-modal="Modal-Delete"
              class="flex items-center rounded-2xl py-4 px-6 gap-[10px] bg-desa-red">
        <p class="font-medium text-white">Hapus Data</p>
        <img src="@/assets/images/icons/trash-white.svg" class="flex size-6 shrink-0" alt="icon">
      </button>
      <router-link :to="{name: 'socialAssistanceEdit', params: {id: route.params.id}}"
                   class="flex items-center rounded-2xl py-4 px-6 gap-[10px] bg-desa-black">
        <p class="font-medium text-white">Ubah Data</p>
        <img src="@/assets/images/icons/edit-white.svg" class="flex size-6 shrink-0" alt="icon">
      </router-link>
    </div>
  </div>
  <div class="flex gap-[14px]">
    <section id="Informasi-Bantuan-Sosial"
             class="flex flex-col shrink-0 w-[calc(545/1000*100%)] h-fit rounded-3xl p-6 gap-6 bg-white">
      <p class="font-medium leading-5 text-desa-secondary">Informasi Bantuan Sosial</p>
      <div class="flex items-center justify-between gap-4">
        <div class="flex w-[120px] h-[100px] shrink-0 rounded-2xl overflow-hidden bg-desa-foreshadow">
          <img :src="data.thumbnail" class="w-full h-full object-cover" alt="photo">
        </div>
        <div v-if="data.is_available"
             class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-soft-green">
          <span class="font-semibold text-xs text-white uppercase">Tersedia</span>
        </div>
        <div v-else class="badge rounded-full p-3 gap-2 flex justify-center shrink-0 bg-desa-red">
          <span class="font-semibold text-xs text-white uppercase">Tidak Tersedia</span>
        </div>
      </div>
      <div class="flex flex-col gap-[6px] w-full">
        <p class="font-semibold text-xl">{{ data.name }}</p>
        <p class="flex items-center gap-1">
          <img src="@/assets/images/icons/profile-secondary-green.svg" class="flex size-[18px] shrink-0" alt="icon">
          <span class="font-medium text-sm text-desa-secondary">{{ data.provider }}</span>
        </p>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex items-center w-full gap-3">
        <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-foreshadow items-center justify-center">
          <img src="@/assets/images/icons/money-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
        </div>
        <div class="flex flex-col gap-1 w-full">
          <p class="font-semibold text-lg leading-[22.5px] text-desa-dark-green">Rp. {{ formatRupiah(data.amount) }}</p>
          <span class="font-medium text-desa-secondary">
                                        Uang Tunai
                                    </span>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex items-center w-full gap-3">
        <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-blue/10 items-center justify-center">
          <img src="@/assets/images/icons/profile-2user-blue.svg" class="flex size-6 shrink-0" alt="icon">
        </div>
        <div class="flex flex-col gap-1 w-full">
          <p class="font-semibold text-lg leading-[22.5px] text-desa-blue">
            {{ data.social_assistance_recipients?.length ?? '0' }}</p>
          <span class="font-medium text-desa-secondary">
                                        Total Pengajuan
                                    </span>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex flex-col gap-3">
        <p class="font-medium text-sm text-desa-secondary">Tentang Bantuan</p>
        <p class="font-medium leading-8">{{ data.description }}</p>
      </div>
    </section>
    <section id="Penerima-Bansos-Terakhir" class="flex flex-col flex-1 h-fit shrink-0 rounded-3xl p-6 gap-6 bg-white" v-if="user?.role === 'admin'">
      <p class="font-medium leading-5 text-desa-secondary">Penerima Bansos Terakhir</p>
      <div id="List-Bansos-Terkahir" class="flex flex-col gap-6">
        <div v-for="item in data.social_assistance_recipients"
             class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
          <div class="flex items-center justify-between">
            <p class="font-medium text-sm text-desa-secondary">{{ formatClientTimezone(item.created_at) }}</p>
            <img src="@/assets/images/icons/calendar-2-secondary-green.svg" class="flex size-[18px] shrink-0"
                 alt="icon">
          </div>
          <hr class="border-desa-background">
          <div class="flex items-center gap-3">
            <div class="flex flex-col gap-[6px] w-full">
              <p class="font-semibold text-lg leading-5">Rp {{ formatRupiah(item.amount) }}</p>
              <p class="font-medium text-sm text-desa-secondary">Nominal Pengajuan</p>
            </div>
            <div
                class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0"
                :class="{
                  'bg-desa-yellow': item.status === 'pending',
                  'bg-desa-green': item.status === 'approved',
                  'bg-desa-red': item.status === 'rejected'
                }"
                          >
                <span class="font-semibold text-xs text-white uppercase">
                  {{
                    item.status === 'pending'
                        ? 'Menunggu'
                        : item.status === 'approved'
                            ? 'Disetujui'
                            : 'Ditolak'
                  }}
                </span>
            </div>

          </div>
          <hr class="border-desa-background">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-0.5">
              <img src="@/assets/images/icons/profile-secondary-green.svg" class="flex size-[18px] shrink-0" alt="icon">
              <p class="font-medium text-sm text-desa-secondary">Diberikan Kepada:</p>
            </div>
            <div class="flex items-center gap-1">
              <p class="font-medium leading-5">{{item.head_of_family?.user?.name}}</p>
              <div class="flex size-8 shrink-0 rounded-full bg-desa-foreshadow overflow-hidden">
                <img :src="item.head_of_family?.profile_picture" class="w-full h-full object-cover" alt="photo">
              </div>
            </div>
          </div>
        </div>
        <a href="#" class="flex items-center justify-center h-14 rounded-2xl py-4 px-6 gap-[10px] bg-desa-dark-green">
          <span class="font-medium leading-5 text-white">View All</span>
        </a>
      </div>
    </section>
    <form @submit.prevent="handleSubmit" class="w-full" v-if="user?.role === 'head-of-family'">
      <div class="flex flex-col gap-[14px] flex-1">
        <section id="Bank-Account-Detail" class="rounded-2xl bg-white p-6 flex flex-col gap-6">
          <h2 class="font-semibold leading-5">Bank Account Detail</h2>
          <label id="Pilih-Bank">
            <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Kamu Pake Bank Apa?</h3>
            <div class="flex flex-col gap-4 py-4 mt-4 bg-white border border-desa-background rounded-2xl">
              <button type="button" data-expand="BankJ" class="px-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                  <img src="@/assets/images/icons/bank-secondary-green.svg" alt="icon" class="size-6 shrink-0" />
                  <h4 class="font-medium leading-5 text-desa-secondary">Pilih salah satu bank</h4>
                </div>
                <img id="BankArrow" src="@/assets/images/icons/arrow-down-secondary-green.svg" alt="icon" class="size-6 shrink-0 transition-all duration-300" />
              </button>
              <div id="BankJ" class="border-t border-desa-background flex flex-col gap-4 pt-4 px-4">
                <label id="Bca-Bank" class="flex items-center justify-between gap-4">
                  <div class="flex items-center gap-4">
                    <div class="rounded-2xl border border-desa-background bg-[#F2F7FA] w-[100px] h-[60px] shrink-0 px-[14px] py-5">
                      <img src="@/assets/images/thumbnails/kk-bca.png" alt="" class="size-full object-contain" />
                    </div>
                    <p class="font-medium leading-5 text-[#000000]">Bank Central Asia</p>
                  </div>
                  <input v-model="formCreate.bank" value="bca" required type="radio" name="bank" id="Bca" class="peer flex size-[18px] shrink-0 accent-desa-dark-green" />
                </label>
                <hr class="border-desa-background" />
                <label id="Mandiri-Bank" class="flex items-center justify-between gap-4">
                  <div class="flex items-center gap-4">
                    <div class="rounded-2xl border border-desa-background bg-[#F2F7FA] w-[100px] h-[60px] shrink-0 px-[14px] py-5">
                      <img src="@/assets/images/thumbnails/kk-mandiri.png" alt="" class="size-full object-contain" />
                    </div>
                    <p class="font-medium leading-5 text-[#000000]">Bank Mandiri</p>
                  </div>
                  <input v-model="formCreate.bank" value="mandiri" required type="radio" name="bank" id="Mandiri" class="peer flex size-[18px] shrink-0 accent-desa-dark-green" />
                </label>
                <hr class="border-desa-background" />
                <label id="Bni-Bank" class="flex items-center justify-between gap-4">
                  <div class="flex items-center gap-4">
                    <div class="rounded-2xl border border-desa-background bg-[#F2F7FA] w-[100px] h-[60px] shrink-0 px-[14px] py-5">
                      <img src="@/assets/images/thumbnails/kk-bni.png" alt="" class="size-full object-contain" />
                    </div>
                    <p class="font-medium leading-5 text-[#000000]">Bank Negara Indonesia</p>
                  </div>
                  <input v-model="formCreate.bank" value="bni" required type="radio" name="bank" id="Bni" class="peer flex size-[18px] shrink-0 accent-desa-dark-green" />
                </label>
                <hr class="border-desa-background" />
              </div>
            </div>
          </label>
          <label id="Nomer-Rekening" class="flex flex-col gap-4">
            <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Nomor Rekening Bank</h3>
            <div class="relative">
              <input v-model="formCreate.account_number" required type="number" placeholder="Masukan Nomor Rekening" class="peer w-full py-[18px] rounded-2xl border border-desa-background pl-[48px] pr-4 focus:outline-none bg-white font-medium leading-5 placeholder:text-desa-secondary placeholder:font-medium placeholder:leading-5 focus:ring-[1.5px] focus:ring-desa-dark-green transition-all duration-300" />
              <img src="@/assets/images/icons/card-secondary-green.svg" alt="icon" class="peer-placeholder-shown:block hidden size-6 shrink-0 absolute left-4 top-1/2 -translate-y-1/2" />
              <img src="@/assets/images/icons/card-black.svg" alt="icon" class="peer-placeholder-shown:hidden size-6 shrink-0 absolute left-4 top-1/2 -translate-y-1/2" />
            </div>
          </label>
        </section>
        <section id="Ajukan-Bantuan-Sosial" class="rounded-2xl bg-white p-6 flex flex-col gap-6">
          <h2 class="font-semibold leading-5">Ajukan Bantuan Sosial</h2>
          <div id="Nominal" class="flex flex-col gap-4">
            <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Nominal Pengajuan</h3>
            <label class="relative group peer w-full">
              <input v-model="formCreate.amount" type="number" placeholder="Input amount" class="appearance-none outline-none w-full h-14 rounded-2xl ring-[1.5px] ring-desa-background focus:ring-desa-dark-green py-4 pr-12 [&:placeholder-shown]:pl-12 pl-[70px] gap-2 font-medium placeholder:text-desa-secondary transition-all duration-300">
              <div class="absolute transform -translate-y-1/2 top-1/2 left-4 flex size-6 shrink-0">
                <img src="@/assets/images/icons/receive-square-2-secondary-green.svg" class="size-6 hidden group-has-[:placeholder-shown]:flex" alt="icon">
                <img src="@/assets/images/icons/receive-square-2-black.svg" class="size-6 flex group-has-[:placeholder-shown]:hidden" alt="icon">
                <span class="text-desa-black ml-2 opacity-100 group-has-[:placeholder-shown]:opacity-0 transition-setup">Rp</span>
              </div>
            </label>
          </div>
          <label id="Nomer-Pemilik" class="flex flex-col gap-4">
            <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Deskripsi Bantuan Sosial</h3>
            <div class="relative">
              <textarea v-model="formCreate.reason" placeholder="Jelaskan lebih detail tentang bantuan" name="" id="" class="w-full h-[224px] rounded-2xl peer p-4 border border-desa-background focus:outline-none bg-white font-medium leading-5 placeholder:text-desa-secondary placeholder:font-medium placeholder:leading-5 focus:ring-[1.5px] focus:ring-desa-dark-green transition-all duration-300 [&::-webkit-scrollbar]:hidden"></textarea>
            </div>
          </label>
          <button type="submit" class="bg-desa-black rounded-2xl w-full py-[18px] flex justify-center items-center font-medium leading-5 text-white text-center">Ajukan Bantuan</button>
        </section>
      </div>
    </form>
  </div>
  <!-- BENAR ✅ -->
  <ModalDelete
      title="Hapus Data"
      :show="showModal"
      :loading="loading"
      @close="showModal = false"
      @confirm="handleDelete"
  />

</template>
