<script setup>
import {storeToRefs} from "pinia";
import {ref} from "vue";
import {useProfileVillageStore} from "@/stores/profile-village.js";
import Input from "@/components/ui/Input.vue";

import IconBuilding4SecondaryGreen from '@/assets/images/icons/building-4-secondary-green.svg';
import IconBuilding4Black from '@/assets/images/icons/building-4-black.svg';

import IconUserSquareSecondaryGreen from '@/assets/images/icons/user-square-secondary-green.svg';
import IconUserSquareBlack from '@/assets/images/icons/user-square-black.svg';

import IconTreeSecondaryGreen from '@/assets/images/icons/tree-secondary-green.svg';
import IconTreeBlack from '@/assets/images/icons/tree-black.svg';

import IconGrid5SecondaryGreen from '@/assets/images/icons/grid-5-secondary-green.svg';
import IconGrid5Black from '@/assets/images/icons/grid-5-black.svg';

import IconProfile2UserSecondaryGreen from '@/assets/images/icons/profile-2user-secondary-green.svg';
import IconProfile2UserBlack from '@/assets/images/icons/profile-2user-black.svg';

const store = useProfileVillageStore()
const {loading, error, success} = storeToRefs(store)
const {create} = store

const form = ref({
  thumbnail: [],
  thumbnail_url: [],
  name: null,
  location: null,
  headman: null,
  agricultural_area: null,
  total_area: null,
  people: null,
  about: null,
  images: [],
})

const photoForms = ref([{ id: 1, url: null, file: null }])
const fileInputRefs = ref([])
let nextId = 2

function onImageChange(e, index) {
  const file = e.target.files[0]
  if (file) {
    // Validasi tipe file
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png']
    if (!validTypes.includes(file.type)) {
      alert('File harus berupa gambar JPG, JPEG, atau PNG')
      e.target.value = '' // Reset input
      return
    }

    // Validasi ukuran (max 2MB)
    if (file.size > 2048 * 1024) {
      alert('Ukuran file maksimal 2MB')
      e.target.value = '' // Reset input
      return
    }

    // Simpan file asli
    photoForms.value[index].file = file

    // Buat preview URL
    const reader = new FileReader()
    reader.onload = (event) => {
      photoForms.value[index].url = event.target.result
      updateFormThumbnails()
    }
    reader.readAsDataURL(file)
  }
}

function updateFormThumbnails() {
  // Hanya simpan file asli yang valid
  form.value.thumbnail = photoForms.value
      .filter(p => p.file instanceof File) // Pastikan itu File object
      .map(p => p.file)

  // Simpan URL untuk preview
  form.value.thumbnail_url = photoForms.value
      .map(p => p.url)
      .filter(Boolean)
}

function addPhotoForm() {
  photoForms.value.push({ id: nextId++, url: null, file: null })
}

function deletePhotoForm(index) {
  if (photoForms.value.length > 1) {
    photoForms.value.splice(index, 1)
    updateFormThumbnails()
  }
}

function triggerFileInput(index) {
  fileInputRefs.value[index]?.click()
}

const handleSubmit = async () => {
  const formData = new FormData()

  // THUMBNAIL: Kirim HANYA file pertama (single file, bukan array)
  if (photoForms.value[0]?.file instanceof File) {
    formData.append('thumbnail', photoForms.value[0].file)
  }

  // IMAGES: Kirim sisanya sebagai array (mulai dari index 1)
  photoForms.value.slice(1).forEach((photo) => {
    if (photo.file instanceof File) {
      formData.append('images[]', photo.file)
    }
  })

  // Field lainnya
  formData.append('name', form.value.name || '')
  formData.append('headman', form.value.headman || '')
  formData.append('agricultural_area', form.value.agricultural_area || '')
  formData.append('total_area', form.value.total_area || '')
  formData.append('people', form.value.people || '')
  formData.append('about', form.value.about || '')

  // Debug
  console.log('FormData entries:')
  for (const [key, value] of formData.entries()) {
    if (value instanceof File) {
      console.log(key, '(File):', value.name, value.type, value.size)
    } else {
      console.log(key, ':', value)
    }
  }

  await create(formData)
}
</script>

<template>
  <div id="Header" class="flex items-center justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex gap-1 items-center leading-5 text-desa-secondary">
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Profile Desa</p>
        <span>/</span>
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Create Profile Desa</p>
      </div>
      <h1 class="font-semibold text-2xl">Create Profile Desa</h1>
    </div>
  </div>
  <form @submit.prevent="handleSubmit" id="myForm" class="capitalize">
    <div class="shrink-0 rounded-3xl p-6 bg-white flex flex-col gap-6 h-fit">
      <section id="Photos" class="flex justify-between">
        <h2 class="font-medium leading-5 text-desa-secondary flex h-[100px] items-center w-[calc(424/904*100%)]">Thumbnail Profile Desa</h2>
        <div class="photo-input-container flex flex-col gap-6 flex-1">
          <div
              v-for="(photo, index) in photoForms"
              :key="photo.id"
              :class="['photo-form group/parent flex items-center justify-between', index > 0 ? 'new' : '']"
          >
            <div class="Photo-Preview flex itce justify-center w-[120px] h-[100px] rounded-2xl overflow-hidden bg-desa-foreshadow">
              <img class="Photo size-full object-cover" :src="photo.url || '@/assets/images/thumbnail/thumbnail-bansos-preview.svg'" alt="image"/>
            </div>
            <div class="relative">
              <input
                  :ref="el => fileInputRefs[index] = el"
                  :required="index === 0"
                  type="file"
                  @change="onImageChange($event, index)"
                  class="photo-input absolute opacity-0 left-0 top-0 size-0 -z-10"
              />
              <div class="action flex gap-3">
                <button
                    type="button"
                    @click="triggerFileInput(index)"
                    class="Upload-btn relative flex items-center py-4 px-6 rounded-2xl bg-desa-black gap-[10px]"
                >
                  <img src="@/assets/images/icons/send-square-white.svg" alt="icon" class="size-6 shrink-0" />
                  <p class="font-medium leading-5 text-white">Upload</p>
                </button>
                <button
                    v-if="index > 0"
                    type="button"
                    @click="deletePhotoForm(index)"
                    class="delete size-14 rounded-2xl p-4 bg-desa-red flex items-center justify-center"
                >
                  <img src="@/assets/images/icons/trash-white.svg" class="flex size-6 shrink-0" alt="icon">
                </button>
              </div>
            </div>
          </div>
          <button
              type="button"
              @click="addPhotoForm"
              class="add-more-btn flex items-center w-full justify-center rounded-2xl py-4 px-6 gap-3 bg-desa-foreshadow"
          >
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
              v-model="form.name"
              type="text"
              placeholder="Ketik nama desa"
              :error-message="error?.name?.[0]"
              :icon="IconBuilding4SecondaryGreen"
              :filled-icon="IconBuilding4Black"
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
              v-model="form.headman"
              type="text"
              placeholder="Pilih Kepala Desa"
              :error-message="error?.headman?.[0]"
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
          >
            <template #suffix>
              <span class="font-medium leading-5 normal-case">m<sup>2</sup></span>
            </template>
          </Input>
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
              :icon="IconGrid5SecondaryGreen"
              :filled-icon="IconGrid5Black"
          >
            <template #suffix>
              <span class="font-medium leading-5 normal-case">m<sup>2</sup></span>
            </template>
          </Input>
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Jumlah Penduduk" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Jumlah Penduduk Desa</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.people"
              type="number"
              placeholder="Masukan total penduduk desa"
              :error-message="error?.people?.[0]"
              :icon="IconProfile2UserSecondaryGreen"
              :filled-icon="IconProfile2UserBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Deskripsi" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Deskripsi Tentang Desa</p>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <textarea v-model="form.about" placeholder="Jelaskan lebih detail tentang desa terkait" rows="6" class="appearance-none outline-none w-full rounded-2xl ring-[1.5px] ring-desa-background focus:ring-desa-black py-4 px-4 gap-2 font-medium placeholder:text-desa-secondary transition-all duration-300"></textarea>
        </div>
      </section>
      <hr class="border-desa-background w-[calc(100%+48px)] -mx-6" />
      <section id="Buttons" class="flex items-center justify-end gap-4">
        <RouterLink :to="{name:'profileVillage'}">
          <div class="py-[18px] rounded-2xl bg-desa-red w-[180px] text-center flex justify-center font-medium text-white">Batal, Tidak jadi</div>
        </RouterLink>
        <button :disabled="loading" id="submitButton" type="submit" class="py-[18px] rounded-2xl disabled:bg-desa-grey w-[180px] text-center flex justify-center font-medium text-white bg-desa-dark-green transition-all duration-300">
          <span v-if="loading">Loading...</span>
          <span v-else>Create Now</span>
        </button>
      </section>
    </div>
  </form>
</template>