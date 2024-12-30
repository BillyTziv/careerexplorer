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
// import BaseInfoText from '@/Components/Base/BaseInfoText.vue';
import BaseCalendarInput from '@/Components/Base/BaseCalendarInput.vue';
import BaseNumberInput from '@/Components/Base/BaseNumberInput.vue';

import { useToastNotification } from '@/Composables/useToastNotification';

const { notify } = useToastNotification();

/* Props Definition */
const props = defineProps({
    task: Object, // Existing task data for edit mode; empty for create mode
    taskStatusDropdownOptions: Array, // Array of task statuses for dropdown
    volunteerDropdownOptions: Array, // Array of volunteers for dropdown
    errors: Object, // Validation errors from backend
    response: Object // Any additional response data
});

/* Computed Properties */
const isEditMode = computed(() => taskForm.id !== null);
const submitButtonLabel = computed(() => isEditMode.value ? 'Ενημέρωση' : 'Δημιουργία');

const calcTaskPriorityDropdownOptions = computed(() => {
    return [
        { label: 'Χαμηλή', id: 0 },
        { label: 'Μεσαία', id: 1 },
        { label: 'Υψηλή', id: 2 },
    ];
});

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

/* Initialize Form */
const taskForm = useForm({
    id: props.task?.id || null,
    task_name: props.task?.task_name || '',
    description: props.task?.description || '',

    due_date: props.task?.due_date || '',
    estimated_time: props.task?.estimated_time || 0,
    points: props.task?.points || 0,

    priority: props.task?.priority || 0,
    volunteer_id: props.task?.volunteer_id || null,
    status_id: props.task?.status_id || (props.taskStatusDropdownOptions.length > 0 ? props.taskStatusDropdownOptions[0].id : null),
});


/* Submit Handler */
function saveTask() {
    if (isEditMode.value) {
        taskForm.put(`/tasks/${taskForm.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                notify('success', 'Επιτυχία', 'Η εργασία ενημερώθηκε επιτυχώς.');

                taskForm.reset()
            }
        });
    } else {
        taskForm.post('/tasks', {
            preserveScroll: true,
            onSuccess: () => {
                notify('success', 'Επιτυχία', 'Η εργασία δημιουργήθηκε επιτυχώς.');
                taskForm.reset()
            },
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
                        label="Τίτλος Εργασίας"
                        :errors="errors.task_name"
                        required
                    />

                    <!-- Description -->
                    <BaseTextareaInput
                        v-model="taskForm.description"
                        label="Περιγραφή Εργασίας"
                        :rows="10"
                        :errors="errors.description"
                    />

                    <!-- Volunteer Selection -->
                    <BaseDropdownInput
                        :options="calcVolunteerDropdownOptions"
                        v-model="taskForm.volunteer_id"
                        label="Ανάθεση σε εθελοντή"
                        placeholder="Επιλέξτε εθελοντή"
                        :errors="errors.volunteer_id"
                        required
                    />

                    <!-- Task Due Date -->
                    <BaseCalendarInput
                        v-model="taskForm.due_date"
                        label="Προθεσμία"
                        :errors="errors.due_date"
                        required
                    />

                    <!-- Task Priority Selection -->
                    <BaseDropdownInput
                        :options="calcTaskPriorityDropdownOptions"
                        v-model="taskForm.priority"
                        label="Προτεραιότητα"
                        placeholder="Επιλέξτε Προτεραιότητα"
                        :errors="errors.priority"
                        required
                    />

                    <!-- Task Status Selection -->
                    <BaseDropdownInput
                        :options="calcTaskStatusDropdownOptions"
                        v-model="taskForm.status_id"
                        label="Κατάσταση"
                        placeholder="Επιλέξτε Κατάσταση"
                        :errors="errors.status_id"
                        required
                    />

                    <!-- Estimated Time -->
                    <BaseNumberInput
                        v-model="taskForm.estimated_time"
                        label="Εκτιμώμενος Χρόνος (λεπτά)"
                        min="0"
                        :errors="errors.estimated_time"
                    />

                    <!-- Points -->
                    <BaseNumberInput
                        v-model="taskForm.points"
                        label="Δυσκολία (0-9)"
                        min="0"
                        max="9"
                        :errors="errors.points"
                    />

                    <!-- Additional Information -->
                    <!-- <BaseInfoText type="info">
                        Συμπληρώστε τα απαραίτητα πεδία για τη δημιουργία ή την επεξεργασία του task.
                    </BaseInfoText> -->

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
