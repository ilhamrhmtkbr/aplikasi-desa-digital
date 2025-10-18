<script setup>
import {storeToRefs} from "pinia";
import {ref, onMounted} from "vue";
import {useRoute} from "vue-router";
import {useProfileVillageStore} from "@/stores/profile-village.js";
import {handleImageChange} from "@/helpers/imageHelper.js";
import Input from "@/components/ui/Input.vue";

import IconBuildingSecondaryGreen from '@/assets/images/icons/building-4-secondary-green.svg';
import IconBuildingBlack from '@/assets/images/icons/building-4-black.svg';

import IconUserSquareSecondaryGreen from '@/assets/images/icons/user-square-secondary-green.svg';
import IconUserSquareBlack from '@/assets/images/icons/user-square-black.svg';

import IconTreeSecondaryGreen from '@/assets/images/icons/tree-secondary-green.svg';
import IconTreeBlack from '@/assets/images/icons/tree-black.svg';

import IconGridSecondaryGreen from '@/assets/images/icons/grid-5-secondary-green.svg';
import IconGridBlack from '@/assets/images/icons/grid-5-black.svg';

import IconProfile2UserSecondaryGreen from '@/assets/images/icons/profile-2user-secondary-green.svg';
import IconProfile2UserBlack from '@/assets/images/icons/profile-2user-black.svg';

const route = useRoute()
const store = useProfileVillageStore()
const {loading, error, success} = storeToRefs(store)
const {edit, show} = store

const form = ref({
  photos: [],
  village_name: null,
  location: null,
  village_head: null,
  agricultural_area: null,
  total_area: null,
  population: null,
  description: null
})

const photoInputs = ref([])

onMounted(async () => {
  const id = route.params.id
  const data = await show(id)
  if (data) {
    form.value = {...data}
  }
})

function onImageChange(e, index) {
  handleImageChange(e, form, 'photos', index)
}

const handleSubmit = async () => {
  const id = route.params.id
  await edit(id, form.value)
}
</script>

<template>
  <div id="Header" class="flex items-center justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex gap-1 items-center leading-5 text-desa-secondary">
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Profile Desa</p>
        <span>/</span>
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Edit Profile Desa</p>
      </div>
      <h1 class="font-semibold text-2xl">Edit Profile Desa</h1>
    </div>
  </div>
  <form @submit.prevent="handleSubmit" id="myForm" class="capitalize">
    <div class="shrink-0 rounded-3xl p-6 bg-white flex flex-col gap-6 h-fit">
      <section id="Photos" class="flex justify-between">
        <h2 class="font-medium leading-5 text-desa-secondary flex h-[100px] items-center w-[calc(424/904*100%)]">Thumbnail Profile Desa</h2>
        <div class="photo-input-container flex flex-col gap-6 flex-1">
          <div v-for="(photo, index) in form.photos" :key="index" class="photo-form group/parent flex items-center justify-between" :class="{ 'new': index > 0 }">
            <div class="Photo-Preview flex itce justify-center w-[120px] h-[100px] rounded-2xl overflow-hidden bg-desa-foreshadow">
              <img class="Photo size-full object-cover" :src="photo" alt="image"/>
            </div>
            <div class="relative">
              <input
                  @change="(e) => onImageChange(e, index)"
                  :ref="el => photoInputs[index] = el"
                  type="file"
                  class="photo-input absolute opacity-0 left-0 top-0 size-0 -z-10" />
              <div class="action flex gap-3">
                <button type="button" class="Upload-btn relative flex items-center py-4 px-6 rounded-2xl bg-desa-black gap-[10px]" @click="photoInputs[index].click()">
                  <img src="@/assets/images/icons/send-square-white.svg" alt="icon" class="size-6 shrink-0" />
                  <p class="font-medium leading-5 text-white">Upload</p>
                </button>
                <button v-if="index > 0" type="button" class="delete size-14 rounded-2xl p-4 bg-desa-red flex items-center justify-center" @click="form.photos.splice(index, 1)">
                  <img src="@/assets/images/icons/trash-white.svg" class="flex size-6 shrink-0" alt="icon">
                </button>
              </div>
            </div>
          </div>
          <button type="button" class="add-more-btn flex items-center w-full justify-center rounded-2xl py-4 px-6 gap-3 bg-desa-foreshadow" @click="form.photos.push(null)">
            <p class="font-medium leading-5 text-desa-dark-green">Tambah Gambar Desa</p>
            <img src="@/assets/images/icons/add-square-dark-green.svg" class="flex size-6 shrink-0" alt="icon">
          </button>
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Nama-Desa" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nama Desa</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.village_name"
              type="text"
              placeholder="Ketik nama desa"
              :error-message="error?.village_name?.[0]"
              :icon="IconBuildingSecondaryGreen"
              :filled-icon="IconBuildingBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Lokasi" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Lokasi Desa</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <textarea v-model="form.location" placeholder="Ketik alamat desa" rows="6" class="appearance-none outline-none w-full rounded-2xl ring-[1.5px] ring-desa-background focus:ring-desa-black py-4 px-4 gap-2 font-medium placeholder:text-desa-secondary transition-all duration-300"></textarea>
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Kepala-Desa" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nama Kepala Desa</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.village_head"
              type="text"
              placeholder="Pilih Kepala Desa"
              :error-message="error?.village_head?.[0]"
              :icon="IconUserSquareSecondaryGreen"
              :filled-icon="IconUserSquareBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Luas-Pertanian" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Luas Pertanian Desa</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.agricultural_area"
              type="number"
              placeholder="Masukan total luas pertanian"
              :error-message="error?.agricultural_area?.[0]"
              :icon="IconTreeSecondaryGreen"
              :filled-icon="IconTreeBlack"
              suffix="m²"
          />
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Luas-Area" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Luas Area Desa</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.total_area"
              type="number"
              placeholder="Masukan total luas area"
              :error-message="error?.total_area?.[0]"
              :icon="IconGridSecondaryGreen"
              :filled-icon="IconGridBlack"
              suffix="m²"
          />
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Jumlah Penduduk" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Jumlah Penduduk Desa</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.population"
              type="number"
              placeholder="Masukan total penduduk desa"
              :error-message="error?.population?.[0]"
              :icon="IconProfile2UserSecondaryGreen"
              :filled-icon="IconProfile2UserBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Deskripsi" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Deskripsi Tentang Desa</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <textarea v-model="form.description" placeholder="Jelaskan lebih detail tentang desa terkait" rows="6" class="appearance-none outline-none w-full rounded-2xl ring-[1.5px] ring-desa-background focus:ring-desa-black py-4 px-4 gap-2 font-medium placeholder:text-desa-secondary transition-all duration-300"></textarea>
        </div>
      </section>
      <hr class="border-desa-background w-[calc(100%+48px)] -mx-6" />
      <section id="Buttons" class="flex items-center justify-end gap-4">
        <RouterLink :to="{name:'profile-village'}">
          <div class="py-[18px] rounded-2xl bg-desa-red w-[180px] text-center flex justify-center font-medium text-white">Batal, Tidak jadi</div>
        </RouterLink>
        <button :disabled="loading" id="submitButton" type="submit" class="py-[18px] rounded-2xl disabled:bg-desa-grey w-[180px] text-center flex justify-center font-medium text-white bg-desa-dark-green transition-all duration-300">
          <span v-if="loading">Loading...</span>
          <span v-else>Save Changes</span>
        </button>
      </section>
    </div>
  </form>
</template>