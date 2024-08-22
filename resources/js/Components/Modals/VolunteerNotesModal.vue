<script setup>
	import { watch, ref } from 'vue';

    const emit = defineEmits([
        'change'
    ]);

    const props = defineProps({
        notes: {
            type: String,
            required: true
        },
    })

    watch(() => props.notes, (newVal) => {
        notes.value = newVal;
    });

    const notes = ref( props.notes );
    const display = ref(false);



    function submit() {
        emit('change', notes.value );
    }

    const open = () => {
        display.value = true;
    };

    const close = () => {
        display.value = false;
    };
</script>

<template>
    <Dialog header="Σημειώσεις Εθελοντή" v-model:visible="display" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
        <form @submit.prevent="submit">
            <textarea
                :rows="22"
                v-model="notes"
                class=" my-4 block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg dark:border-b border-gray-300 focus:ring-lime-300 focus:border-lime-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-slate-300 dark:focus:ring-lime-300 dark:focus:border-lime-300"
            ></textarea>
        </form>

        <Button
            @click="submit" 
            label="Αποθήκευση" 
            icon="pi pi-check"
        />
    </Dialog>

    <Button label="Άνοιγμα Σημειώσεων" class="m-1" icon="pi pi-external-link" style="width: auto" @click="open" />
</template>
  
