<script setup>
import {onMounted, ref} from "vue";
import {storeToRefs} from "pinia";
import {useRoute} from "vue-router";
import router from "@/router/index.js";
import {useEventStore} from "@/stores/event.js";
import ModalDelete from "../../components/ui/ModalDelete.vue";
import Alert from "@/components/ui/Alert.vue";
import {can} from "@/helpers/permissionHelper.js"
import {useAuthStore} from "@/stores/auth.js";
import {formatRupiah} from "@/helpers/formatHelper.js";
import {useEventParticipantStore} from "@/stores/event-participant.js";

const showModal = ref(false)
const route = useRoute()

const data = ref({})

const authStore = useAuthStore()
const {user} = storeToRefs(authStore)

const stores = useEventStore()
const {loading, success} = storeToRefs(stores)
const {destroy, fetchById} = stores

const fetch = async () => {
  data.value = await fetchById(route.params.id)
  formData.value.event_id = data.value.id
}

async function handleDelete() {
  await destroy(route.params.id)
  router.push({name: 'event'})
}

onMounted(fetch)

const decrementQuantity = () => {
  if (formData.value.quantity > 0) {
    formData.value.quantity -= 1
  }

  formData.value.total_price = data.value.price * formData.value.quantity
}

const incrementQuantity = () => {
  formData.value.quantity += 1
  formData.value.total_price = data.value.price * formData.value.quantity
}

const formData = ref({
  event_id: null,
  quantity: 0,
  total_price: 0
})

const eventParticipantStore = useEventParticipantStore()
const {create} = eventParticipantStore

async function handleSubmit() {
  const response = await create(formData.value)
  window.snap.pay(response.snap_token, {
    onSuccess: function (result) {
      /* You may add your own implementation here */
      showSuccessModal.value = true
      formData.value.quantity = 0
      formData.value.total_price = 0
    },
    onPending: function (result) {
      /* You may add your own implementation here */
      console.log(result);
    },
    onError: function (result) {
      /* You may add your own implementation here */
      console.log(result);
    },
    onClose: function () {
      /* You may add your own implementation here */
    }
  });
}

const showSuccessModal = ref(false)


</script>

<template>
  <Alert :message="success" @close="success = null"/>
  <div id="Header" class="flex items-center justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex gap-1 items-center leading-5 text-desa-secondary">
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Events desa</p>
        <span>/</span>
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Detail Event Desa</p>
      </div>
      <h1 class="font-semibold text-2xl">Detail Event Desa</h1>
    </div>
    <router-link :to="{name: 'eventEdit', params: {id: route.params.id}}"
                 class="flex items-center rounded-2xl py-4 px-6 gap-[10px] bg-desa-black"
                 v-if="can('event-edit')">
      <p class="font-medium text-white">Ubah Data</p>
      <img src="@/assets/images/icons/edit-white.svg" class="flex size-6 shrink-0" alt="icon">
    </router-link>
  </div>
  <div class="flex gap-[14px]">
    <section id="Informasi" class="flex flex-col shrink-0 w-[calc(525/1000*100%)] h-fit rounded-3xl p-6 gap-6 bg-white">
      <p class="font-medium leading-5 text-desa-secondary">Informasi Event</p>
      <div class="flex items-center w-full">
        <div class="flex w-[100px] h-20 shrink-0 rounded-2xl overflow-hidden bg-desa-foreshadow">
          <img :src="data.thumbnail" class="w-full h-full object-cover" alt="photo">
        </div>
        <div class="flex flex-col gap-[6px] w-full ml-4 mr-9">
          <p class="font-semibold text-lg leading-[22.5px] line-clamp-1">{{ data.name }}</p>
          <div class="flex items-center gap-1">
            <img src="@/assets/images/icons/ticket-secondary-green.svg" class="flex size-[18px] shrink-0" alt="icon">
            <p class="font-medium text-sm text-desa-secondary">
              Registration:
              <span class="font-medium text-base text-desa-dark-green">
                {{ data.is_active ? 'Open' : 'Closed' }}
              </span>
            </p>
          </div>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex items-center w-full gap-3">
        <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-red/10 items-center justify-center">
          <img src="@/assets/images/icons/ticket-2-red.svg" class="flex size-6 shrink-0" alt="icon">
        </div>
        <div class="flex flex-col gap-1 w-full">
          <p class="font-semibold text-lg leading-[22.5px] text-desa-red">
            Rp{{ parseFloat(data.price).toLocaleString('id-ID') }}</p>
          <span class="font-medium text-desa-secondary">
            Harga Event
          </span>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex items-center w-full gap-3">
        <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-blue/10 items-center justify-center">
          <img src="@/assets/images/icons/profile-2user-blue.svg" class="flex size-6 shrink-0" alt="icon">
        </div>
        <div class="flex flex-col gap-1 w-full">
          <p class="font-semibold text-lg leading-[22.5px] text-desa-blue">{{ data.event_participants?.length || 0 }}
            Warga</p>
          <span class="font-medium text-desa-secondary">
            Total Partisipasi
          </span>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex items-center w-full gap-3">
        <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-foreshadow items-center justify-center">
          <img src="@/assets/images/icons/calendar-2-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
        </div>
        <div class="flex flex-col gap-1 w-full">
          <p class="font-semibold text-lg leading-[22.5px] text-desa-dark-green">{{
              new Date(data.date).toLocaleDateString('en-US', {
                weekday: 'short',
                day: '2-digit',
                month: 'short',
                year: 'numeric'
              })
            }}</p>
          <span class="font-medium text-desa-secondary">
            Tanggal Pelaksaaan
          </span>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex items-center w-full gap-3">
        <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-yellow/10 items-center justify-center">
          <img src="@/assets/images/icons/clock-yellow.svg" class="flex size-6 shrink-0" alt="icon">
        </div>
        <div class="flex flex-col gap-1 w-full">
          <p class="font-semibold text-lg leading-[22.5px] text-desa-yellow">{{ data.time }} WIB</p>
          <span class="font-medium text-desa-secondary">
            Waktu Pelaksanaan
          </span>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex flex-col gap-3">
        <p class="font-medium text-sm text-desa-secondary">Tentang Event</p>
        <p class="font-medium leading-8">{{ data.description }}</p>
      </div>
    </section>
    <section id="Participants" class="flex flex-col flex-1 h-fit shrink-0 rounded-3xl p-6 gap-6 bg-white" v-if="user?.role === 'admin'">
      <p class="font-medium leading-5 text-desa-secondary">Latest Participants</p>
      <div class="flex flex-col gap-[14px]">
        <template v-if="data.event_participants && data.event_participants.length > 0">
          <template v-for="(participant, index) in data.event_participants.slice(0, 5)" :key="participant.id">
            <div class="flex items-center gap-3 w-[302px] shrink-0">
              <div class="flex size-[54px] rounded-full bg-desa-foreshadow overflow-hidden">
                <img :src="participant.head_of_family?.profile_picture" class="w-full h-full object-cover" alt="icon">
              </div>
              <div class="flex flex-col gap-1">
                <p class="font-semibold text-lg leading-5 text-desa-black">{{
                    participant.head_of_family?.user?.name
                  }}</p>
                <p class="flex items-center gap-1">
                  <img src="@/assets/images/icons/briefcase-secondary-green.svg" class="flex size-[18px] shrink-0"
                       alt="icon">
                  <span
                      class="font-medium text-sm text-desa-secondary">{{ participant.head_of_family?.occupation }}</span>
                </p>
              </div>
            </div>
            <hr v-if="index < Math.min(data.event_participants.length, 5) - 1" class="border-desa-background">
          </template>
        </template>
        <template v-else>
          <p class="text-center text-desa-secondary">Belum ada partisipan</p>
        </template>
      </div>
      <a href="#" class="flex items-center justify-center h-14 rounded-2xl py-4 px-6 gap-[10px] bg-desa-dark-green">
        <span class="font-medium leading-5 text-white">View All</span>
      </a>
    </section>
    <form @submit.prevent="handleSubmit" class="flex-1" v-if="user?.role === 'head-of-family'">
      <div class="flex flex-col h-fit gap-6 rounded-2xl bg-white py-6 flex-1">
        <h2 class="font-medium text-sm leading-[17.5px] text-desa-secondary px-6">Detail Pembayaran</h2>
        <section id="Harga-Event" class="flex items-center justify-between px-6">
          <div class="point flex items-center gap-3">
            <div class="p-[14px] shrink-0 bg-[#FF507017] rounded-2xl">
              <img src="@/assets/images/icons/ticket-2-red.svg" alt="icon" class="size-6 shrink-0" />
            </div>
            <div class="flex flex-col gap-1">
              <p class="font-semibold text-lg leading-[22.5px] text-desa-red">Rp {{formatRupiah(data.price)}}</p>
              <h3 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Harga Event</h3>
            </div>
          </div>
          <div id="CountButton" class="py-3 px-4 rounded-2xl border border-desa-background flex items-center gap-3">
            <button type="button" id="decrementBtn" @click="decrementQuantity">
              <img src="@/assets/images/icons/minus-square-secondary-green.svg" alt="icon" class="size-6 shrink-0" />
            </button>
            <p id="counterValue" class="font-medium text-[22px] leading-[27.5px] text-center text-[#000000]">{{
              formData.quantity}}</p>
            <button type="button" id="incrementBtn" @click="incrementQuantity">
              <img src="@/assets/images/icons/add-square-secondary-green.svg" alt="icon" class="size-6 shrink-0" />
            </button>
          </div>
        </section>
        <hr class="border-desa-background" />
        <section id="Detail-Payment" class="flex flex-col gap-6 px-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-[6px]">
              <img src="@/assets/images/icons/receipt-2-secondary-green.svg" alt="icon" class="size-6 shrink-0" />
              <h4 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Metode Pembayaran</h4>
            </div>
            <img src="@/assets/images/icons/midtrans.svg" alt="icon" class="w-[108px] shrink-0" />
          </div>
          <hr class="border-desa-background" />
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-[6px]">
              <img src="@/assets/images/icons/profile-2user-secondary-green-bold.svg" alt="icon" class="size-6 shrink-0" />
              <h4 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Quantity</h4>
            </div>
            <p class="font-semibold text-lg leading-[22.5px]">{{formData.quantity}}x warga</p>
          </div>
          <hr class="border-desa-background" />
          <label class="flex items-center justify-between">
            <div class="flex items-center gap-[6px]">
              <img src="@/assets/images/icons/money-secondary-green.svg" alt="icon" class="size-6 shrink-0" />
              <h4 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Harga Total</h4>
            </div>
            <input type="text" name="harga_total" :value="formatRupiah(formData.total_price)" class="font-semibold text-lg leading-[22.5px] text-right focus:outline-none" readonly />
          </label>
          <hr class="border-desa-background" />
        </section>
        <button type="submit" class="bg-desa-dark-green mx-6 rounded-2xl py-[18px] flex justify-center items-center text-center text-white font-medium leading-5">Purchase Ticket</button>
      </div>
    </form>
  </div>
  <ModalDelete
      title="Hapus Data"
      :show="showModal"
      :loading="loading"
      @close="showModal = false"
      @confirm="handleDelete"
  />

  <div id="modal" class="fixed inset-0 flex items-center justify-center bg-[#001B1ACC] z-50" v-if="showSuccessModal">
    <div class="bg-white rounded-2xl p-4 w-[320px] flex flex-col items-center gap-6">
      <div class="flex flex-col items-center gap-4">
        <h3 class="font-semibold text-2xl leading-8 text-desa-secondary">Pembayaran Berhasil</h3>
        <p class="font-medium text-base leading-5 text-desa-secondary">
          Terima kasih telah berpartisipasi<br />
          dalam<br />
          acara<br />
          ini.
        </p>
      </div>
      <button
          type="button"
          class="bg-desa-dark-green rounded-2xl py-[18px] flex justify-center items-center text-center text-white font-medium leading-5 w-full"
          @click="showSuccessModal = false"
      >
        Tutup
      </button>
    </div>
  </div>


</template>