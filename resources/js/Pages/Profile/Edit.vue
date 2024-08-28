<script setup>
    import { computed, } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    
    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    /* Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseNumberInput from '@/Components/Base/BaseNumberInput.vue';
    import BaseEmailInput from '@/Components/Base/BaseEmailInput.vue';
    
    let props = defineProps({
        auth: Object,
        userData: Object,
        errors: Object,
    });
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span >Το Προφίλ μου</span>
        </template>

        <template #page-content>
            <div class="p-fluid formgrid grid">
                <BaseTextInput
                    v-model="auth.user.firstName"
                    label="Όνομα"
                    placeholder="Όνομα.."
                    readonly="true"
                    :errors="errors.firstname"
                    class="col-12 md:col-6"
                />

                <BaseTextInput
                    v-model="auth.user.lastName" 
                    label="Επίθετο"
                    placeholder="Επίθετο.."
                    readonly="true"
                    :errors="errors.lastname"
                    class="col-12 md:col-6"
                />
            </div>

            <div class="p-fluid formgrid grid">
                <BaseTextInput
                    v-model="auth.user.role" 
                    label="Ρόλος"
                    readonly="true"
                    :errors="errors.role"
                    class="col-12 md:col-6"
                />

                <BaseEmailInput
                    v-model="auth.user.email" 
                    label="Email"
                    placeholder="Email.."
                    readonly="true"
                    :errors="errors.email"
                    class="col-12 md:col-6"
                />
            </div>
        </template>
    </AppPageWrapper>
</template>