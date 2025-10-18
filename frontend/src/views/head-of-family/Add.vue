<script setup>
import {storeToRefs} from "pinia";
import {ref} from "vue";
import {useHeadOfFamilyStore} from "@/stores/head-of-family.js";
import {handleImageChange} from "@/helpers/imageHelper.js";
import Input from "@/components/ui/Input.vue";

import IconProfileSecondaryGreen from '@/assets/images/icons/user-secondary-green.svg';
import IconProfileBlack from '@/assets/images/icons/user-black.svg';

import IconKeyboardSecondaryGreen from '@/assets/images/icons/keyboard-secondary-green.svg';
import IconKeyboardBlack from '@/assets/images/icons/keyboard-black.svg';

import IconCallSecondaryGreen from '@/assets/images/icons/call-secondary-green.svg';
import IconCallBlack from '@/assets/images/icons/call-black.svg';

import IconBriefCaseSecondaryGreen from '@/assets/images/icons/briefcase-secondary-green.svg';
import IconBriefCaseBlack from '@/assets/images/icons/briefcase-black.svg';

import IconCalendarSecondaryGreen from '@/assets/images/icons/calendar-2-secondary-green.svg';
import IconCalendarBlack from '@/assets/images/icons/calendar-2-black.svg';

import IconKeySecondaryGreen from '@/assets/images/icons/key-secondary-green.svg';
import IconKeyBlack from '@/assets/images/icons/key-black.svg';

const store = useHeadOfFamilyStore()
const {loading, error, success} = storeToRefs(store)
const {create} = store
const form = ref({
  name: null,
  email: null,
  password: null,
  profile_picture: null,
  profile_picture_url: null,
  identify_number: null,
  gender: null,
  date_of_birth: null,
  phone_number: null,
  occupation: null,
  marital_status: null
})

function onImageChange(e) {
  handleImageChange(e, form, 'profile_picture')
}

const handleSubmit = async () => {
  await create(form.value)
}

</script>

<template>
  <div id="Header" class="flex items-center justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex gap-1 items-center leading-5 text-desa-secondary">
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Kepala Rumah</p>
        <span>/</span>
        <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Tambah Kepala Rumah</p>
      </div>
      <h1 class="font-semibold text-2xl">Tambah Kepala Rumah Baru</h1>
    </div>
  </div>
  <form @submit.prevent="handleSubmit" id="myForm" class="capitalize">
    <div class="shrink-0 rounded-3xl p-6 bg-white flex flex-col gap-6 h-fit">
      <section id="Photo-Profile" class="flex items-center justify-between">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Profile Kepala Rumah</h2>
        <div class="flex-1 flex items-center justify-between">
          <div id="Photo-Preview"
               class="flex itce justify-center size-[100px] rounded-full overflow-hidden bg-desa-foreshadow">
            <img id="Photo" :src="form.profile_picture_url" alt="image" class="size-full object-cover"/>
          </div>
          <div class="relative">
            <input @change="onImageChange"
                   required
                   id="File"
                   type="file"
                   name="file" class="absolute opacity-0 left-0 w-full top-0 h-full"
                   ref="profile_picture"/>
            <button id="Upload" type="button"
                    class="relative flex items-center py-4 px-6 rounded-2xl bg-desa-black gap-[10px]"
                    @click="$refs.profile_picture.click()"
            >
              <img src="@/assets/images/icons/send-square-white.svg" alt="icon" class="size-6 shrink-0"/>
              <p class="font-medium leading-5 text-white">Upload</p>
            </button>
          </div>
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Nama-Kepala-Rumah" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nama Kepala Rumah</p>

        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.name"
              type="text"
              placeholder="Ketik Nama Kamu"
              :error-message="error?.name?.[0]"
              :icon="IconProfileSecondaryGreen"
              :filled-icon="IconProfileBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="NIK" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nomor Induk Kependudukan</p>

        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.identify_number"
              type="number"
              placeholder="Ketik NIK Kamu"
              :error-message="error?.identify_number?.[0]"
              :icon="IconKeyboardSecondaryGreen"
              :filled-icon="IconKeyboardBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Phone" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nomor Handphone</p>

        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.phone_number"
              type="number"
              placeholder="Ketik Nomor Handphone Kamu"
              :error-message="error?.phone_number?.[0]"
              :icon="IconCallSecondaryGreen"
              :filled-icon="IconCallBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Pekerjaan" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Pekerjaan</p>

        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.occupation"
              type="text"
              placeholder="Ketik Pekerjaan Kamu"
              :error-message="error?.occupation?.[0]"
              :icon="IconBriefCaseSecondaryGreen"
              :filled-icon="IconBriefCaseBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Tanggal-Lahir" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Tanggal Lahir</p>

        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.date_of_birth"
              type="date"
              placeholder="Ketik Tanggal Lahir Kamu"
              :error-message="error?.date_of_birth?.[0]"
              :icon="IconCalendarSecondaryGreen"
              :filled-icon="IconCalendarBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Jenis-Kelamin" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Jenis Kelamin</p>
        <div class="flex flex-1 gap-6 shrink-0">
          <label
              class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-desa-dark-green transition-setup">
            <input type="radio" name="gender" id=""
                   class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup"
                   v-model="form.gender" value="male">
            <span
                class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
                                            Pria
                                        </span>
            <div class="flex size-6 shrink-0">
              <img src="@/assets/images/icons/man-secondary-green.svg" class="size-6 flex group-has-[:checked]:hidden"
                   alt="icon">
              <img src="@/assets/images/icons/man-dark-green.svg" class="size-6 hidden group-has-[:checked]:flex"
                   alt="icon">
            </div>
          </label>
          <label
              class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-desa-dark-green transition-setup">
            <input type="radio" name="gender" id=""
                   class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup"
                   v-model="form.gender" value="female">
            <span
                class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
                                            Wanita
                                        </span>
            <div class="flex size-6 shrink-0">
              <img src="@/assets/images/icons/woman-secondary-green.svg" class="size-6 flex group-has-[:checked]:hidden"
                   alt="icon">
              <img src="@/assets/images/icons/woman-dark-green.svg" class="size-6 hidden group-has-[:checked]:flex"
                   alt="icon">
            </div>
          </label>
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Status" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Status</p>
        <div class="flex flex-1 gap-6 shrink-0">
          <label
              class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-desa-dark-green transition-setup">
            <input type="radio" name="status" id=""
                   class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup"
                   v-model="form.marital_status" value="single">
            <span
                class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
                                            Belum Menikah
                                        </span>
            <div class="flex size-6 shrink-0">
              <img src="@/assets/images/icons/profile-secondary-green.svg"
                   class="size-6 flex group-has-[:checked]:hidden" alt="icon">
              <img src="@/assets/images/icons/profile-dark-green.svg" class="size-6 hidden group-has-[:checked]:flex"
                   alt="icon">
            </div>
          </label>
          <label
              class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-desa-dark-green transition-setup">
            <input type="radio" name="status" id=""
                   class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup"
                   v-model="form.marital_status" value="married">
            <span
                class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
                                            Sudah Menikah
                                        </span>
            <div class="flex size-6 shrink-0">
              <img src="@/assets/images/icons/profile-2user-secondary-green.svg"
                   class="size-6 flex group-has-[:checked]:hidden" alt="icon">
              <img src="@/assets/images/icons/profile-2user-dark-green.svg"
                   class="size-6 hidden group-has-[:checked]:flex" alt="icon">
            </div>
          </label>
        </div>
      </section>
      <hr class="border-desa-background w-[calc(100%+48px)] -mx-6"/>
      <p class="font-medium leading-5">Akun Dashboard</p>
      <section id="Email" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Email Address</p>

        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.email"
              type="email"
              placeholder="Ketik Email Kamu"
              :error-message="error?.email?.[0]"
              :icon="IconProfileSecondaryGreen"
              :filled-icon="IconProfileBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Passwords" class="flex items-center justify-between">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Passwords</p>

        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.password"
              type="password"
              placeholder="Ketik Password Kamu"
              :error-message="error?.password?.[0]"
              :icon="IconKeySecondaryGreen"
              :filled-icon="IconKeyBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background"/>
      <section id="Buttons" class="flex items-center justify-end gap-4">
        <RouterLink :to="{name:'headOfFamily'}">
          <div
              class="py-[18px] rounded-2xl bg-desa-red w-[180px] text-center flex justify-center font-medium text-white">
            Batal, Tidak jadi
          </div>
        </RouterLink>
        <button :disabled="loading" id="submitButton" type="submit"
                class="py-[18px] rounded-2xl disabled:bg-desa-grey w-[180px] text-center flex justify-center font-medium text-white bg-desa-dark-green transition-all duration-300">
          <span v-if="loading">Loading...</span>
          <span v-else>Create Now</span>
        </button>
      </section>
    </div>
  </form>
</template>
