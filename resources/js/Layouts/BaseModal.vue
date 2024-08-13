<template>
    <div v-if="isModalOpen" class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-slate-800 rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full p-4">
                <!-- Modal Header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-slate-700">
                    <!-- Title -->
                    <h3 class="text-xl font-semibold text-lime-300 dark:text-lime-300">
                        <slot name="title"></slot>
                    </h3>

                    <!-- Clone Button -->
                    <button @click="closeModal" class="float-right text-slate-300 hover:text-slate-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->

                <div class="mt-4">
                    <slot name="content"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
    import { ref, watchEffect } from 'vue';
    
    // Props for controlling visibility from the parent component
    const props = defineProps({
        modelValue: Boolean,
    });
    
    const isModalOpen = ref(props.modelValue);
    
    // Watch for changes in modelValue to update the modal's visibility
    watchEffect(() => {
        isModalOpen.value = props.modelValue;
    });
    
    // Emit update to the modelValue when closing the modal
    const emit = defineEmits(['update:modelValue']);
    const closeModal = () => {
        emit('update:modelValue', false);
    };
</script>