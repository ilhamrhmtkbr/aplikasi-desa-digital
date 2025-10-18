<script setup>
import {onMounted, ref} from "vue";
import {storeToRefs} from "pinia";
import {useRoute} from "vue-router";
import router from "@/router/index.js";
import {formatClientTimezone, formatRupiah} from "@/helpers/formatHelper.js";
import ModalDelete from "../../components/ui/ModalDelete.vue";
import Alert from "@/components/ui/Alert.vue";
import {useSocialAssistanceRecipientStore} from "@/stores/social-assistance-recipient.js";
import {handleImageChange} from "@/helpers/imageHelper.js";
import Input from "@/components/ui/Input.vue";
import {can} from "@/helpers/permissionHelper.js"
import {useAuthStore} from "@/stores/auth.js";

const showModal = ref(false)
const route = useRoute()

const authStore = useAuthStore();
const {user} = storeToRefs(authStore)

const form = ref({
  id: null,
  social_assistance_id: null,
  head_of_family_id: null,
  amount: null,
  reason: null,
  bank: null,
  account_number: null,
  proof: null,
  proof_url: null,
  status: null
})

const data = ref({})

const stores = useSocialAssistanceRecipientStore()
const {loading, success} = storeToRefs(stores)
const {destroy, fetchById, edit} = stores

const fetch = async () => {
  const res = await fetchById(route.params.id)
  data.value = res
  if (res) {
    form.value = {
      id: res.id,
      social_assistance_id: res.social_assistance?.id,
      head_of_family_id: res.head_of_family?.id,
      amount: res.amount,
      reason: res.reason,
      bank: res.bank,
      account_number: res.account_number,
      status: res.status ?? null,
      proof_url: res.proof || null
    }
  }
}

async function handleDelete() {
  await destroy(route.params.id)
  router.push({name: 'socialAssistanceRecipient'})
}

async function handleUpdate() {
  let isSuccess = await edit(form.value, route.params.id)
  if (isSuccess) {
    await fetch()
  }
}

function onImageChange(e) {
  handleImageChange(e, form, 'proof')
}

onMounted(fetch)
</script>

<template>
  <Alert :message="success" @close="success = null"/>
  <div id="Header" class="flex items-center justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex gap-1 items-center leading-5 text-desa-secondary">
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Pengajuan Bantuan sosial</p>
        <span>/</span>
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">penyetujuan bansos</p>
      </div>
      <h1 class="font-semibold text-2xl">Penyetujuan Bansos </h1>
    </div>
  </div>
  <div class="flex gap-[14px]">
    <section id="Detail" class="flex flex-col shrink-0 w-[calc(545/1000*100%)] h-fit rounded-3xl p-6 gap-6 bg-white">
      <p class="font-medium leading-5 text-desa-secondary">Detail Bantuan Sosial</p>
      <div class="flex items-center justify-between gap-4">
        <div class="flex w-[120px] h-[100px] shrink-0 rounded-2xl overflow-hidden bg-desa-foreshadow">
          <img :src="data.social_assistance?.thumbnail" class="w-full h-full object-cover" alt="photo">
        </div>
        <div class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-black">
          <span class="font-semibold text-xs text-white uppercase">{{ data.status }}</span>
        </div>
      </div>
      <div class="flex flex-col gap-[6px] w-full">
        <p class="font-semibold text-xl line-clamp-1">Bantuan Untuk Rakyat Kurang Mampu</p>
        <p class="flex items-center gap-1">
          <img src="@/assets/images/icons/profile-secondary-green.svg" class="flex size-[18px] shrink-0" alt="icon">
          <span class="font-medium text-sm text-desa-secondary">{{ data.social_assistance?.provider }}</span>
        </p>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex items-center w-full gap-3">
        <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-foreshadow items-center justify-center">
          <img src="@/assets/images/icons/money-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
        </div>
        <div class="flex flex-col gap-1 w-full">
          <p class="font-semibold text-lg leading-[22.5px] text-desa-dark-green">Rp {{ formatRupiah(data.amount) }}</p>
          <span class="font-medium text-desa-secondary">
                                        Uang Tunai
                                    </span>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex items-center w-full gap-3">
        <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-red/10 items-center justify-center">
          <img src="@/assets/images/icons/minus-square-red.svg" class="flex size-6 shrink-0" alt="icon">
        </div>
        <div class="flex flex-col gap-1 w-full">
          <p class="font-semibold text-lg leading-[22.5px] text-desa-red">Rp92.000.000</p>
          <span class="font-medium text-desa-secondary">
                                        Sisa Bansos
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
            {{ data.social_assistance?.social_assistance_recipients?.length ?? '0' }} Warga</p>
          <span class="font-medium text-desa-secondary"> Total Pengajuan </span>
        </div>
      </div>
      <hr class="border-desa-foreshadow">
      <div class="flex flex-col gap-3">
        <p class="font-medium text-sm text-desa-secondary">Tentang Bantuan</p>
        <p class="font-medium leading-8">{{ data.social_assistance?.description }}</p>
      </div>
    </section>
    <div class="flex-1 flex flex-col h-fit gap-[14px] w-[418px]">
      <div class="rounded-2xl bg-white p-6 flex flex-col gap-6" v-if="user?.role === 'head-of-family'">
        <section id="Status-Pengajuan" class="flex items-center justify-between">
          <h2 class="font-medium text-sm leading-[17.5px] text-desa-secondary">Status Pengajuan</h2>
          <div class="group {{ data.status }}">
            <span class="group-[&.pending]:flex hidden rounded-full py-[12px] w-[100px] justify-center bg-desa-yellow text-white"
            ></span>
            <span
                class="group-[&.rejected]:flex hidden rounded-full py-[12px] w-[100px] justify-center bg-desa-red text-white"
            ></span>
            <span
                class="group-[&.approved]:flex hidden rounded-full py-[12px] w-[100px] justify-center bg-desa-dark-green text-white"
            ></span>
          </div>
        </section>

        <hr class="border-desa-background"/>

        <section id="Bukti-Menerima-Bansos" class="flex flex-col gap-4">
          <h2 class="font-medium text-sm leading-[17.5px] text-desa-secondary">
            Bukti Menerima Bansos
          </h2>
          <div class="relative flex justify-center items-center w-full h-[230px] overflow-hidden rounded-2xl">
            <div
                class="square-dashed w-full h-[230px] flex justify-center items-center"
                v-if="!data.proof"
            >
              <p class="text-center w-[240px] font-medium text-xs leading-[19.2px] text-[#ACB9BB]">
                Gambar akan muncul jika status pengajuan sudah berhasil 🥲
              </p>
            </div>
            <img
                :src="data.proof"
                alt="image"
                class="bukti-menerima-bansos absolute left-0 top-0 w-full h-full object-cover"
                v-if="data.proof"
            />
          </div>
        </section>
      </div>
      <section class="flex flex-col flex-1 h-fit shrink-0 rounded-3xl p-6 gap-6 bg-white">
        <p class="font-medium leading-5 text-desa-secondary">Detail Pengajuan</p>
        <div class="flex items-center gap-3 w-[302px] shrink-0">
          <div class="flex size-[54px] rounded-full bg-desa-foreshadow overflow-hidden">
            <img :src="data.head_of_family?.profile_picture" class="w-full h-full object-cover" alt="icon">
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-lg leading-5 text-desa-black">{{ data.head_of_family?.user?.name }}</p>
            <p class="flex items-center gap-1">
              <img src="@/assets/images/icons/briefcase-secondary-green.svg" class="flex size-[18px] shrink-0"
                   alt="icon">
              <span class="font-medium text-sm text-desa-secondary">{{ data.head_of_family?.occupation }}</span>
            </p>
          </div>
        </div>
        <hr class="border-desa-background">
        <div class="flex items-center gap-3 w-[302px] shrink-0">
          <div class="flex size-[52px] rounded-2xl items-center justify-center bg-desa-foreshadow overflow-hidden">
            <img src="@/assets/images/icons/profile-2user-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-lg leading-5">{{ data.head_of_family?.family_members?.length ?? '0' }}
              Anggota</p>
            <p class="font-medium text-sm text-desa-secondary">Total Keluarga</p>
          </div>
        </div>
        <hr class="border-desa-background">
        <div class="flex items-center gap-3 w-[302px] shrink-0">
          <div class="flex size-[52px] rounded-2xl items-center justify-center bg-desa-foreshadow overflow-hidden">
            <img src="@/assets/images/icons/keyboard-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-lg leading-5">{{ data.head_of_family?.identify_number }}</p>
            <p class="font-medium text-sm text-desa-secondary">NIK</p>
          </div>
        </div>
        <hr class="border-desa-background">
        <div class="flex items-center gap-3 w-[302px] shrink-0">
          <div class="flex size-[52px] rounded-2xl items-center justify-center bg-desa-foreshadow overflow-hidden">
            <img src="@/assets/images/icons/calendar-2-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-lg leading-5">{{ formatClientTimezone(data.created_at) }}</p>
            <p class="font-medium text-sm text-desa-secondary">Tanggal Pengajuan</p>
          </div>
        </div>
        <hr class="border-desa-background">
        <div class="flex items-center gap-3 w-[302px] shrink-0">
          <div class="flex size-[52px] rounded-2xl items-center justify-center bg-desa-foreshadow overflow-hidden">
            <img src="@/assets/images/icons/receive-square-2-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-lg leading-5">Rp{{ formatRupiah(data?.amount) }}</p>
            <p class="font-medium text-sm text-desa-secondary">Nominal Pengajuan</p>
          </div>
        </div>
        <hr class="border-desa-background">
        <div class="flex flex-col gap-1">
          <p class="font-medium text-sm text-desa-secondary">Pesan Pengajuan:</p>
          <p class="font-medium leading-8">“{{data.reason}}”</p>
        </div>
        <hr class="border-desa-background">
        <div class="flex flex-col gap-6">
          <p class="font-medium text-sm text-desa-secondary">Rekening Kepala Rumah</p>
          <div class="flex items-center gap-3">
            <div
                class="flex w-[120px] h-[60px] rounded-2xl border border-desa-background py-3 px-0.5 items-center justify-center bg-desa-blue/10 overflow-hidden">
              <img src="@/assets/images/thumbnails/kk-bca.png" class="w-full h-full object-contain" alt="icon"
                   v-if="data.bank === 'bca'">
              <img src="@/assets/images/thumbnails/kk-mandiri.png" class="w-full h-full object-contain" alt="icon"
                   v-if="data.bank === 'mandiri'">
              <img src="@/assets/images/thumbnails/kk-bni.png" class="w-full h-full object-contain" alt="icon"
                   v-if="data.bank === 'bni'">
            </div>
            <div>
              <p class="font-medium text-sm text-desa-secondary">{{ data.head_of_family?.user?.name }}</p>
              <div class="flex items-center gap-1">
                <img src="@/assets/images/icons/document-copy-dark-green.svg" class="flex size-[18px] shrink-0"
                     alt="icon">
                <p class="font-semibold text-lg text-desa-dark-green">{{ data.account_number }}</p>
              </div>
            </div>
          </div>
        </div>
        <div v-if="user?.role === 'admin'">
          <hr class="border-desa-background">
          <form @submit.prevent="handleUpdate" class="flex flex-col gap-6">
            <p class="font-medium text-sm text-desa-secondary">Bukti Pemberian Bansos</p>
            <div class="flex-1 flex items-center justify-between">
              <div id="Photo-Preview"
                   class="flex itce justify-center w-[120px] h-[100px] rounded-2xl overflow-hidden bg-desa-foreshadow">
                <img id="Photo" :src="form.proof_url" alt="image" class="size-full object-cover"/>
              </div>
              <div class="relative" v-if="data.status === 'pending'">
                <input @change="onImageChange" required id="File" type="file" name="file"
                       class="absolute opacity-0 left-0 w-full top-0 h-full" ref="proof"/>
                <button id="Upload" type="button"
                        class="relative flex items-center py-4 px-6 rounded-2xl bg-desa-black gap-[10px]"
                        @click="$refs.proof.click()">
                  <img src="@/assets/images/icons/send-square-white.svg" alt="icon" class="size-6 shrink-0"/>
                  <p class="font-medium leading-5 text-white">Upload</p>
                </button>
              </div>
            </div>
            <hr class="border-desa-background">
            <div class="flex items-center gap-3 w-full" v-if="data.status === 'pending'">
              <button @click="form.status = 'rejected'" type="submit"
                      class="flex items-center w-full justify-center gap-[10px] rounded-2xl py-4 px-6 bg-desa-red/10">
                <span class="font-medium text-desa-red">Tolak</span>
              </button>
              <button @click="form.status = 'approved'" type="submit"
                      class="flex items-center w-full justify-center gap-[10px] rounded-2xl py-4 px-6 bg-desa-dark-green">
                <span class="font-medium text-white">Setuju</span>
              </button>
            </div>
          </form>
        </div>
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
