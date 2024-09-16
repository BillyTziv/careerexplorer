<script setup>
    const props = defineProps({
        fullWidth: {
            type: Boolean,
            default: false
        },
        background: {
            type: String,
            default: ''
        }
    });
</script>

<template>
    <div class="px-0 md:px-6 lg:px-0 py-5 sm:py-15">
        <slot></slot>
    </div>
</template>