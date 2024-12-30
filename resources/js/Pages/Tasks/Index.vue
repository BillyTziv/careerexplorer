<script setup>
/* Core */
import { ref, reactive, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

/* Layouts */
import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

/* Components */
import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
import BaseTableView from '@/Components/Base/Views/Table/BaseTableView.vue';
import BaseCalendarView from '@/Components/Base/Views/Calendar/BaseCalendarView.vue';
import BaseEisenhowerMatrixView from '@/Components/Base/Views/EisenhowerMatrix/BaseEisenhowerMatrixView.vue';

/* Store */
import { useTaskStore } from '@/Stores/useTask.store';

/* Composables */
import { useToastNotification } from '@/Composables/useToastNotification';
import BaseCalendarInput from '@/Components/Base/BaseCalendarInput.vue';

/* Data */
const taskStore = useTaskStore();
const { notify } = useToastNotification();

/* Props */
const props = defineProps({
    tasks: {
        type: Array,
        default: () => []
    },
    taskStatusDropdownOptions: {
        type: Array,
        default: () => []
    },
    priorityDropdownOptions: {
        type: Array,
        default: () => []
    },
    tagDropdownOptions: {
        type: Array,
        default: () => []
    },
    categoryDropdownOptions: {
        type: Array,
        default: () => []
    },
    volunteerDropdownOptions: {
        type: Array,
        default: () => []
    }
});

/* Reactive */
const tableOptions = reactive(taskStore.getTableOptions);
const filters = reactive(taskStore.getFilters);

const selectedTask = ref(null);
const deleteDialog = ref(false);

/* Watchers */
watch(() => taskStore.getFilters, () => {
    router.get('/tasks/', taskStore.getFilters, { preserveState: true, replace: true });
}, { deep: true });

const clearFilters = () => {
    taskStore.resetTableFilters();
};


// const confirmDeleteTask = (task) => {
//     selectedTask.value = task;
//     deleteDialog.value = true;
// };

const deleteTask = ( task_id) => {
    if( !task_id ) return;

    router.delete(`/tasks/${task_id}`, {
        preserveScroll: true,
        onSuccess: () => {
            deleteDialog.value = false;
            selectedTask.value = null;
            notify('success', 'Επιτυχία', 'Η εργασία διαγράφηκε με επιτυχία.');
        },
        onError: () => {
            deleteDialog.value = false;
            selectedTask.value = null;
            notify('error', 'Αποτυχία', 'Κάτι πήγε στραβα.');
        },
    });
};

onMounted(() => {
    taskStore.setDropdownOptions('volunteer', props.volunteerDropdownOptions);
    taskStore.setDropdownOptions('status', props.taskStatusDropdownOptions);
    taskStore.setDropdownOptions('tag', props.tagDropdownOptions);
    taskStore.setDropdownOptions('category', props.categoryDropdownOptions);
    taskStore.setDropdownOptions('priority', props.priorityDropdownOptions);

    router.reload({ only: ['tasks'] });
});

const selectedDataView = ref({ label: 'Πίνακας', viewType: 'table', icon: 'pi pi-table' });

const options = ref([
    { label: 'Πίνακας', viewType: 'table', icon: 'pi pi-table' },
    { label: 'Ημερολόγιο', viewType: 'calendar', icon: 'pi pi-calendar' },
    { label: 'Eishenhower', viewType: 'matrix', icon: 'pi pi-th-large' },
]);

const columns = ref([
    { field: 'task_name', header: 'Τίτλος' },
    { field: 'volunteer_fname', header: 'Εθελοντής' },
    { field: 'status', header: 'Κατάσταση' },
    { field: 'priority', header: 'Προτεραιότητα' },
    { field: 'due_date', header: 'Προθεσμία' }
]);

const handleTableAction = (action) => {
  if (action.type === 'edit') {
    router.visit(`/tasks/${action.data.task_id}/edit`);
  } else if (action.type === 'delete') {
    deleteTask( action.data.task_id);
  }else if (action.type === 'view') {
    router.visit(`/tasks/${action.data.task_id}`);
  }else if(action.type === 'create'){
    router.visit(`/tasks/create`);
  }
};

const handleCalendarAction = (action) => {
  if (action.type === 'view') {
    router.visit(`/tasks/${action.id}`);
  }
};
</script>

<template>
    <AppPageWrapper>
        <!-- Page Title -->
        <template #page-title>
            Λίστα Εργασιών
        </template>

        <!-- Page Content -->
        <template #page-content>
            <!-- Filter Section -->
            <div class="flex flex-column md:flex-row align-items-start md:align-items-start mb-3 w-full">
                <BaseDropdownInput
                    v-model="filters.volunteer"
                    placeholder="Όλοι οι εθελοντές"
                    :options="taskStore.getVolunteerDropdownOptions"
                    :narrow="true"
                    class="mx-1"
                />

                <BaseDropdownInput
                    v-model="filters.status"
                    placeholder="Όλες οι Καταστάσεις"
                    :options="taskStore.getStatusDropdownOptions"
                    :narrow="true"
                    class="mx-1"
                />

                <BaseDropdownInput
                    v-model="filters.priority"
                    placeholder="Όλες οι Προτεραιότητες"
                    :options="taskStore.getPriorityDropdownOptions"
                    :narrow="true"
                    class="mx-1"
                />

                <!-- <BaseDropdownInput
                    v-model="filters.tag"
                    placeholder="Όλες οι Ετικέτες"
                    :options="taskStore.getTagDropdownOptions"
                    :narrow="true"
                    class="mx-1"
                /> -->

                <Button
                    type="button"
                    icon="pi pi-filter-slash"
                    label="Καθαρισμός"
                    outlined
                    @click="clearFilters()"
                    class="mx-1"
                />
            </div>

            <SelectButton
                v-model="selectedDataView"
                :options="options"
                optionLabel="viewType"
                dataKey="viewType"
                aria-labelledby="custom"
                class="select-button"
                >
                <template #option="slotProps">
                    <!-- Render the icon dynamically based on the option selected -->
                    <i :class="slotProps.option.icon"></i>
                    <span class="ml-2">{{ slotProps.option.label }}</span>
                </template>
            </SelectButton>

            <!-- Table View -->
            <BaseTableView
                v-if="selectedDataView.viewType === 'table'"
                :data="tasks"
                :columns="columns"
                :filters="tableOptions"
                @action="handleTableAction"
            />

            <!-- Calendar View -->
            <BaseCalendarView
                v-if="selectedDataView.viewType === 'calendar'"
                :tasks="tasks"
                @action="handleCalendarAction"
            />

            <!-- Eisenhower Matrix View -->
            <BaseEisenhowerMatrixView
                v-if="selectedDataView.viewType === 'matrix'"
                :tasks="tasks"
            />
        </template>
    </AppPageWrapper>
</template>
