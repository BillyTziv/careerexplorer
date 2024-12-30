<script setup>
    import { computed } from 'vue';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    import { useForm } from '@inertiajs/vue3';

    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';
    import BaseInfoText from '@/Components/Base/BaseInfoText.vue';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
    import BaseColorPickerInput from '@/Components/Base/BaseColorPickerInput.vue';
    import BaseToggleSwitchInput from '@/Components/Base/BaseToggleSwitchInput.vue';

    import { useToastNotification } from '@/Composables/useToastNotification';

    const { notify } = useToastNotification();

    let props = defineProps({
        user: Object,
        taskStatus: Object,
        emailTemplates: Array,
        errors: Object,
        response: Object
    });

    const taskStatusForm = useForm({
        id: props.taskStatus.id ? props.taskStatus.id : null,
        name: props.taskStatus.name ? props.taskStatus.name : "",
        description: props.taskStatus.description ? props.taskStatus.description : "",
        hex_color: props.taskStatus.hex_color ? props.taskStatus.hex_color : "#",
        is_default: props.taskStatus.is_default ? true : false,
        email_template_id: props.taskStatus.email_template_id ? parseInt(props.taskStatus.email_template_id) : null,
    });

    const isEditMode = computed(() => { return taskStatusForm.id > 0 } );
    const submitButtonLabel = computed(() => { return isEditMode.value ? 'Ενημέρωση' : 'Δημιουργία' });

    function saveTaskStatus() {
        if( taskStatusForm.id > 0 ) {
            taskStatusForm.put('/task-statuses/' + taskStatusForm.id, {
                preserveScroll: true,
                onSuccess: () => {
                    notify('success', 'Επιτυχία', 'Η κατάσταση εργασίας ενημερώθηκε επιτυχώς.');
                    taskStatusForm.reset('role')
                },
            });
        } else {
            taskStatusForm.post('/task-statuses', {
                preserveScroll: true,
                onSuccess: () => {
                    notify('success', 'Επιτυχία', 'Η κατάσταση εργασίας δημιουργήθηκε επιτυχώς.');
                    taskStatusForm.reset('role')
                },
            });
        }
    }
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία Κατάστασης Εργασίας</span>
            <span v-if="isEditMode">Επεξεργασία Κατάστασης Εργασίας</span>
        </template>

        <template #page-content>
            <form @submit.prevent="saveTaskStatus" autocomplete="off">
                <AppFormLayout>

                    <!-- Task Status Name -->
                    <BaseTextInput
                        v-model="taskStatusForm.name"
                        label="Όνομα Κατάστασης"
                        :errors="errors.name"
                    />

                    <!-- Task Status Description -->
                    <BaseTextareaInput
                        v-model="taskStatusForm.description"
                        label="Περιγραφή Κατάστασης"
                        :rows="10"
                        :errors="errors.description"
                    />

                    <!-- Associated Email Template -->
                    <BaseDropdownInput
                        :options="props.emailTemplates"
                        v-model="taskStatusForm.email_template_id"
                        placeholder="Επιλέξτε πρότυπο email"
                        label="Πρότυπο Email"
                        :errors="errors.role"
                    ></BaseDropdownInput>

                    <BaseInfoText
                        type="info"
                    >
                        Το πρότυπο email που θα σταλεί όταν η κατάσταση αλλάξει.
                    </BaseInfoText>

                    <!-- Task Status HEX Color -->
                    <BaseColorPickerInput
                        v-model="taskStatusForm.hex_color"
                        label="Χρώμα Κατάστασης"
                        :errors="errors.hex_color"
                    />

                    <!-- Is Task Status Default? -->
                    <BaseToggleSwitchInput
                        v-model="taskStatusForm.is_default"
                        label="Ορισμός ως Προεπιλεγμένη Κατάσταση"
                    />

                    <BaseInfoText
                        type="info"
                    >
                        Η προεπιλεγμένη κατάσταση ορίζεται αυτόματα στις νέες εργασίες.
                    </BaseInfoText>
                </AppFormLayout>

                <Button
                    @click="saveTaskStatus"
                    :label="submitButtonLabel"
                    raised
                    class="mb-2 mt-4"
                />
            </form>
        </template>
    </AppPageWrapper>
</template>
