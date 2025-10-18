<script setup>
import {useHeadOfFamilyStore} from "@/stores/head-of-family.js";
import {onMounted, ref} from "vue";
import {storeToRefs} from "pinia";
import {useRoute} from "vue-router";
import router from "@/router/index.js";
import {calculateAge} from "@/helpers/formatHelper.js"
import ModalDelete from "@/components/ui/ModalDelete.vue"
import {can} from "@/helpers/permissionHelper.js"

const showModal = ref(false)
const route = useRoute()

const data = ref({})

const stores = useHeadOfFamilyStore()
const {loading} = storeToRefs(stores)
const {destroy, fetchById} = stores

const fetch = async () =>{
  data.value = await fetchById(route.params.id)
}

async function handleDelete() {
  await destroy(route.params.id)
  router.push({name: 'headOfFamily'})
}

onMounted(fetch)
</script>

<template>
  <div id="Header" class="flex items-center justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex gap-1 items-center leading-5 text-desa-secondary">
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Kepala Rumah</p>
        <span>/</span>
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Manage Kepala Rumah</p>
      </div>
      <h1 class="font-semibold text-2xl">Manage Kepala Rumah</h1>
    </div>
    <button data-modal="Modal-Delete" class="flex items-center rounded-2xl py-4 px-6 gap-[10px] bg-desa-red"
            @click="showModal = true">
      <p class="font-medium text-white">Hapus Data</p>
      <img src="@/assets/images/icons/trash-white.svg" class="flex size-6 shrink-0" alt="icon">
    </button>
  </div>
  <div class="flex gap-[14px]">
    <div class="flex flex-col w-[calc(525/1000*100%)] shrink-0 gap-[14px]">
      <section id="Kepala-Rumah" class="flex flex-col rounded-3xl p-6 gap-6 bg-white">
        <p class="font-medium leading-5 text-desa-secondary">Kepala Rumah</p>
        <div class="flex items-center gap-4">
          <div class="flex size-[76px] shrink-0 rounded-full overflow-hidden bg-desa-foreshadow">
            <img :src="data?.profile_picture" class="w-full h-full object-cover" alt="photo">
          </div>
          <div class="flex flex-col gap-[6px] w-full">
            <p class="font-semibold text-xl line-clamp-1">{{ data?.user?.name }}</p>
            <p class="flex items-center gap-1">
              <img src="@/assets/images/icons/briefcase-secondary-green.svg" class="flex size-[18px] shrink-0"
                   alt="icon">
              <span class="font-medium text-sm text-desa-secondary">{{ data?.occupation}}</span>
            </p>
          </div>
          <div class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-soft-green">
            <span class="font-semibold text-xs text-white uppercase">{{ data?.marital_status }}</span>
          </div>
        </div>
        <hr class="border-desa-foreshadow">
        <div class="flex items-center w-full gap-3">
          <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-foreshadow items-center justify-center">
            <img src="@/assets/images/icons/keyboard-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1 w-full">
            <p class="font-semibold text-xl leading-[22.5px]">{{ data?.identify_number }}</p>
            <span class="font-medium text-desa-secondary">
                                            Nomor Induk Kependudukan
                                        </span>
          </div>
        </div>
        <hr class="border-desa-foreshadow">
        <div class="flex items-center w-full gap-3">
          <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-foreshadow items-center justify-center">
            <img src="@/assets/images/icons/user-square-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1 w-full">
            <p class="font-semibold text-xl leading-[22.5px]">{{ calculateAge(data?.date_of_birth) }} Tahun</p>
            <span class="font-medium text-desa-secondary">
                                            Umur Kepala Rumah
                                        </span>
          </div>
        </div>
        <hr class="border-desa-foreshadow">
        <div class="flex items-center w-full gap-3">
          <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-foreshadow items-center justify-center">
            <img src="@/assets/images/icons/man-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1 w-full">
            <p class="font-semibold text-xl leading-[22.5px]">{{ data?.gender }}</p>
            <span class="font-medium text-desa-secondary">
                                            Jenis Kelamin
                                        </span>
          </div>
        </div>
        <hr class="border-desa-foreshadow">
        <div class="flex items-center w-full gap-3">
          <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-foreshadow items-center justify-center">
            <img src="@/assets/images/icons/sms-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1 w-full">
            <p class="font-semibold text-xl leading-[22.5px]">{{ data?.user?.email }}</p>
            <span class="font-medium text-desa-secondary">
                                            Email Address
                                        </span>
          </div>
        </div>
        <hr class="border-desa-foreshadow">
        <div class="flex items-center w-full gap-3">
          <div class="flex size-[52px] shrink-0 rounded-2xl bg-desa-foreshadow items-center justify-center">
            <img src="@/assets/images/icons/call-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </div>
          <div class="flex flex-col gap-1 w-full">
            <p class="font-semibold text-xl leading-[22.5px]">{{ data?.phone_number }}</p>
            <span class="font-medium text-desa-secondary">
                                            Nomor Hp
                                        </span>
          </div>
        </div>
      </section>
      <section id="Anggota-Keluarga" class="flex flex-col rounded-3xl p-6 gap-6 bg-white" v-if="data?.family_members?.length">
        <div class="flex items-center justify-between">
          <div class="flex flex-col gap-[6px]">
            <p class="font-semibold text-[32px] leading-10">{{ data?.family_members?.length ?? '0' }}</p>
            <p class="font-medium leading-5 text-desa-secondary">Anggota Keluarga</p>
          </div>
          <img src="@/assets/images/icons/profile-2user-foreshadow-background.svg" class="flex size-12 shrink-0"
               alt="icon">
        </div>
        <hr class="border-desa-foreshadow">
        <div class="flex flex-col gap-[14px]">
          <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-6" v-for="item in data.family_members" :key="item.id">
            <div class="flex items-center gap-4">
              <div class="flex size-[64px] shrink-0 rounded-full overflow-hidden bg-desa-foreshadow">
                <img :src="item?.profile_picture" class="w-full h-full object-cover" alt="photo">
              </div>
              <div class="flex flex-col gap-[6px] w-full">
                <p class="font-semibold text-xl line-clamp-1">{{ item?.user?.name }}</p>
                <p class="flex items-center gap-1">
                  <img src="@/assets/images/icons/briefcase-secondary-green.svg" class="flex size-[18px] shrink-0"
                       alt="icon">
                  <span class="font-medium text-sm text-desa-secondary">{{ item?.occupation }}</span>
                </p>
              </div>
              <p class="font-medium leading-5 text-nowrap">{{ calculateAge(item?.date_of_birth) }} tahun</p>
            </div>
            <hr class="border-desa-background">
            <div class="flex justify-between items-center">
              <p class="flex items-center gap-1">
                <img src="@/assets/images/icons/keyboard-secondary-green.svg" class="flex size-[18px] shrink-0"
                     alt="icon">
                <span class="font-medium text-sm text-desa-secondary">Nomor Induk Kependudukan:</span>
              </p>
              <p class="font-medium leading-5">{{ item?.identify_number }}</p>
            </div>
          </div>
        </div>
      </section>
    </div>
    <div class="flex flex-col flex-1 shrink-0 gap-[14px]">
      <section id="Recent-Activity" class="flex flex-col rounded-3xl p-6 gap-6 bg-white">
        <p class="font-medium leading-5 text-desa-secondary">Recent Activity</p>
        <div id="Tabs-Button" class="grid grid-cols-3 gap-3">
          <button data-content="Bansos" class="tab-btn group active">
            <div
                class="flex items-center justify-center rounded-full border border-desa-background py-[14px] px-[18px] group-hover:bg-desa-black group-[.active]:bg-desa-black transition-setup">
                                            <span
                                                class="font-medium leading-5 text-desa-secondary group-hover:text-white group-[.active]:text-white transition-setup">
                                                Bansos
                                            </span>
            </div>
          </button>
          <button data-content="Events" class="tab-btn group">
            <div
                class="flex items-center justify-center rounded-full border border-desa-background py-[14px] px-[18px] group-hover:bg-desa-black group-[.active]:bg-desa-black transition-setup">
                                            <span
                                                class="font-medium leading-5 text-desa-secondary group-hover:text-white group-[.active]:text-white transition-setup">
                                                Events
                                            </span>
            </div>
          </button>
          <button data-content="Applicants" class="tab-btn group">
            <div
                class="flex items-center justify-center rounded-full border border-desa-background py-[14px] px-[18px] group-hover:bg-desa-black group-[.active]:bg-desa-black transition-setup">
                                            <span
                                                class="font-medium leading-5 text-desa-secondary group-hover:text-white group-[.active]:text-white transition-setup">
                                                Applicants
                                            </span>
            </div>
          </button>
        </div>
        <div id="Tabs-Content" class="flex flex-col">
          <div id="Bansos" class="flex flex-col gap-6">
            <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Tue, 31 Dec 2024 </p>
                <img src="@/assets/images/icons/calendar-2-secondary-green.svg" class="flex size-[18px] shrink-0"
                     alt="icon">
              </div>
              <hr class="border-desa-background">
              <p class="font-semibold text-lg">Bantuan Untuk Rakyat Kurang Mampu</p>
              <div class="flex items-center gap-3">
                <div class="flex size-[52px] shrink-0 items-center justify-center rounded-2xl bg-desa-foreshadow">
                  <img src="@/assets/images/icons/money-dark-green.svg" alt="icon">
                </div>
                <div class="flex flex-col gap-[6px] w-full">
                  <p class="font-semibold text-lg leading-5">Rp120.000.000</p>
                  <p class="font-medium text-sm text-desa-secondary">Nominal Pengajuan</p>
                </div>
                <div class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-yellow">
                  <span class="font-semibold text-xs text-white uppercase">Menunggu</span>
                </div>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Nominal Pengajuan:</p>
                <p class="font-medium leading-5 text-desa-red">Rp2.500.000</p>
              </div>
            </div>
            <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Tue, 25 Dec 2024 </p>
                <img src="@/assets/images/icons/calendar-2-secondary-green.svg" class="flex size-[18px] shrink-0"
                     alt="icon">
              </div>
              <hr class="border-desa-background">
              <p class="font-semibold text-lg">Bantuan Pangan Sehari-hari</p>
              <div class="flex items-center gap-3">
                <div class="flex size-[52px] shrink-0 items-center justify-center rounded-2xl bg-desa-foreshadow">
                  <img src="@/assets/images/icons/bag-2-dark-green.svg" alt="icon">
                </div>
                <div class="flex flex-col gap-[6px] w-full">
                  <p class="font-semibold text-lg leading-5">Beras 200 Ton</p>
                  <p class="font-medium text-sm text-desa-secondary">Bahan Pokok</p>
                </div>
                <div class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-dark-green">
                  <span class="font-semibold text-xs text-white uppercase">Diterima</span>
                </div>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Nominal Pengajuan:</p>
                <p class="font-medium leading-5 text-desa-red">Beras 2kg</p>
              </div>
            </div>
            <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Tue, 12 Dec 2024 </p>
                <img src="@/assets/images/icons/calendar-2-secondary-green.svg" class="flex size-[18px] shrink-0"
                     alt="icon">
              </div>
              <hr class="border-desa-background">
              <p class="font-semibold text-lg">Bantuan Untuk anak kurang gizi</p>
              <div class="flex items-center gap-3">
                <div class="flex size-[52px] shrink-0 items-center justify-center rounded-2xl bg-desa-foreshadow">
                  <img src="@/assets/images/icons/bag-2-dark-green.svg" alt="icon">
                </div>
                <div class="flex flex-col gap-[6px] w-full">
                  <p class="font-semibold text-lg leading-5">Susu 200 Liter</p>
                  <p class="font-medium text-sm text-desa-secondary">Bahan Pokok</p>
                </div>
                <div class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-red">
                  <span class="font-semibold text-xs text-white uppercase">Ditolak</span>
                </div>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Nominal Pengajuan:</p>
                <p class="font-medium leading-5 text-desa-red">Susu 200ml</p>
              </div>
            </div>
          </div>
          <div id="Events" class="flex flex-col gap-6 hidden">
            <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Fri, 3 Jan 2025</p>
                <img src="@/assets/images/icons/calendar-2-secondary-green.svg" class="flex size-[18px] shrink-0"
                     alt="icon">
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center gap-3">
                <div
                    class="flex w-20 h-[60px] shrink-0 items-center justify-center rounded-2xl bg-desa-foreshadow overflow-hidden">
                  <img src="@/assets/images/thumbnails/event-image-1.png" class="w-full h-full object-cover"
                       alt="thumbnail">
                </div>
                <div class="flex flex-col gap-[6px] w-full">
                  <p class="font-semibold leading-5 line-clamp-1">Belajar HTML Dasar Bersama</p>
                  <div class="flex items-center gap-1">
                    <img src="@/assets/images/icons/profile-2user-orange.svg" class="flex size-[18px] shrink-0"
                         alt="icon">
                    <p class="font-medium text-sm text-desa-orange">9210 total partisipasi</p>
                  </div>
                </div>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Harga Event:</p>
                <p class="font-medium leading-5 text-desa-red">Rp49.000</p>
              </div>
            </div>
            <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Wed, 1 Jan 2025</p>
                <img src="@/assets/images/icons/calendar-2-secondary-green.svg" class="flex size-[18px] shrink-0"
                     alt="icon">
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center gap-3">
                <div
                    class="flex w-20 h-[60px] shrink-0 items-center justify-center rounded-2xl bg-desa-foreshadow overflow-hidden">
                  <img src="@/assets/images/thumbnails/kk-dashboard-2.png" class="w-full h-full object-cover"
                       alt="thumbnail">
                </div>
                <div class="flex flex-col gap-[6px] w-full">
                  <p class="font-semibold leading-5 line-clamp-1">Dari Desa ke dunia digital: workshop</p>
                  <div class="flex items-center gap-1">
                    <img src="@/assets/images/icons/profile-2user-orange.svg" class="flex size-[18px] shrink-0"
                         alt="icon">
                    <p class="font-medium text-sm text-desa-orange">9210 total partisipasi</p>
                  </div>
                </div>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Harga Event:</p>
                <p class="font-medium leading-5 text-desa-red">Rp49.000</p>
              </div>
            </div>
            <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Sun, 21 Dec 2024</p>
                <img src="@/assets/images/icons/calendar-2-secondary-green.svg" class="flex size-[18px] shrink-0"
                     alt="icon">
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center gap-3">
                <div
                    class="flex w-20 h-[60px] shrink-0 items-center justify-center rounded-2xl bg-desa-foreshadow overflow-hidden">
                  <img src="@/assets/images/thumbnails/kk-event-desa-3.png" class="w-full h-full object-cover"
                       alt="thumbnail">
                </div>
                <div class="flex flex-col gap-[6px] w-full">
                  <p class="font-semibold leading-5 line-clamp-1">Mengenal AI: Menjelajah dunia Kecerdasan</p>
                  <div class="flex items-center gap-1">
                    <img src="@/assets/images/icons/profile-2user-orange.svg" class="flex size-[18px] shrink-0"
                         alt="icon">
                    <p class="font-medium text-sm text-desa-orange">9210 total partisipasi</p>
                  </div>
                </div>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center justify-between">
                <p class="font-medium text-sm text-desa-secondary">Harga Event:</p>
                <p class="font-medium leading-5 text-desa-red">Rp49.000</p>
              </div>
            </div>
          </div>
          <div id="Applicants" class="flex flex-col gap-6 hidden">
            <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
              <div class="flex items-center justify-between gap-3">
                <div
                    class="flex w-20 h-[60px] shrink-0 items-center justify-center rounded-2xl bg-desa-foreshadow overflow-hidden">
                  <img src="@/assets/images/thumbnails/event-image-1.png" class="w-full h-full object-cover"
                       alt="thumbnail">
                </div>
                <div class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-yellow">
                  <span class="font-semibold text-xs text-white uppercase">Menunggu</span>
                </div>
              </div>
              <div class="flex flex-col gap-1">
                <p class="font-semibold leading-5">Pembangunan Jalanan Utama</p>
                <p class="font-medium leading-5 text-desa-secondary">
                  Penanggung jawab:
                  <span class="font-semibold text-desa-dark-green">
                                                        Uzumaki Asep
                                                    </span>
                </p>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center gap-3">
                <div
                    class="flex size-12 shrink-0 rounded-full bg-desa-foreshadow overflow-hidden items-center justify-center">
                  <img src="@/assets/images/icons/calendar-2-dark-green.svg" class="flex size-6" alt="icon">
                </div>
                <div>
                  <p class="font-semibold leading-5 text-desa-dark-green">3 Jan 2025</p>
                  <p class="font-medium text-sm text-desa-secondary">Tanggal Pelaksanaan</p>
                </div>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center gap-3">
                <div
                    class="flex size-12 shrink-0 rounded-full bg-desa-foreshadow overflow-hidden items-center justify-center">
                  <img src="@/assets/images/icons/timer-dark-green.svg" class="flex size-6" alt="icon">
                </div>
                <div>
                  <p class="font-semibold leading-5 text-desa-dark-green">24 Hari</p>
                  <p class="font-medium text-sm text-desa-secondary">Waktu Pelaksanaan</p>
                </div>
              </div>
            </div>
            <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
              <div class="flex items-center justify-between gap-3">
                <div
                    class="flex w-20 h-[60px] shrink-0 items-center justify-center rounded-2xl bg-desa-foreshadow overflow-hidden">
                  <img src="@/assets/images/thumbnails/event-image-1.png" class="w-full h-full object-cover"
                       alt="thumbnail">
                </div>
                <div class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-dark-green">
                  <span class="font-semibold text-xs text-white uppercase">Diterima</span>
                </div>
              </div>
              <div class="flex flex-col gap-1">
                <p class="font-semibold leading-5">Pembangunan Jalanan Utama</p>
                <p class="font-medium leading-5 text-desa-secondary">
                  Penanggung jawab:
                  <span class="font-semibold text-desa-dark-green">
                                                        Uzumaki Asep
                                                    </span>
                </p>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center gap-3">
                <div
                    class="flex size-12 shrink-0 rounded-full bg-desa-foreshadow overflow-hidden items-center justify-center">
                  <img src="@/assets/images/icons/calendar-2-dark-green.svg" class="flex size-6" alt="icon">
                </div>
                <div>
                  <p class="font-semibold leading-5 text-desa-dark-green">3 Jan 2025</p>
                  <p class="font-medium text-sm text-desa-secondary">Tanggal Pelaksanaan</p>
                </div>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center gap-3">
                <div
                    class="flex size-12 shrink-0 rounded-full bg-desa-foreshadow overflow-hidden items-center justify-center">
                  <img src="@/assets/images/icons/timer-dark-green.svg" class="flex size-6" alt="icon">
                </div>
                <div>
                  <p class="font-semibold leading-5 text-desa-dark-green">24 Hari</p>
                  <p class="font-medium text-sm text-desa-secondary">Waktu Pelaksanaan</p>
                </div>
              </div>
            </div>
            <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
              <div class="flex items-center justify-between gap-3">
                <div
                    class="flex w-20 h-[60px] shrink-0 items-center justify-center rounded-2xl bg-desa-foreshadow overflow-hidden">
                  <img src="@/assets/images/thumbnails/event-image-1.png" class="w-full h-full object-cover"
                       alt="thumbnail">
                </div>
                <div class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-red">
                  <span class="font-semibold text-xs text-white uppercase">Ditolak</span>
                </div>
              </div>
              <div class="flex flex-col gap-1">
                <p class="font-semibold leading-5">Pembangunan Jalanan Utama</p>
                <p class="font-medium leading-5 text-desa-secondary">
                  Penanggung jawab:
                  <span class="font-semibold text-desa-dark-green">
                                                        Uzumaki Asep
                                                    </span>
                </p>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center gap-3">
                <div
                    class="flex size-12 shrink-0 rounded-full bg-desa-foreshadow overflow-hidden items-center justify-center">
                  <img src="@/assets/images/icons/calendar-2-dark-green.svg" class="flex size-6" alt="icon">
                </div>
                <div>
                  <p class="font-semibold leading-5 text-desa-dark-green">3 Jan 2025</p>
                  <p class="font-medium text-sm text-desa-secondary">Tanggal Pelaksanaan</p>
                </div>
              </div>
              <hr class="border-desa-background">
              <div class="flex items-center gap-3">
                <div
                    class="flex size-12 shrink-0 rounded-full bg-desa-foreshadow overflow-hidden items-center justify-center">
                  <img src="@/assets/images/icons/timer-dark-green.svg" class="flex size-6" alt="icon">
                </div>
                <div>
                  <p class="font-semibold leading-5 text-desa-dark-green">24 Hari</p>
                  <p class="font-medium text-sm text-desa-secondary">Waktu Pelaksanaan</p>
                </div>
              </div>
            </div>
          </div>
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
  /></template>
