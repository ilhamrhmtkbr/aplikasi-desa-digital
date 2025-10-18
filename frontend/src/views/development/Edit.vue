<script setup>
import {storeToRefs} from "pinia";
import {ref, onMounted, watch} from "vue";
import {useRoute} from "vue-router";

import {useDevelopmentStore} from "@/stores/development.js";
import {handleImageChange} from "@/helpers/imageHelper.js";
import Input from "@/components/ui/Input.vue";

import IconWalletSecondaryGreen from '@/assets/images/icons/wallet-3-secondary-green.svg';
import IconWalletBlack from '@/assets/images/icons/wallet-3-black.svg';

import IconEditSecondaryGreen from '@/assets/images/icons/edit-secondary-green.svg';
import IconEditBlack from '@/assets/images/icons/edit-black.svg';

import IconProfileCircleSecondaryGreen from '@/assets/images/icons/profile-circle-secondary-green.svg';
import IconProfileCircleBlack from '@/assets/images/icons/profile-circle-black.svg';

import IconCalendarSecondaryGreen from '@/assets/images/icons/calendar-2-secondary-green.svg';
import IconCalendarBlack from '@/assets/images/icons/calendar-2-black.svg';

import IconTimerSecondaryGreen from '@/assets/images/icons/tick-circle-secondary-green.svg';
import IconTimerBlack from '@/assets/images/icons/tick-circle-dark-green.svg';

const route = useRoute()
const store = useDevelopmentStore()
const {loading, error} = storeToRefs(store)
const {edit, fetchById} = store

const form = ref({
  amount: null,
  thumbnail: null,
  thumbnail_url: null,
  name: null,
  person_in_charge: null,
  status: null,
  end_date: null,
  start_date: null,
  day: null,
  description: null,
})

onMounted(async () => {
  const id = route.params.id
  const data = await fetchById(id)
  if (data) {
    form.value = {
      thumbnail_url: data.thumbnail_url || null,
      amount: data.amount || null,
      name: data.name,
      person_in_charge: data.person_in_charge,
      status: data.status,
      start_date: data.start_date,
      end_date: data.end_date,
      description: data.description,
      day: Math.round((new Date(data.end_date).getTime() - new Date(data.start_date).getTime()) / (24 * 60 * 60 * 1000))
    }
  }
})

function onImageChange(e) {
  handleImageChange(e, form, 'thumbnail')
}

const handleSubmit = async () => {
  const id = route.params.id
  await edit(form.value, id)
}

// Watch untuk menghitung end_date berdasarkan start_date dan day
watch(() => form.value.day, (newDay) => {
  if (form.value.start_date && newDay) {
    const startDate = new Date(form.value.start_date)
    startDate.setDate(startDate.getDate() + parseInt(newDay))
    form.value.end_date = startDate.toISOString().split('T')[0]
  }
})

watch(() => form.value.start_date, (newStartDate) => {
  if (newStartDate && form.value.day) {
    const startDate = new Date(newStartDate)
    startDate.setDate(startDate.getDate() + parseInt(form.value.day))
    form.value.end_date = startDate.toISOString().split('T')[0]
  }
})
</script>

<template>
  <div id="Header" class="flex items-center justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex gap-1 items-center leading-5 text-desa-secondary">
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Pembangunan Desa</p>
        <span>/</span>
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Edit Pembangunan Desa</p>
      </div>
      <h1 class="font-semibold text-2xl">Edit Pembangunan Desa</h1>
    </div>
  </div>
  <form @submit.prevent="handleSubmit" id="myForm" class="capitalize">
    <div class="shrink-0 rounded-3xl p-6 bg-white flex flex-col gap-6 h-fit">
      <section id="Total-Dana" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Total Dana Pembangunan</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.amount"
              type="number"
              placeholder="Ketik dana yang dibutuhkan"
              :error-message="error?.amount?.[0]"
              :icon="IconWalletSecondaryGreen"
              :filled-icon="IconWalletBlack"
              prefix="Rp"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Thumbnail" class="flex items-center justify-between">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Thumbnail Event Terkait</h2>
        <div class="flex-1 flex items-center justify-between">
          <div id="Photo-Preview"
               class="flex items-center justify-center w-[120px] h-[100px] rounded-2xl overflow-hidden bg-desa-foreshadow">
            <img v-if="form.thumbnail_url" id="Photo" :src="form.thumbnail_url" alt="image" class="size-full object-cover"/>
            <span v-else class="text-desa-secondary text-sm">No Image</span>
          </div>
          <div class="relative">
            <input @change="onImageChange" id="File" type="file" name="file" accept="image/*"
                   class="absolute opacity-0 left-0 w-full top-0 h-full" ref="thumbnail"/>
            <button id="Upload" type="button"
                    class="relative flex items-center py-4 px-6 rounded-2xl bg-desa-black gap-[10px]"
                    @click="$refs.thumbnail.click()">
              <img src="@/assets/images/icons/send-square-white.svg" alt="icon" class="size-6 shrink-0"/>
              <p class="font-medium leading-5 text-white">Upload</p>
            </button>
          </div>
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Nama-Projek" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nama Projek Pembangunan</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.name"
              type="text"
              placeholder="Ketik nama project pembangunan"
              :error-message="error?.name?.[0]"
              :icon="IconEditSecondaryGreen"
              :filled-icon="IconEditBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Penanggung-Jawab" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Penanggung Jawab</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.person_in_charge"
              type="text"
              placeholder="Ketik penanggung jawab"
              :error-message="error?.person_in_charge?.[0]"
              :icon="IconProfileCircleSecondaryGreen"
              :filled-icon="IconProfileCircleBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Status" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Status Pembangunan</p>
        <div class="flex flex-1 gap-6 shrink-0">
          <label
              class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-desa-dark-green transition-setup">
            <input type="radio" name="status"
                   class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup"
                   v-model="form.status" value="ongoing">
            <span
                class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
              On Going
            </span>
            <div class="flex size-6 shrink-0">
              <img src="@/assets/images/icons/tick-circle-secondary-green.svg"
                   class="size-6 flex group-has-[:checked]:hidden" alt="icon">
              <img src="@/assets/images/icons/tick-circle-dark-green.svg"
                   class="size-6 hidden group-has-[:checked]:flex" alt="icon">
            </div>
          </label>
          <label
              class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-desa-dark-green transition-setup">
            <input type="radio" name="status"
                   class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup"
                   v-model="form.status" value="completed">
            <span
                class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
              Completed
            </span>
            <div class="flex size-6 shrink-0">
              <img src="@/assets/images/icons/close-circle-secondary-green.svg"
                   class="size-6 flex group-has-[:checked]:hidden" alt="icon">
              <img src="@/assets/images/icons/close-circle-white.svg"
                   class="size-6 hidden group-has-[:checked]:flex" alt="icon">
            </div>
          </label>
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Tanggal-Pembangunan" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Tanggal Pembangunan</p>
        <div class="flex items-center gap-6 flex-1 shrink-0">
          <Input
              v-model="form.start_date"
              type="date"
              placeholder="Pilih Tanggal Pembangunan"
              :error-message="error?.start_date?.[0]"
              :icon="IconCalendarSecondaryGreen"
              :filled-icon="IconCalendarBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Hari" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Hari yang dibutuhkan</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.day"
              type="number"
              placeholder="Ketik hari yang dibutuhkan"
              :error-message="error?.day?.[0]"
              :icon="IconTimerSecondaryGreen"
              :filled-icon="IconTimerBlack"
              suffix="Hari"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Deskripsi" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Deskripsi Pembangunan</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <textarea v-model="form.description" placeholder="Jelaskan lebih detail tentang pembangunan" rows="6"
                    class="appearance-none outline-none w-full rounded-2xl ring-[1.5px] ring-desa-background focus:ring-desa-black py-4 px-4 gap-2 font-medium placeholder:text-desa-secondary transition-all duration-300"></textarea>
        </div>
      </section>
      <hr class="border-desa-background w-[calc(100%+48px)] -mx-6"/>
      <section id="Buttons" class="flex items-center justify-end gap-4">
        <RouterLink :to="{name:'developmentManage', params:{id:route.params.id}}">
          <div
              class="py-[18px] rounded-2xl bg-desa-red w-[180px] text-center flex justify-center font-medium text-white">
            Batal, Tidak jadi
          </div>
        </RouterLink>
        <button :disabled="loading" id="submitButton" type="submit"
                class="py-[18px] rounded-2xl disabled:bg-desa-grey w-[180px] text-center flex justify-center font-medium text-white bg-desa-dark-green transition-all duration-300">
          <span v-if="loading">Loading...</span>
          <span v-else>Save Changes</span>
        </button>
      </section>
    </div>
  </form>
</template>