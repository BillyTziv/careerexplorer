<!-- Modal.vue -->
<template>
    <div v-if="isOpen" >
        <div tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto">
            <!-- Backdrop (add this div) -->
            <div class="fixed inset-0 bg-black opacity-60"></div>

            <div class="relative w-full max-w-2xl max-h-full z-10">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Αποδοχή Εθελοντή
                        </h3>
                        <button @click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <span class="text-white">Επιλέξτε τον ρόλο που θα έχει ο εθελοντής στον οργανισμό:</span>
                        <BaseSingleSelect
                            v-model="volunteerRole"
                            :options="roles"
                            label="Ρόλος"
                            accessKey="role"
                            placeholder="Επίλεξε έναν ρόλο"
                            :errors="''"
                        />
                    </div>
                    <!-- Modal footer -->
                    <div v-if="volunteerRole" class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button
                            @click="approveVolunteer"
                          
                            class="flex items-center justify-start text-white bg-gradient-to-r from-green-500 via-green-600 to-green-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-3 py-1.5 text-center"
                        >
                            Αποδοχή
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
	import { ref } from 'vue';
  	import { Link } from '@inertiajs/inertia-vue3';
    import BaseSingleSelect from '@/Pages/Common/UI/Form/BaseSingleSelect.vue'

    const emit = defineEmits(['update:isOpen', 'approve']);
    const props = defineProps({
        isOpen: {
            type: Boolean,
            required: true
        },
        volunteerId: {
            type: Number,
            required: true
        },
        roles: {
            type: Array,
            required: true
        }
    })

    const volunteerRole = ref("");

    function closeModal() {
       emit('update:isOpen', false);
    }

    function approveVolunteer() {
        emit('approve', parseInt(volunteerRole.value) );
        emit('update:isOpen', false);
    }
</script>