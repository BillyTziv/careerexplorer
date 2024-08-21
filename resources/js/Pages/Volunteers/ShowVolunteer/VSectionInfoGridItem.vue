<template>
    <div class="field grid">
        <label class="col-6 mb-2 md:col-6 label-fixed-width">{{ label }}</label>
        <div class="col-6 md:col-6">
            <span class="font-bold">{{ value }}</span>
        </div>
    </div>
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

<style scoped>
.label-fixed-width {
    width: 120px;
}
</style>