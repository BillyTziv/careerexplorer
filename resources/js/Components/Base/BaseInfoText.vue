<template>
    <div
        class="my-3 p-2 rounded-lg border-left-2 border-blue bg-blue-50 "
        :class="{
            'text-red-500': type === 'error',
            'text-green-500': type === 'success',
            'text-blue-400 ': type === 'info'
        }"
    >
        <small class="text-sm">
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