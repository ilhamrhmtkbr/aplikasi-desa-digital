<template>
  <div
      v-if="message"
      :class="[
      'px-4 py-3 rounded-2xl relative mb-4 border',
      type === 'success'
        ? 'bg-green-100 border-green-400 text-green-700'
        : 'bg-red-100 border-red-400 text-red-700'
    ]"
      role="alert"
  >
    <span class="block sm:inline">{{ message }}</span>

    <button
        type="button"
        @click="$emit('close')"
        class="absolute top-1/2 -translate-y-1/2 right-4"
    >
      <img
          src="@/assets/images/icons/close-circle-white.svg"
          class="flex size-6 shrink-0"
          alt="icon"
      />
    </button>
  </div>
</template>

<script setup>
defineProps({
  type: {
    type: String,
    default: 'success',
  },
  message: {
    type: String,
    default: null,
  },
})

defineEmits(['close'])
</script>
