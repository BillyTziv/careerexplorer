<script setup>
    import { ref } from 'vue';

    const props = defineProps({
        cv: {
            type: String,
            default: '',
            required: true
        }
    })

    const display = ref(false);

    const open = () => {
        display.value = true;
    };

    const close = () => {
        display.value = false;
    };
</script>

<template>
    <Dialog header="Βιογραφικό" v-model:visible="display" maximizable :breakpoints="{ '960px': '75vw' }" :style="{ width: '100vw' }" :modal="true">
        <div :style="{ width: '100vw', height: '100vh' }">
            <embed :src="cv" type="application/pdf" width="100%" height="100%">
        </div>

        <template #footer>
            <Button label="Κλείσιμο" @click="close" icon="pi pi-check" outlined />
        </template>
    </Dialog>

    <Button label="Άνοιγμα Βιογραφικού" icon="pi pi-external-link" style="width: auto" @click="open" />
</template>
  
