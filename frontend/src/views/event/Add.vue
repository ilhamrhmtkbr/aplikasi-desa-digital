<script setup>
import {storeToRefs} from "pinia";
import {ref, watch} from "vue";
import {useEventStore} from "@/stores/event.js";
import {handleImageChange} from "@/helpers/imageHelper.js";
import Input from "@/components/ui/Input.vue";

import IconEditSecondaryGreen from '@/assets/images/icons/edit-secondary-green.svg';
import IconEditBlack from '@/assets/images/icons/edit-black.svg';

import IconDollarSquareSecondaryGreen from '@/assets/images/icons/dollar-square-secondary-green.svg';
import IconDollarSquareBlack from '@/assets/images/icons/dollar-square-black.svg';

import IconCalendarSecondaryGreen from '@/assets/images/icons/calendar-2-secondary-green.svg';
import IconCalendarBlack from '@/assets/images/icons/calendar-2-black.svg';

import IconTimerSecondaryGreen from '@/assets/images/icons/timer-secondary-green.svg';
import IconTimerBlack from '@/assets/images/icons/timer-black.svg';

const store = useEventStore()
const {loading, error, success} = storeToRefs(store)
const {create} = store

const form = ref({
  thumbnail: null,
  thumbnail_url: null,
  name: '',
  description: '',
  price: '',
  date: '',
  time: '',
  is_active: '',
})

function onImageChange(e) {
  handleImageChange(e, form, 'thumbnail')
}

const handleSubmit = async () => {
  await create(form.value)
}

watch(
    () => form.value.time,
    (newVal) => {
      if (!newVal) return
      const [hour, minute] = newVal.split(':')
      const h = String(hour).padStart(2, '0')
      const m = String(minute).padStart(2, '0')
      form.value.time = `${h}:${m}`
    }
)
</script>

<template>
  <div id="Header" class="flex items-center justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex gap-1 items-center leading-5 text-desa-secondary">
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Event Desa</p>
        <span>/</span>
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Add Event Desa</p>
      </div>
      <h1 class="font-semibold text-2xl">Add Event Desa</h1>
    </div>
  </div>
  <form @submit.prevent="handleSubmit" id="myForm" class="capitalize">
    <div class="shrink-0 rounded-3xl p-6 bg-white flex flex-col gap-6 h-fit">
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
      <section id="Nama" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nama Event</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.name"
              type="text"
              placeholder="Ketik nama project event"
              :error-message="error?.name?.[0]"
              :icon="IconEditSecondaryGreen"
              :filled-icon="IconEditBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Deskripsi" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Deskripsi Event</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <textarea v-model="form.description" placeholder="Jelaskan lebih detail tentang event" rows="6"
                    class="appearance-none outline-none w-full rounded-2xl ring-[1.5px] ring-desa-background focus:ring-desa-black py-4 px-4 gap-2 font-medium placeholder:text-desa-secondary transition-all duration-300"></textarea>
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Total-Dana" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Total Dana Event</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.price"
              type="number"
              placeholder="Ketik dana yang dibutuhkan"
              :error-message="error?.price?.[0]"
              :icon="IconWalletSecondaryGreen"
              :filled-icon="IconWalletBlack"
              prefix="Rp"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Tanggal-Event" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Tanggal Event</p>
        <div class="flex items-center gap-6 flex-1 shrink-0">
          <Input
              v-model="form.date"
              type="date"
              placeholder="Pilih Tanggal Event"
              :error-message="error?.date?.[0]"
              :icon="IconCalendarSecondaryGreen"
              :filled-icon="IconCalendarBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Waktu-Event" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Waktu Event</p>
        <div class="flex items-center gap-6 flex-1 shrink-0">
          <Input
              v-model="form.time"
              type="time"
              placeholder="Pilih Waktu Event"
              :error-message="error?.time?.[0]"
              :icon="IconCalendarSecondaryGreen"
              :filled-icon="IconCalendarBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Status" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Status Event</p>
        <div class="flex flex-1 gap-6 shrink-0">
          <label
              class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-desa-dark-green transition-setup">
            <input type="radio" name="is_active"
                   class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup"
                   v-model="form.is_active" value="1">
            <span
                class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
              Tidak Aktif
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
            <input type="radio" name="is_active"
                   class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup"
                   v-model="form.is_active" value="0">
            <span
                class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
              Aktif
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
      <hr class="border-desa-background w-[calc(100%+48px)] -mx-6"/>
      <section id="Buttons" class="flex items-center justify-end gap-4">
        <RouterLink :to="{name:'event'}">
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
