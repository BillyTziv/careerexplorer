<template>
    <label class="inline-flex items-center justify-start p-1 dark:text-slate-300">
        <input
            @input="$emit('update:modelValue', $event.target.checked)"
            :checked="isChecked"
            type="checkbox"
          
        />
        <span class="px-2">{{ label }}</span>
    </label>
</template>
  
<script setup>
    import { defineProps, computed } from 'vue';
  
    const props = defineProps({
        modelValue:     { type: Boolean, default: false },
        label:          { type: String, default: '' },
        description:    { type: String, default: '' }
    });

    const isChecked = computed({
        get() {
            return props.modelValue === true;
        },
        set(checked) {
            this.$emit('update:modelValue', checked);
        }
    });
</script>
  