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
        skill: Object,
        hollandCodes: {
            type: Array,
            default: () => []
        }
    });

    const skillForm = useForm({
        id: props.skill?.id ?? null,
        name: props.skill?.name ?? null,
        description: props.skill?.description ?? null,
        hollandCodes: props.skill?.hollandCodes ?? []
    });

    const isEditMode = computed(() => {
        return skillForm.id > 0
    });

    function submit() {
        if( skillForm.id && skillForm.id > 0 ) {
            skillForm.put('/career-skills/'+ skillForm.id, skillForm);
        }else {
            skillForm.post('/career-skills/', skillForm);
        }
    }    
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία Δεξιότητας</span>
            <span v-if="isEditMode">Επεξεργασία Δεξιότητας</span>
        </template>

        <template #page-actions> </template>

        <template #page-content>
            <form @submit.prevent="submit" autocomplete="off">
                <AppFormLayout>
                    <BaseTextInput
                        v-model="skillForm.name"
                        label="Όνομα"
                        placeholder="Όνομα δεξιότητας.."
                        :required=true
                        :errors="errors['name']"
                    />

                    <BaseTextareaInput
                        v-model="skillForm.description"
                        label="Περιγραφή"
                        placeholder="Περιέγραψε τη δεξιότητα.."
                        :errors="errors.description"
                    />

                    <BaseMultiselectInput
                        v-model="skillForm.hollandCodes"
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