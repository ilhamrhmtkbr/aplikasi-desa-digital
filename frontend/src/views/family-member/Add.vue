<script setup>
import {storeToRefs} from "pinia";
import {ref} from "vue";
import {useFamilyMemberStore} from "@/stores/family-member.js";
import {handleImageChange} from "@/helpers/imageHelper.js";
import Input from "@/components/ui/Input.vue";

import IconUserSecondaryGreen from '@/assets/images/icons/user-secondary-green.svg';
import IconUserBlack from '@/assets/images/icons/user-black.svg';

import IconKeyboardSecondaryGreen from '@/assets/images/icons/keyboard-secondary-green.svg';
import IconKeyboardBlack from '@/assets/images/icons/keyboard-black.svg';

import IconBriefCaseSecondaryGreen from '@/assets/images/icons/briefcase-secondary-green.svg';
import IconBriefCaseBlack from '@/assets/images/icons/briefcase-black.svg';

import IconCalendarSecondaryGreen from '@/assets/images/icons/calendar-2-secondary-green.svg';
import IconCalendarBlack from '@/assets/images/icons/calendar-2-black.svg';

import IconCallSecondaryGreen from '@/assets/images/icons/call-secondary-green.svg';
import IconCallBlack from '@/assets/images/icons/call-black.svg';

import IconSmsSecondaryGreen from '@/assets/images/icons/sms-secondary-green.svg';
import IconSmsBlack from '@/assets/images/icons/sms-black.svg';

import {calculateAge} from "@/helpers/formatHelper.js";
import IconKeySecondaryGreen from "@/assets/images/icons/key-secondary-green.svg";
import IconKeyBlack from "@/assets/images/icons/key-black.svg";
import {useRoute} from "vue-router";

const store = useFamilyMemberStore()
const {loading, error, success} = storeToRefs(store)
const {create} = store
const form = ref({
  profile_picture: null,
  profile_picture_url: null,
  gender: null,
  name: null,
  identify_number: null,
  occupation: null,
  date_of_birth: null,
  marital_status: null,
  relation: null,
  phone_number: null,
  email: null,
  password: null
})

function onImageChange(e) {
  handleImageChange(e, form, 'profile_picture')
}

const handleSubmit = async () => {
  await create(form.value)
}
</script>

<template>
  <header class="flex flex-col gap-2">
    <p class="flex items-center gap-1"><span class="leading-5 text-desa-secondary">Kepala Keluarga</span><span class="font-medium leading-5 text-desa-dark-green">/</span><span class="font-medium leading-5 text-desa-dark-green">Tambah Anggota Keluarga</span></p>
    <h1 class="font-semibold text-2xl leading-[30px]">Tambah Anggota Keluarga</h1>
  </header>
  <form @submit.prevent="handleSubmit" id="myForm" class="capitalize">
    <div class="shrink-0 rounded-3xl p-6 bg-white flex flex-col gap-6 h-fit">
      <section id="Photo-Profile" class="flex items-center">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Photo Profile</h2>
        <div class="flex items-center justify-between flex-1">
          <div id="Photo-Preview" class="flex itce justify-center size-[100px] rounded-full overflow-hidden bg-desa-foreshadow">
            <img id="Photo" :src="form.profile_picture_url" alt="image" class="size-full object-cover" />
          </div>
          <div class="relative">
            <input @change="onImageChange" required id="File" type="file" class="absolute opacity-0 left-0 w-full top-0 h-full" ref="profile_picture" />
            <button id="Upload" type="button" class="relative flex items-center py-4 px-6 rounded-2xl bg-desa-black gap-[10px]" @click="$refs.profile_picture.click()">
              <img src="@/assets/images/icons/send-square-white.svg" alt="icon" class="size-6 shrink-0" />
              <p class="font-medium leading-5 text-white">Upload</p>
            </button>
          </div>
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Jenis-Kelamin" class="flex items-center">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Jenis Kelamin</h2>
        <div class="flex-1 grid grid-cols-2 gap-6">
          <label class="relative group flex items-center justify-between rounded-2xl border border-desa-background p-4 hover:border-transparent hover:bg-desa-foreshadow has-[:checked]:bg-desa-foreshadow has-[:checked]:border-transparent transition-all duration-300">
            <div class="flex items-center gap-2">
              <input required type="radio" name="gender" id="pria" class="flex size-[18px] shrink-0 accent-desa-dark-green" v-model="form.gender" value="male" />
              <p class="w-full font-medium text-desa-secondary leading-5">Pria</p>
            </div>
            <img src="@/assets/images/icons/man-secondary-green.svg" class="flex size-6 shrink-0 group-has-[:checked]:hidden" alt="icon" />
            <img src="@/assets/images/icons/man-dark-green.svg" class="hidden size-6 shrink-0 group-has-[:checked]:flex" alt="icon" />
          </label>
          <label class="relative group flex items-center justify-between rounded-2xl border border-desa-background p-4 hover:border-transparent hover:bg-desa-foreshadow has-[:checked]:bg-desa-foreshadow has-[:checked]:border-transparent transition-all duration-300">
            <div class="flex items-center gap-2">
              <input required type="radio" name="gender" id="wanita" class="flex size-[18px] shrink-0 accent-desa-dark-green" v-model="form.gender" value="female" />
              <p class="w-full font-medium text-desa-secondary leading-5">Wanita</p>
            </div>
            <img src="@/assets/images/icons/woman-secondary-green.svg" class="flex size-6 shrink-0 group-has-[:checked]:hidden" alt="icon" />
            <img src="@/assets/images/icons/woman-dark-green.svg" class="hidden size-6 shrink-0 group-has-[:checked]:flex" alt="icon" />
          </label>
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Nama-Keluarga" class="flex items-center">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nama Keluarga</h2>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.name"
              type="text"
              placeholder="Masukan nama lengkap keluarga"
              :error-message="error?.name?.[0]"
              :icon="IconUserSecondaryGreen"
              :filled-icon="IconUserBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Nomor-Induk-Kependudukan" class="flex items-center">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nomor Induk Kependudukan</h2>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.identify_number"
              type="number"
              placeholder="Ketik NIK"
              :error-message="error?.identify_number?.[0]"
              :icon="IconKeyboardSecondaryGreen"
              :filled-icon="IconKeyboardBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Pekerjaan" class="flex items-center">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Pekerjaan</h2>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.occupation"
              type="text"
              placeholder="Masukan nama pekerjaan"
              :error-message="error?.occupation?.[0]"
              :icon="IconBriefCaseSecondaryGreen"
              :filled-icon="IconBriefCaseBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Tanggal-Lahir" class="flex items-center">
        <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Tanggal Lahir</p>
        <div class="flex items-center gap-6 flex-1 shrink-0">
          <div class="flex flex-col gap-3 flex-1 shrink-0">
            <Input
                v-model="form.date_of_birth"
                type="date"
                placeholder="Masukan tanggal lahir"
                :error-message="error?.date_of_birth?.[0]"
                :icon="IconCalendarSecondaryGreen"
                :filled-icon="IconCalendarBlack"
            />
          </div>
          <div class="w-[180px] flex shrink-0 h-[52px] rounded-2xl bg-desa-foreshadow p-4 font-medium leading-5 text-desa-dark-green justify-center">
            <p>Umur: <span id="Age">{{calculateAge(form.date_of_birth)}}</span> tahun</p>
          </div>
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Status" class="flex items-center">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Status</h2>
        <div class="flex-1 grid grid-cols-2 gap-6">
          <label class="relative group flex items-center justify-between rounded-2xl border border-desa-background p-4 hover:border-transparent hover:bg-desa-foreshadow has-[:checked]:bg-desa-foreshadow has-[:checked]:border-transparent transition-all duration-300">
            <div class="flex items-center gap-2">
              <input required type="radio" name="status" id="Belum-Menikah" class="flex size-[18px] shrink-0 accent-desa-dark-green" v-model="form.marital_status" value="single" />
              <p class="w-full font-medium text-desa-secondary leading-5">Belum Menikah</p>
            </div>
            <img src="@/assets/images/icons/profile-secondary-green.svg" class="flex size-6 shrink-0 group-has-[:checked]:hidden" alt="icon" />
            <img src="@/assets/images/icons/profile-dark-green.svg" class="hidden size-6 shrink-0 group-has-[:checked]:flex" alt="icon" />
          </label>
          <label class="relative group flex items-center justify-between rounded-2xl border border-desa-background p-4 hover:border-transparent hover:bg-desa-foreshadow has-[:checked]:bg-desa-foreshadow has-[:checked]:border-transparent transition-all duration-300">
            <div class="flex items-center gap-2">
              <input required type="radio" name="status" id="Sudah-Menikah" class="flex size-[18px] shrink-0 accent-desa-dark-green" v-model="form.marital_status" value="married" />
              <p class="w-full font-medium text-desa-secondary leading-5">Sudah Menikah</p>
            </div>
            <img src="@/assets/images/icons/profile-2user-secondary-green.svg" class="flex size-6 shrink-0 group-has-[:checked]:hidden" alt="icon" />
            <img src="@/assets/images/icons/profile-2user-dark-green.svg" class="hidden size-6 shrink-0 group-has-[:checked]:flex" alt="icon" />
          </label>
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Kategori-Keluarga" class="flex items-center">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Kategori Keluarga</h2>
        <div class="flex-1 grid grid-cols-3 gap-6">
          <label class="relative flex items-center gap-2 rounded-2xl border border-desa-background p-4 hover:border-transparent hover:bg-desa-foreshadow has-[:checked]:bg-desa-foreshadow hover:ring-[1.5px] hover:ring-desa-dark-green has-[:checked]:ring-[1.5px] has-[:checked]:ring-desa-dark-green transition-all duration-300">
            <input required type="radio" name="kategori-keluarga" id="Istri" class="peer flex size-[18px] shrink-0 accent-desa-dark-green" v-model="form.relation" value="wife" />
            <p class="w-full font-medium text-desa-secondary leading-5 peer-checked:text-desa-dark-green">Istri</p>
          </label>
          <label class="relative flex items-center gap-2 rounded-2xl border border-desa-background p-4 hover:border-transparent hover:bg-desa-foreshadow has-[:checked]:bg-desa-foreshadow hover:ring-[1.5px] hover:ring-desa-dark-green has-[:checked]:ring-[1.5px] has-[:checked]:ring-desa-dark-green transition-all duration-300">
            <input required type="radio" name="kategori-keluarga" id="Anak" class="peer flex size-[18px] shrink-0 accent-desa-dark-green" v-model="form.relation" value="child" />
            <p class="w-full font-medium text-desa-secondary leading-5 peer-checked:text-desa-dark-green">Anak</p>
          </label>
          <label class="relative flex items-center gap-2 rounded-2xl border border-desa-background p-4 hover:border-transparent hover:bg-desa-foreshadow has-[:checked]:bg-desa-foreshadow hover:ring-[1.5px] hover:ring-desa-dark-green has-[:checked]:ring-[1.5px] has-[:checked]:ring-desa-dark-green transition-all duration-300">
            <input required type="radio" name="kategori-keluarga" id="Suami" class="peer flex size-[18px] shrink-0 accent-desa-dark-green" v-model="form.relation" value="husband" />
            <p class="w-full font-medium text-desa-secondary leading-5 peer-checked:text-desa-dark-green">Suami</p>
          </label>
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Nomor-Handphone" class="flex items-center">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nomor Handphone</h2>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.phone_number"
              type="tel"
              placeholder="Masukan No. HP yang aktif"
              :error-message="error?.phone_number?.[0]"
              :icon="IconCallSecondaryGreen"
              :filled-icon="IconCallBlack"
          />
        </div>
      </section>
      <hr class="border-desa-background" />
      <section id="Email-Address" class="flex items-center">
        <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Email Address</h2>
        <div class="flex flex-col gap-3 flex-1 shrink-0">
          <Input
              v-model="form.email"
              type="email"
              placeholder="Masukan Email"
              :error-message="error?.email?.[0]"
              :icon="IconSmsSecondaryGreen"
              :filled-icon="IconSmsBlack"
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
      <section id="Buttons" class="flex items-center justify-end gap-4">
        <RouterLink :to="{name:'familyMember'}">
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