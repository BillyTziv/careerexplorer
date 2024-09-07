<script setup>
    import { computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    
    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    /* Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';
    
    let props = defineProps({
        user: Object,
        response: Object,
        errors: Object,
        course: Object,
        hollandCodes: {
            type: Array,
            default: () => []
        },
        course: Object,
        companyDropdownOptions: Array
    });

    const courseForm = useForm({
        id: props.course.id ? props.course?.id : null,
        title: props.course.title ? props.course?.title : "",
        description: props.course.description ? props.course.description : "",
        link: props.course.link ? props.course.link : "",
        company_id: props.course.company_id ? props.course.company_id : null
    });

    const isEditMode = computed(() => {
        return courseForm.id > 0
    });

    function submit() {
        if( courseForm.id && courseForm.id > 0 ) {
            courseForm.put('/courses/'+ courseForm.id, courseForm);
        }else {
            courseForm.post('/courses/', courseForm);
        }
    }    
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία Μαθήματος</span>
            <span v-if="isEditMode">Επεξεργασία Μαθήματος</span>
        </template>

        <template #page-content>
            <form @submit.prevent="submit" autocomplete="off">
                <AppFormLayout>
                    <BaseTextInput
                        v-model="courseForm.title"
                        label="Τίτλος"
                        placeholder="Όνομα μαθήματος.."
                        :required=true
                        :errors="errors['title']"
                    />

                    <BaseTextareaInput
                        v-model="courseForm.description"
                        label="Περιγραφή"
                        placeholder="Περιέγραψε το μάθημα.."
                        :errors="errors['description']"
                    />

                    <BaseTextInput
                        v-model="courseForm.link"
                        label="Σύνδεσμος"
                        placeholder="Σύνδεσμος προς το μάθημα.."
                        :required=true
                        :errors="errors['link']"
                    />

                    <BaseDropdownInput
                        v-model="courseForm.company_id"
                        :options="companyDropdownOptions"
                        label="Φορέας"
                        :required=true
                        :errors="errors['company_id']"
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