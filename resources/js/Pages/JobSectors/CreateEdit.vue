<script setup>
    import { computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    
    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    /* Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';

    let props = defineProps({
        user: Object,
        jobSector: {
            type: Object,
            default: () => null
        },
        errors: {
            type: Object,
            default: () => ({}),
        }
    });

    const jobSectorForm = useForm({
        id: props.jobSector?.id ?? null,
        title: props.jobSector?.title ?? "",
        description: props.jobSector?.description ?? "",
        icon: props.jobSector?.icon ?? "",
    });

    const isEditMode = computed(() => {
        return jobSectorForm.id > 0
    });

    function submit() {
        if( jobSectorForm.id && jobSectorForm.id > 0 ) {
            jobSectorForm.put('/job-sectors/'+ jobSectorForm.id, jobSectorForm);
        }else {
            jobSectorForm.post('/job-sectors/', jobSectorForm);
        }
    }
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία </span>
            <span v-if="isEditMode">Επεξεργασία </span>
            Κλάδου
        </template>

        <template #page-content>
            <form @submit.prevent="submit" autocomplete="off">
                <AppFormLayout>
                    <BaseTextInput
                        v-model="jobSectorForm.title"
                        label="Τίτλος Κλάδου"
                        :required="true"
                        :errors="errors.title"
                    />

                    <BaseTextareaInput
                        v-model="jobSectorForm.description"
                        label="Περιγραφή Κλάδου"
                        :errors="errors.description"
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