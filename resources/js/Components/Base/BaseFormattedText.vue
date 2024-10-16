<script setup>
    const props = defineProps({
        text: {
            type: String,
            default: ""
        }
    });
</script>

<template>
    <span style="white-space: pre-wrap;">
        {{ text }}
    </span>
</template>