<!-- Modal.vue -->
<template>
    <div v-if="isOpen" >
        <div tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto">
            <!-- Backdrop (add this div) -->
            <div class="fixed inset-0 bg-black opacity-60"></div>

            <div class="relative w-full max-w-2xl max-h-full z-10">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-slate-700">
                        <h3 class="text-xl font-semibold text-lime-300 dark:text-lime-300">
                            Επεξεργασία Σημειώσεων Εθελοντή
                        </h3>
                        <button @click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <div class="p-4">
                        <form @submit.prevent="submit">
                            <textarea
                                :rows="22"
                                v-model="notes"
                                class=" my-4 block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg dark:border-b border-gray-300 focus:ring-lime-300 focus:border-lime-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-slate-300 dark:focus:ring-lime-300 dark:focus:border-lime-300"
                            ></textarea>
                            
                            <!-- <BaseInfoText type="info">
                                Στα σχόλια μπορείτε να αφήσετε τον λόγο αλλαγής της κατάστασης του εθελοντή.
                            </BaseInfoText> -->
                        
                            <BaseFormSubmitButton
                                label="Ενημέρωση Σημειώσεων"
                            />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
	import { watch, ref, onBeforeMount } from 'vue';
    import BaseInput from '@/Pages/Common/UI/Form/BaseInput.vue'

    import BaseInfoText from '@/Pages/Common/UI/Form/BaseInfoText.vue';
    import BaseFormSubmitButton from '@/Pages/Common/UI/Form/BaseFormSubmitButton.vue';
    import BaseTextarea from '@/Pages/Common/UI/Form/BaseTextarea.vue';


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

    watch(() => props.notes, (newVal) => {
        notes.value = newVal;
    });

    const notes = ref( props.notes );

    function closeModal() {
       emit('update:isOpen', false);
    }

    function submit() {
        emit('change', notes.value );
        emit('update:isOpen', false);
    }
</script>