<template>
    <div
        class="mx-2 mb-4 p-3 block text-left rounded-md border-left-2 border-blue bg-blue-50 "
        :class="{
            'text-red-500': type === 'error',
            'text-green-500': type === 'success',
            'text-blue-400 ': type === 'info'
        }"
    >
        <small>
            <slot></slot>
        </small>
    </div>
</template>

<script setup>
    const props = defineProps({
        type: {
            type: String,
            default: 'info',
            required: true
        }
    });
</script>