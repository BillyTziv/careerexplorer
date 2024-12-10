<script setup>
	import { watch, ref, computed } from 'vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';

    const emit = defineEmits([
        'change'
    ]);

    const props = defineProps({
        notes: {
            type: String,
            required: true,
            default: ''
        },
        showModal: {
            type: Boolean,
            required: true,
            default: false
        }
    })

    watch(() => props.notes, (newVal) => {
        notes.value = newVal;
    });

    const notes = ref( props.notes );

    function submit() {
        emit('change', notes.value );
    }
</script>

<template>
    <Dialog
        header="Σημειώσεις Συνεδρίας"
        v-model:visible="props.showModal"
        maximizable
        :style="{ width: '100vw' }"
        :modal="true"
    >
        <form @submit.prevent="submit">
            <BaseTextareaInput
                v-model="notes"
                label="Σχόλια"
                :rows="25"
                placeholder="Αφήστε ένα σχόλιο για την συνεδρία"
            />

            <!-- <textarea
                :rows="22"
                v-model="notes"
                class=" my-4 block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg dark:border-b border-gray-300 focus:ring-lime-300 focus:border-lime-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-slate-300 dark:focus:ring-lime-300 dark:focus:border-lime-300"
            ></textarea> -->
        </form>

        <Button
            @click="submit"
            label="Αποθήκευση"
            icon="pi pi-check"
        />
    </Dialog>

    <!-- <Button label="Επεξεργασία Σημειώσεων" class="m-1" icon="pi pi-pencil" style="width: auto" @click="open" /> -->
</template>

