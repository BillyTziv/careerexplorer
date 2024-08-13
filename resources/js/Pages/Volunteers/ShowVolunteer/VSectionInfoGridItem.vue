<template>
    <label class="dark:text-slate-300">{{ label }}</label>
    <div class="flex-1 font-bold">{{ value }}</div>
</template>
  
<script setup>
  import { defineProps } from 'vue';

  const props = defineProps({
    label: {
      type: String,
      required: true,
    },
    value: {
      type: [String, null, Boolean, Number],
      required: true,
    },
  });
</script>