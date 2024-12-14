<script setup>
    /* Core */
    import { ref, computed, watch } from 'vue';
    import { useForm, router } from '@inertiajs/vue3';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Composables */
    import { useFormatBoolean } from '@/Composables/useFormatBoolean';
    import { useFormatDate } from '@/Composables/useFormatDate';
    import { useToastNotification } from '@/Composables/useToastNotification';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';

    const { formatBoolean } = useFormatBoolean();
    const { formatDate } = useFormatDate();
    const { notify } = useToastNotification();

    /* Component Properties */
    const props = defineProps({
        task: {
            type: Object,
            required: true
        },
        errors: {
            type: Object,
            default: () => ({})
        },
        response: {
            type: Object,
            default: () => ({})
        },
        taskStatusDropdownOptions: {
            type: Array,
            required: true
        }
    });

    const calcTaskStatusDropdownOptions = computed(() => {
        console.log( props.taskStatusDropdownOptions )
        if( !props.taskStatusDropdownOptions ) {
            return [];
        }
        return props.taskStatusDropdownOptions.map(option => ({
            label: option.name,
            id: option.id
        }));
    });

    /* Reactive Variables */
    const selectedTaskLog = ref(null); // Selected task log for deletion
    const deleteTaskLogDialog = ref(false); // Visibility of delete confirmation dialog

    // ----------------------------
    // ADD TASK LOG MODAL VARIABLES
    // ----------------------------
    const addTaskLogDialog = ref(false); // Visibility of add task log dialog

    // Initialize the form for adding a new task log
    const taskLogForm = useForm({
        task_id: props.task.id,
        start_time: '',
        end_time: '',
        duration: 0
    });

    /**
     * Open Add Task Log Modal
     */
    const openAddTaskLogModal = () => {
        // Reset the form
        taskLogForm.reset();
        addTaskLogDialog.value = true;
    };

    /**
     * Submit the Task Log Form
     */
    const submitTaskLog = () => {
        // Calculate duration if end_time is provided
        if (taskLogForm.end_time) {
            const start = new Date(taskLogForm.start_time);
            const end = new Date(taskLogForm.end_time);
            const duration = Math.floor((end - start) / 60000); // Duration in minutes

            if (duration < 0) {
                notify('error', 'Σφάλμα', 'Η ώρα λήξης πρέπει να είναι μετά την ώρα έναρξης.');
                return;
            }

            taskLogForm.duration = duration;
        } else {
            taskLogForm.duration = 0;
        }

        taskLogForm.post('/task-logs', {
            preserveScroll: true,
            onSuccess: () => {
                notify('success', 'Ολοκληρώθηκε', 'Το task log δημιουργήθηκε επιτυχώς.');
                addTaskLogDialog.value = false;
                // Optionally, you can emit an event or refresh the page to show the new log
                router.reload();
            },
            onError: () => {
                notify('error', 'Σφάλμα', 'Δεν ήταν δυνατή η δημιουργία του task log.');
            },
        });
    };

    const taskStatus = ref(null);

    watch(() => taskStatus.value, (newValue) => {
        console.log( newValue )
        handleTaskStatusChange();
    });

    function handleTaskStatusChange( event ) {
        console.log( "asdasdasd")
        router.put('/tasks/' + props.task.id + '/status', {
            newStatusValue: taskStatus.value,
        }, {
            preserveState: true,
            replace: true,
            onSuccess: () => {
                // Popup a notification
                let changeStatusMsg = 'Η κατάσταση της εργασίας ' + props.task.task_name + ' άλλαξε επιτυχώς.';
                notify('success', 'Ολοκληρώθηκε', changeStatusMsg);

                // Close the modal
                // showTaskStatusChangeModal.value = false;
            }
        });

        // router.put('/tasks/' + props.volunteer.id + '/status', {
        //     newStatusValue: selectedVolunteerStatus.value,
        //     statusChangeReason: form.reason,
        //     sendEmail: form.sendEmail
        // }, {
        //     preserveState: true,
        //     replace: true,
        //     onSuccess: () => {
        //         // Popup a notification
        //         let changeStatusMsg = 'Η κατάσταση του εθελοντή ' + props.volunteer.firstname + ' ' + props.volunteer.lastname + ' άλλαξε επιτυχώς.';
        //         notify('success', 'Ολοκληρώθηκε', changeStatusMsg);

        //         // Close the modal
        //         showVolunteerStatusChangeModal.value = false;
        //     }
        // });
    }

</script>

<template>
    <AppPageWrapper>
        <!-- Page Title -->
        <template #page-title>
            {{ task.task_name }}
        </template>

        <!-- Page Content -->
        <template #page-content>
            <BaseDropdownInput
                v-model="taskStatus"
                label="Κατάσταση Εργασίας"
                :options="calcTaskStatusDropdownOptions"
                @change="handleTaskStatusChange(event)"
            />

            <!-- Task Details -->
                    <!-- Task Information -->
                    <div class="flex-1">
                        <p class="text-gray-700 mb-2">
                            <strong>Κατάσταση:</strong>
                            {{ task.status.name ? task.status.name : '---' }}
                        </p>
                        <p class="text-gray-700 mb-2">
                            <strong>Εθελοντής:</strong> {{ task.volunteer.firstname }} {{ task.volunteer.lastname }}
                        </p>
                        <p class="text-gray-700 mb-2">
                            <strong>Εκτιμώμενος Χρόνος:</strong>
                            {{ task.estimated_time ? task.estimated_time + ' λεπτά' : '---' }}
                        </p>
                        <p class="text-gray-700 mb-2">
                            <strong>Συνολικός Χρόνος:</strong>
                            {{ task.actual_time ? task.actual_time + ' λεπτά' : '---' }}
                        </p>

                        <br />
                        <p class="text-gray-700 mb-2">
                            <strong>Περιγραφή:</strong> {{ task.description || '---' }}
                        </p>

                        <br />

                        <p class="text-gray-700 mb-2">
                            <strong>Ημ/νια Δημιουργίας:</strong> {{ formatDate(task.created_at) }}
                        </p>
                        <p class="text-gray-700 mb-2">
                            <strong>Ημ/νια Ενημέρωσης:</strong> {{ formatDate(task.updated_at) }}
                        </p>
                        <p class="text-gray-700 mb-2">
                            <strong>Προεπιλεγμένη Κατάσταση:</strong> {{ formatBoolean(task.status.is_default) }}
                        </p>
                    </div>

            <br /><br />

            <!-- <div class="absolute bottom-0 left-0 border-round font-bold w-full">
                <ProgressBar
                    :value="calculateProgress"
                >

                </ProgressBar>
            </div> -->



            <!-- Task Logs -->
            <div class="">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Χρονική Καταγραφή</h3>
                </div>

                <Button
                    label="Προσθήκη"
                    icon="pi pi-plus"
                    rounded
                    outlined
                    @click="openAddTaskLogModal"
                />

                <DataTable
                    :value="task.task_logs"
                    dataKey="id"
                    paginator
                    :rows="5"
                    responsiveLayout="scroll"
                    stripedRows
                    :rowsPerPageOptions="[5, 10, 20]"
                >
                    <template #empty>
                        Δεν βρέθηκαν task logs.
                    </template>

                    <!-- Start Time Column -->
                    <Column field="start_time" header="Έναρξη" sortable>
                        <template #body="{ data }">
                            <span class="p-column-title">Έναρξη</span>
                            {{ formatDate(data.start_time) }}
                        </template>
                    </Column>

                    <!-- End Time Column -->
                    <Column field="end_time" header="Λήξη" sortable>
                        <template #body="{ data }">
                            <span class="p-column-title">Λήξη</span>
                            {{ data.end_time ? formatDate(data.end_time) : '---' }}
                        </template>
                    </Column>

                    <!-- Duration Column -->
                    <Column field="duration" header="Διάρκεια (λεπτά)" sortable>
                        <template #body="{ data }">
                            <span class="p-column-title">Διάρκεια</span>
                            {{ data.duration ? data.duration + ' λεπτά' : '---' }}
                        </template>
                    </Column>


                    <!-- Actions Column -->
                    <!-- <Column header="Ενέργειες">
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-trash"
                                class="mt-2"
                                rounded
                                outlined
                                severity="danger"
                                tooltip="Διαγραφή Task Log"
                                @click="confirmDeleteTaskLog(slotProps.data)"
                            />
                        </template>
                    </Column> -->
                </DataTable>
            </div>
        </template>
    </AppPageWrapper>

    <!-- Add Task Log Modal -->
    <Dialog
        v-model:visible="addTaskLogDialog"
        :style="{ width: '500px' }"
        header="Δημιουργία Task Log"
        :modal="true"
    >
        <form @submit.prevent="submitTaskLog" autocomplete="off">
            <div class="flex flex-col space-y-4">
                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Ώρα Έναρξης</label>
                    <InputText
                        id="start_time"
                        type="datetime-local"
                        v-model="taskLogForm.start_time"
                        class="mt-1 block w-full"
                        :class="{ 'border-red-500': errors.start_time }"
                        required
                    />
                    <span v-if="errors.start_time" class="text-red-500 text-sm">{{ errors.start_time }}</span>
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700">Ώρα Λήξης</label>
                    <InputText
                        id="end_time"
                        type="datetime-local"
                        v-model="taskLogForm.end_time"
                        class="mt-1 block w-full"
                        :class="{ 'border-red-500': errors.end_time }"
                    />
                    <span v-if="errors.end_time" class="text-red-500 text-sm">{{ errors.end_time }}</span>
                </div>

                <!-- Duration (Read-Only) -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700">Διάρκεια (λεπτά)</label>
                    <InputText
                        id="duration"
                        type="number"
                        v-model="taskLogForm.duration"
                        class="mt-1 block w-full"
                        readonly
                    />
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <Button
                    label="Ακύρωση"
                    icon="pi pi-times"
                    text
                    @click="addTaskLogDialog = false"
                    class="mr-2"
                />
                <Button
                    label="Δημιουργία"
                    icon="pi pi-check"
                    type="submit"
                />
            </div>
        </form>
    </Dialog>
</template>


<style scoped>
    .p-progressbar {
        background: #323232;
    }

    .p-progressbar >>> .p-progressbar-label	 {
        color: white;
    }

    .p-progressbar >>> .p-progressbar-value {
        background: linear-gradient(to bottom right, #f97316, #ec4899, #a855f7) !important;
    }
</style>
