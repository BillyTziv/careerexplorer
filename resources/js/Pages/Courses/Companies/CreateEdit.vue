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
        courseCompany: Object
    });

    const courseCompanyForm = useForm({
        id: props.courseCompany?.id ?? null,
        name: props.courseCompany?.name ?? null
    });

    const isEditMode = computed(() => {
        return courseCompanyForm.id > 0
    });

    function submit() {
        if( courseCompanyForm.id && courseCompanyForm.id > 0 ) {
            courseCompanyForm.put('/course-companies/'+ courseCompanyForm.id, courseCompanyForm);
        }else {
            courseCompanyForm.post('/course-companies/', courseCompanyForm);
        }
    }    
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία Φορέα Εκπαίδευσης</span>
            <span v-if="isEditMode">Επεξεργασία Φορέα Εκπαίδευσης</span>
        </template>

        <template #page-actions> </template>

        <template #page-content>
            <form @submit.prevent="submit" autocomplete="off">
                <AppFormLayout>
                    <BaseTextInput
                        v-model="courseCompanyForm.name"
                        label="Όνομα"
                        placeholder="Όνομα Φορέα Εκπαίδευσης.."
                        :required=true
                        :errors="errors['name']"
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