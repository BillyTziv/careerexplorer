<template>
    <section :id="sectionId">
        <slot name="header"></slot>
        <slot></slot>
  </section>
</template>

<script setup>
    import { defineProps } from 'vue';

    const props = defineProps({
        sectionId: {
            type: String,
            default: null,
        },
    });
</script>