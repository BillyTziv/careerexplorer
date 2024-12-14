<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

/* Layouts */
import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
import AppFormLayout from '@/Layouts/AppFormLayout.vue';

/* Components */
import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';
import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
import BaseInfoText from '@/Components/Base/BaseInfoText.vue';

/* Props Definition */
const props = defineProps({
    task: Object, // Existing task data for edit mode; empty for create mode
    taskStatusDropdownOptions: Array, // Array of task statuses for dropdown
    volunteerDropdownOptions: Array, // Array of volunteers for dropdown
    errors: Object, // Validation errors from backend
    response: Object // Any additional response data
});

/* Initialize Form */
const taskForm = useForm({
    id: props.task?.id || null,
    task_name: props.task?.task_name || '',
    description: props.task?.description || '',
    volunteer_id: props.task?.volunteer_id || null,
    status_id: props.task?.status_id || null,
    estimated_time: props.task?.estimated_time || '',
});

/* Computed Properties */
const isEditMode = computed(() => taskForm.id !== null);
const submitButtonLabel = computed(() => isEditMode.value ? 'Ενημέρωση' : 'Δημιουργία');

const calcVolunteerDropdownOptions = computed(() => {
    return props.volunteerDropdownOptions?.map((volunteer) => {
        return {
            label: volunteer.name,
            id: volunteer.id
        }
    });
});

const calcTaskStatusDropdownOptions = computed(() => {
    return props.taskStatusDropdownOptions?.map((taskStatus) => {
        return {
            label: taskStatus.name,
            id: taskStatus.id
        }
    });
});


/* Submit Handler */
function saveTask() {
    if (isEditMode.value) {
        taskForm.put(`/tasks/${taskForm.id}`, {
            preserveScroll: true,
            onSuccess: () => taskForm.reset(),
        });
    } else {
        taskForm.post('/tasks', {
            preserveScroll: true,
            onSuccess: () => taskForm.reset(),
        });
    }
}
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία Task</span>
            <span v-else>Επεξεργασία Task</span>
        </template>

        <template #page-content>
            <form @submit.prevent="saveTask" autocomplete="off">
                <AppFormLayout>

                    <!-- Task Name -->
                    <BaseTextInput
                        v-model="taskForm.task_name"
                        label="Όνομα Task"
                        :errors="errors.task_name"
                        required
                    />

                    <!-- Description -->
                    <BaseTextareaInput
                        v-model="taskForm.description"
                        label="Περιγραφή Task"
                        :rows="5"
                        :errors="errors.description"
                    />

                    <!-- Volunteer Selection -->
                    <BaseDropdownInput
                        :options="calcVolunteerDropdownOptions"
                        v-model="taskForm.volunteer_id"
                        label="Εκχώρηση σε Εθελοντή"
                        placeholder="Επιλέξτε Εθελοντή"
                        :errors="errors.volunteer_id"
                        required
                    />

                    <!-- Task Status Selection -->
                    <BaseDropdownInput
                        :options="calcTaskStatusDropdownOptions"
                        v-model="taskForm.status_id"
                        label="Κατάσταση Task"
                        placeholder="Επιλέξτε Κατάσταση"
                        :errors="errors.status_id"
                        required
                    />

                    <!-- Estimated Time -->
                    <BaseTextInput
                        v-model="taskForm.estimated_time"
                        label="Εκτιμώμενος Χρόνος (λεπτά)"
                        type="number"
                        min="0"
                        :errors="errors.estimated_time"
                    />

                    <!-- Additional Information -->
                    <BaseInfoText type="info">
                        Συμπληρώστε τα απαραίτητα πεδία για τη δημιουργία ή την επεξεργασία του task.
                    </BaseInfoText>

                </AppFormLayout>

                <!-- Submit Button -->
                <Button
                    type="submit"
                    :label="submitButtonLabel"
                    raised
                    class="mt-4"
                />
            </form>
        </template>
    </AppPageWrapper>
</template>

<style scoped>
/* Add any component-specific styles here */
</style>
