<script setup>
    import { computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    
    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    /* Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseMultiselectInput from '@/Components/Base/BaseMultiselectInput.vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';
    
    let props = defineProps({
        user: Object,
        response: Object,
        errors: Object,
        interest: Object,
        hollandCodes: {
            type: Array,
            default: () => []
        }
    });

    const interestForm = useForm({
        id: props.interest?.id ?? null,
        name: props.interest?.name ?? null,
        description: props.interest?.description ?? null,
        hollandCodes: props.interest?.hollandCodes ?? []
    });

    const isEditMode = computed(() => {
        return interestForm.id > 0
    });

    function submit() {
        if( interestForm.id && interestForm.id > 0 ) {
            interestForm.put('/career-interests/'+ interestForm.id, interestForm);
        }else {
            interestForm.post('/career-interests/', interestForm);
        }
    }    
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία Ενδιαφέροντος</span>
            <span v-if="isEditMode">Επεξεργασία Ενδιαφέροντος</span>
        </template>

        <template #page-actions> </template>

        <template #page-content>
            <form @submit.prevent="submit" autocomplete="off">
                <AppFormLayout>
                    <BaseTextInput
                        v-model="interestForm.name"
                        label="Όνομα"
                        placeholder="Όνομα ενδιαφέροντος.."
                        :required=true
                        :errors="errors['name']"
                    />

                    <BaseTextareaInput
                        v-model="interestForm.description"
                        label="Περιγραφή"
                        placeholder="Περιέγραψε το ενδιαφέρον.."
                        :errors="errors.description"
                    />

                    <BaseMultiselectInput
                        v-model="interestForm.hollandCodes"
                        :options="hollandCodes"
                        label="Επίλεξε τους κωδικούς Holland"
                        :required=true
                        :errors="errors.hollandCodes"
                    />
                </AppFormLayout>
                
                <Button 
                    @click="submit"
                    label="Αποθήκευση" 
                    raised 
                    class="mb-2 mr-2"
                />
            </form>
        </template>
    </AppPageWrapper>
</template>