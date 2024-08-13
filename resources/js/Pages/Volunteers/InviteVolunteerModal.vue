<script setup>
    import { router } from '@inertiajs/vue3'
    import { ref, watch } from 'vue';

    import ModalComponent from '@/Layouts/BaseModal.vue';

    /* Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';

    let emit = defineEmits(['closeModal']);

    let props = defineProps({
        showModal: {
            type: Boolean,
            default: false
        }
    });

    const invitationEmail = ref("");
    const isModalOpen = ref( true );
    const errors = ref("");

    async function sendInvitation() {
        if (!invitationEmail.value) {
            errors.value = "Email is required";
            return;
        }

        try {
            await router.post('/volunteers/sendInvitation', {
                email: invitationEmail.value
            });
            invitationEmail.value = ""; // Reset the form

            emit('closeModal');
        } catch (error) {
            errors.value = "Failed to send invitation";
        }
    }

    watch(() => isModalOpen.value, (value) => {
        if( value === false ) {
            errors.value = "";
            invitationEmail.value = "";

            emit('closeModal');
        }
    });
</script>

<template>
    <ModalComponent
        v-model="isModalOpen"
    >
        <template #title>
            <h3 class="text-xl font-semibold text-lime-300 dark:text-lime-300">
                Προσκληση Εθελοντή
            </h3>
        </template>

        <template #content>
            <BaseTextInput
                v-model="invitationEmail"
                label="Email"
                placeholder="Email.."
                :required=true
                :errors="errors['email']"
            />

            <Button 
                @click="sendInvitation"
                label="Αποθήκευση" 
                raised 
                class="mb-2 mr-2"
            />
        </template>
    </ModalComponent>
</template>