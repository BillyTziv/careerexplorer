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
        careerValue: Object,
        hollandCodes: {
            type: Array,
            default: () => []
        }
    });

    const careerValueForm = useForm({
        id: props.careerValue?.id ?? null,
        name: props.careerValue?.name ?? null,
        description: props.careerValue?.description ?? null,
        hollandCodes: props.careerValue?.hollandCodes ?? []
    });

    const isEditMode = computed(() => {
        return careerValueForm.id > 0
    });

    function submit() {
        if( careerValueForm.id && careerValueForm.id > 0 ) {
            careerValueForm.put('/career-values/'+ careerValueForm.id, careerValueForm);
        }else {
            careerValueForm.post('/career-values/', careerValueForm);
        }
    }    
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία</span>
            <span v-if="isEditMode">Επεξεργασία</span>
            Εργασιακής Αξίας
        </template>

        <template #page-actions> </template>

        <template #page-content>
            <form @submit.prevent="submit" autocomplete="off">
                <AppFormLayout>
                    <BaseTextInput
                        v-model="careerValueForm.name"
                        label="Όνομα"
                        placeholder="Όνομα εργασιακής αξίας.."
                        :required=true
                        :errors="errors['name']"
                    />

                    <BaseTextareaInput
                        v-model="careerValueForm.description"
                        label="Περιγραφή"
                        placeholder="Περιέγραψε την εργασιακής αξίας.."
                        :errors="errors.description"
                    />

                    <BaseMultiselectInput
                        v-model="careerValueForm.hollandCodes"
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