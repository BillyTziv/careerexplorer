<script setup>
	import { reactive } from 'vue';
    import BaseInput from '@/Pages/Common/UI/Form/BaseInput.vue'

    import BaseInfoText from '@/Pages/Common/UI/Form/BaseInfoText.vue';
    import BaseFormSubmitButton from '@/Pages/Common/UI/Form/BaseFormSubmitButton.vue';
    import BaseToggleSwitchInput from '@/Components/Base/BaseToggleSwitchInput.vue';

    const emit = defineEmits([
        'update:isOpen', 
        'change'
    ]);

    const props = defineProps({
        isOpen: {
            type: Boolean,
            required: true
        },
        volunteerId: {
            type: Number,
            required: true
        }
    })

    const form = reactive({
        reason: '',
        sendEmail: false
    });

    function closeModal() {
        emit('update:isOpen', false);

        form.sendEmail = false;
        form.reason = '';
    }

    function submit() {
        emit('change', form );
        emit('update:isOpen', false);

        form.sendEmail = false;
        form.reason = '';
    }

    function toggleSendEmailOption() {
        form.sendEmail = !form.sendEmail;
    }
</script>

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
                            Αλλαγή Κατάστασης Εθελοντή
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
                            <BaseInput
                                v-model="form.reason"
                                accessKey="name"
                                label="Σχόλια"
                                type="text"
                                :errors="''"
                            />

                            <BaseInfoText
                                type="info"
                            >
                                Στα σχόλια μπορείτε να αφήσετε τον λόγο αλλαγής της κατάστασης του εθελοντή.
                            </BaseInfoText>
                        
                            <BaseToggleSwitchInput
                                :field="{ value: form.sendEmail }"
                                :label="'Αποστολή email ενημέρωσης στον εθελοντή'"
                                @update:field="toggleSendEmailOption(name, $event)"
                            />

                            <BaseInfoText
                                v-if="form.sendEmail"
                                type="info"
                            >
                                Ενεργοποιώντας αυτή την επιλογή ο εθελοντής θα λάβει ένα αυτοματοποιημένο email ενημέρωσης για την αλλαγή κατάστασης του λογαριασμού του όπως αυτό έχει οριστεί στα πρότυπα email.
                            </BaseInfoText>

                            <BaseFormSubmitButton
                                label="Αλλαγή Κατάστασης"
                            />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>