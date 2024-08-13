<template>
    <small
        class="my-2 px-4 py-4 block text-left rounded-md border-l-2 bg-blue-50 border-blue"
        :class="{
            'text-red-500': type === 'error',
            'text-green-500': type === 'success',
            'text-blue-400 ': type === 'info'
        }"
    >
        <slot></slot>
    </small>
</template>

<script setup>
    const props = defineProps({
        type: {
            type: String,
            required: true
        }
    });
</script>