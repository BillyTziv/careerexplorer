<script setup>
const props = defineProps({
    title: { type: String, default: '' },
});
</script>

<template>
    <h2 class="text-2xl font-semibold">
        {{ props.title }}
    </h2>
</template>