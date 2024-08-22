<script setup>
    const emit = defineEmits(['update:isOpen', 'change']);
    const props = defineProps({
        isOpen: {
            type: Boolean,
            required: true
        },
        notes: {
            type: String,
            required: true
        },
    })

    const display = ref(false);

    function open() {
        display.value = true;
    }

    function close() {
        display.value = false;
    }
</script>

<template>
    <Dialog header="Dialog" v-model:visible="display" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
        <p class="line-height-3 m-0">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        <template #footer>
            <Button label="Ok" @click="close" icon="pi pi-check" outlined />
        </template>
    </Dialog>
    <Button label="Show" icon="pi pi-external-link" style="width: auto" @click="open" />
</template>

