<script setup>
    import AppMenuItem from './AppMenuItem.vue';

    const props = defineProps({
        model: {
            type: Array,
            default: null
        },
        auth: {
            type: Object,
            default: {}
        }
    });
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <AppMenuItem :item="item" root :index="i" />
            <li class="menu-separator"></li>
        </template>
    </ul>
</template>
