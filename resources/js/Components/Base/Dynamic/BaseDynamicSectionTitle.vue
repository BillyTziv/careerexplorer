<script setup>
    const props = defineProps({
        properties: { type: Object, default: {} }
    });
</script>

<template>
    <h5 class="text-primary">
        {{ properties.title }}
    </h5>

    <Divider align="left" layout="horizontal" class="text-primary h-full mx-0 lg:mx-3 surface-border"></Divider>
</template>