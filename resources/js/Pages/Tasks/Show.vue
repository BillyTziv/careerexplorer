<script setup>
    /* Core */
    import { ref, computed, watch } from 'vue';
    import { useForm, router } from '@inertiajs/vue3';
    import moment from 'moment';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Composables */
    import { useFormatBoolean } from '@/Composables/useFormatBoolean';
    import { useFormatDate } from '@/Composables/useFormatDate';
    import { useToastNotification } from '@/Composables/useToastNotification';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';

    import ConfettiExplosion from "vue-confetti-explosion";

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

    const completeTaskExplosion = ref(false);

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

    const onOpenAddTimeLogModal = () => {
        taskLogForm.reset();
        addTaskLogDialog.value = true;
    };

    const submitTaskLog = () => {
        // Convert totalTime (hours) into start_date, end_date_and duration
        taskLogForm.duration = totalMin.value * 60; // Convert hours to minutes
        taskLogForm.start_time = moment(new Date()).format('YYYY-MM-DD');
        taskLogForm.end_time = moment(new Date()).format('YYYY-MM-DD');

        // Calculate duration if end_time is provided
        // if (taskLogForm.end_time) {
        //     const start = new Date(taskLogForm.start_time);
        //     const end = new Date(taskLogForm.end_time);
        //     const duration = Math.floor((end - start) / 60000);

        //     if (duration < 0) {
        //         notify('error', 'Σφάλμα', 'Η ώρα λήξης πρέπει να είναι μετά την ώρα έναρξης.');
        //         return;
        //     }

        //     taskLogForm.duration = duration;
        // } else {
        //     taskLogForm.duration = 0;
        // }

        taskLogForm.post('/task-logs', {
            preserveScroll: true,
            onSuccess: () => {
                notify('success', 'Ολοκληρώθηκε', 'Τέλεια! Ευχαριστούμε πολύ για την συνεισφορά σου.');
                addTaskLogDialog.value = false;
                router.reload();
            },
            onError: () => {
                notify('error', 'Σφάλμα', 'Δεν ήταν δυνατή η δημιουργία του task log.');
            },
        });
    };

    const taskStatus = ref(props.task.status_id);

    watch(() => taskStatus.value, (newValue) => {
        console.log( newValue )
        handleTaskStatusChange();
    });

    function formatPriority( priority ) {
        switch( priority ) {
            case 0:
                return 'Χαμηλή';
            case 1:
                return 'Μεσαία';
            case 2:
                return 'Υψηλή';
            default:
                return '---';
        }
    }

    function handleTaskStatusChange( event ) {
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

    function convertToReadableTime(minutes) {
        const days = Math.floor(minutes / 1440);            // 1 day = 1440 minutes
        const hours = Math.floor((minutes % 1440) / 60);    // 1 hour = 60 minutes
        const mins = minutes % 60;                          // Remaining minutes

        let result = '';
        if (days > 0) result += `${days} ημέρες `;
        if (hours > 0) result += `${hours} ώρες `;
        if (mins > 0) result += `${mins} λεπτά`;

        return result.trim();
    }

    const taskDetails = [
        {
        label: 'Εθελοντής',
        value: `${props.task.volunteer.firstname} ${props.task.volunteer.lastname}`
        },
        {
        label: 'Προτεραιότητα',
        value: formatPriority(props.task.priority)
        },
        {
        label: 'Κατάσταση',
        value: props.task.status ? props.task.status.name : '---'
        },
        {
        label: 'Εκτιμώμενος Χρόνος',
        value: props.task.estimated_time ? `${convertToReadableTime(props.task.estimated_time)}` : '---'
        },
        {
        label: 'Συνολικός Χρόνος',
        value: props.task.actual_time ? `${convertToReadableTime(props.task.actual_time)}` : '---'
        },
        {
        label: 'Ημ/νια Ολοκλήρωσης',
        value: formatDate(props.task.due_date, true)
        },
        {
        label: 'Ημ/νια Δημιουργίας',
        value: formatDate(props.task.created_at, true)
        }
    ];

    const getTagLabel = (tag) => {
        let status = props.taskStatusDropdownOptions.find(option => option.id === tag);
        if( status ) {
            return status.name;
        }else {
            return '---';
        }
    };

    const getTagStyle = (tag) => {

        let status = props.taskStatusDropdownOptions.find(option => option.id === tag);
        if( status ) {
            return {
                backgroundColor: status.hex_color,
                borderRadius: '0.80rem',
                color: 'white'
            };
        }else {
            return {
                backgroundColor: 'transparent',
                borderRadius: '0.80rem',
                color: 'black'
            };
        }
    };

    const totalMin = ref(0);

    function onCompleteTask() {
        // Check if time log has been added
        if( props.task.task_logs.length === 0 ) {
            notify('error', 'Σφάλμα', 'Πρέπει να προσθέσεις χρονική καταγραφή πριν ολοκληρϋσεις την εργασία.');
            return;
        }

        router.put(`/tasks/${props.task.id}/complete`, null, {
            preserveState: true,
            replace: true,
            onSuccess: () => {
                completeTaskExplosion.value = true;

                let changeStatusMsg = 'Τέλεια, η εργασία ' + props.task.task_name + ' ολοκληρώθηκε επιτυχώς!';
                notify('success', 'Μπράβο!', changeStatusMsg);
            }
        });
    }

    const hasCompleted = props.task.status_id !== 3;
</script>

<template>
    <AppPageWrapper>
        <!-- Page Title -->
        <template #page-title>
            {{ task.task_name }}
        </template>

        <template #page-actions>
            <template v-if="hasCompleted">
                <Button
                    @click="onOpenAddTimeLogModal()"
                    label="Προσθήκη Χρόνου"
                    icon="pi pi-plus"
                    outlined
                    class="mx-1"
                />

                <Button
                    @click="onCompleteTask()"
                    label="Ολοκλήρωση"
                    icon="pi pi-check"
                    class="mx-1"
                />
            </template>
              <!-- <div class="absolute bottom-0 left-0 border-round font-bold w-full">
                <ProgressBar
                    :value="calculateProgress"
                >

                </ProgressBar>
            </div> -->


        </template>

        <!-- Page Content -->
        <template #page-content>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <ConfettiExplosion
                    v-if="completeTaskExplosion"
                    :particleCount="300"
                    :stageHeight="600"
                    :stageWidth="1100"
                    :particleSize="20"
                    :force="1.2"
                    />
            </div>
            <!-- Task Description -->
            <div class="font-medium text-md text-surface-900 dark:text-surface-0 mb-4">
                    {{ task.description }}
            </div>

            <!-- Task Status-->
            <!-- <BaseDropdownInput
                v-model="taskStatus"
                label="Κατάσταση Εργασίας"
                :options="calcTaskStatusDropdownOptions"
                @change="handleTaskStatusChange(event)"
            /> -->
            <div class="grid">
                <div class="col-fixed" style="width: 250px">
                    <div class="text-left text-lg p-2 font-bold">
                        Κατάσταση Εργασίας
                    </div>
                </div>
                <div class="col">
                    <div
                        class="text-left text-lg p-1"
                    >
                        <span class="flex items-center">
                            <Tag
                                shape="circle"
                                :style="getTagStyle(props.task.status_id)"
                                class="px-3 text-lg"
                            >{{ getTagLabel(props.task.status_id) }}</Tag>

                        </span>
                    </div>
                </div>
            </div>

            <!-- Task Details -->
            <template v-for="(field, index) in taskDetails" :key="index">
                <div class="grid">
                    <div class="col-fixed" style="width: 250px">
                        <div class="text-left text-lg p-2 font-bold">
                            {{ field.label }}
                        </div>
                    </div>
                    <div class="col">
                        <div
                            class="text-left text-lg p-2"
                            :style="{
                            backgroundColor: index % 2 === 0 ? 'transparent' : '#f5f5f5'
                            }"
                        >
                            {{ field.value }}
                        </div>
                    </div>
                </div>
            </template>


            <h3 class="text-xl font-normal">Ανάλυση Χρονικών Καταγραφών</h3>
            <template
                v-if="true"
            >
                <DataTable
                    :value="task.task_logs"
                    dataKey="id"
                    responsiveLayout="scroll"
                    stripedRows
                >
                    <template #empty>
                        Δεν βρέθηκαν χρονκή συνεισφορά.
                    </template>

                    <!-- Start Time Column -->
                    <Column field="start_time" header="Έναρξη Εργασίας" sortable>
                        <template #body="{ data }">
                            <span class="p-column-title">Έναρξη Εργασίας</span>
                            {{ formatDate(data.start_time, true) }}
                        </template>
                    </Column>

                    <!-- End Time Column -->
                    <Column field="end_time" header="Λήξη Εργασίας" sortable>
                        <template #body="{ data }">
                            <span class="p-column-title">Λήξη Εργασίας</span>
                            {{ data.end_time ? formatDate(data.end_time, true) : '---' }}
                        </template>
                    </Column>

                    <!-- Duration Column -->
                    <Column field="duration" header="Διάρκεια" sortable>
                        <template #body="{ data }">
                            <span class="p-column-title">Διάρκεια</span>
                            {{ convertToReadableTime(data.duration) }}
                        </template>
                    </Column>
                </DataTable>
            </template>
        </template>
    </AppPageWrapper>

    <!-- Add Task Log Modal -->
    <Dialog
        v-model:visible="addTaskLogDialog"
        :style="{ width: '500px' }"
        header="Πόσα λεπτά έχεις αφιερώσεις σε αυτή την εργασία;"
        :modal="true"
    >
        <form @submit.prevent="submitTaskLog" autocomplete="off">
            <InputText v-model.number="totalMin" class="mb-5"/>
            <span class="text-lg ml-2">λεπτά</span>

            <Slider v-model="totalMin" :max="60"/>

            <div class=" mt-4">
                <!-- <Button
                    label="Ακύρωση"
                    icon="pi pi-times"
                    text
                    @click="addTaskLogDialog = false"
                    class="mr-2"
                /> -->

                <Button
                    label="Προσθήκη Συνεισφοράς"
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
