<script setup>
    /* Core */
    import { ref, computed, reactive, provide, watch, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Components */
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';

    /* Composables */
    import { useFormatBoolean } from '@/Composables/useFormatBoolean';
    import { useFormatDate } from '@/Composables/useFormatDate';

    const { formatBoolean } = useFormatBoolean();
    const { formatDate } = useFormatDate( );

    /* Component Properties */
    const props = defineProps({
        tasks: {
            type: Array,
            default: () => []
        },
        taskStatusDropdownOptions: {
            type: Array,
            default: () => []
        },
        volunteerDropdownOptions: {
            type: Array,
            default: () => []
        },
        filters: {
            type: Object,
            default: () => ({})
        },
        response: {
            type: Object,
            default: () => ({})
        },
    });

    /* Reactive Variables */
    const selectedTask = ref(null); // Selected task for deletion
    const deleteDialog = ref(false); // Visibility of delete confirmation dialog
    const taskTableRef = ref(null); // Reference to DataTable for export
    const filterTaskTable = ref({ ...props.filters }); // Local copy of filters

    /**
     * Export tasks as CSV
     */
    const exportTasksAsCSV = () => {
        if (taskTableRef.value) {
            taskTableRef.value.exportCSV();
        }
    };

    /**
     * Navigate to edit task page
     * @param {Object} task - Task object to edit
     */
    const editTask = (task) => {
        router.visit(`/tasks/${task.id}/edit`);
    };

    const viewDetails = ( task ) => {
        router.visit(`/tasks/${task.id}`);
    };

    /**
     * Confirm deletion of a task
     * @param {Object} task - Task object to delete
     */
    const confirmDeleteTask = (task) => {
        selectedTask.value = task;
        deleteDialog.value = true;
    };

    /**
     * Delete the selected task
     */
    const deleteTask = () => {
        if (!selectedTask.value || !selectedTask.value.id) return;

        router.delete(`/tasks/${selectedTask.value.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                deleteDialog.value = false;
                selectedTask.value = null;
            },
            onError: () => {
                deleteDialog.value = false;
                selectedTask.value = null;
            },
        });
    };

    /**
     * Navigate to create task page
     */
    const redirectToCreate = () => {
        router.visit(`/tasks/create`);
    };

    /**
     * Watch for changes in filters and update the table accordingly
     */
    watch(() => props.filters, (newFilters) => {
        filterTaskTable.value = { ...newFilters };
    }, { deep: true });

    /* Hook: On Mounted */
    onMounted(() => {
        // Any initialization logic if necessary
    });

    /* Computed Properties */
    const currentPageReportTemplate = computed(() => {
        return `Προβολή ${props.tasks.length} εργασιών.`;
    });

    /* Datatable Size Change */
    const size = ref({ label: 'Small', value: 'small' });

    const sizeOptions = ref([
        { label: 'Small', value: 'small' },
        { label: 'Normal', value: 'null' },
        { label: 'Large', value: 'large' }
    ]);
</script>

<template>
    <AppPageWrapper>
        <!-- Page Title -->
        <template #page-title>
            Λίστα Εργασιών
        </template>

        <!-- Page Content -->
        <template #page-content>
            <!-- Action Buttons -->
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <!-- Optional Icon -->
                </IconField>

                <div class="flex">
                    <Button
                        type="button"
                        icon="pi pi-download"
                        rounded
                        tooltip="Export Data"
                        text
                        @click="exportTasksAsCSV"
                        class="mr-2"
                    />
                    <Button
                        type="button"
                        icon="pi pi-plus"
                        rounded
                        tooltip="Δημιουργία Νέας Εργασίας"
                        @click="redirectToCreate"
                    />
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-column md:flex-row align-items-start mb-3 w-full">
                <!-- Search Input -->
                <IconField iconPosition="left" class="mx-1">
                    <InputIcon class="pi pi-search" />
                    <InputText
                        type="text"
                        v-model="filterTaskTable.search"
                        placeholder="Αναζήτηση με όνομα, περιγραφή..."
                        class="w-full"
                        @input="router.get('/tasks', filterTaskTable, { preserveState: true, replace: true })"
                    />
                </IconField>

                <!-- Task Status Dropdown -->
                <BaseDropdownInput
                    v-model="filterTaskTable.status_id"
                    placeholder="Όλες οι Καταστάσεις"
                    :options="taskStatusDropdownOptions"
                    class="mx-1"
                    @change="router.get('/tasks', filterTaskTable, { preserveState: true, replace: true })"
                />

                <!-- Volunteer Dropdown -->
                <BaseDropdownInput
                    v-model="filterTaskTable.volunteer_id"
                    placeholder="Όλοι οι Εθελοντές"
                    :options="volunteerDropdownOptions"
                    class="mx-1"
                    @change="router.get('/tasks', filterTaskTable, { preserveState: true, replace: true })"
                />

                <!-- Clear Filters Button -->
                <Button
                    type="button"
                    icon="pi pi-filter-slash"
                    label="Καθαρισμός Φίλτρων"
                    outlined
                    @click="() => { filterTaskTable.value = {}; router.get('/tasks', filterTaskTable, { preserveState: true, replace: true }) }"
                    class="mx-1"
                />
            </div>

            <!-- Task DataTable -->
            <DataTable
                ref="taskTableRef"
                :value="tasks"
                dataKey="id"
                paginator
                :rows="10"
                responsiveLayout="scroll"
                v-model:filters="filterTaskTable"
                :size="size.value"
                stripedRows
                :rowsPerPageOptions="[5, 10, 20, 50]"
            >
                <template #empty>
                    Δεν βρέθηκαν εργασίες.
                </template>

                <!-- <Column selectionMode="multiple" headerStyle="width: 3rem"></Column> -->

                <Column headerStyle="width: 5rem">
                    <template #header>

                    </template>
                    <template #body="slotProps">
                        <Button icon="pi pi-search" rounded text @click="viewDetails(slotProps.data)" />
                    </template>
                </Column>


                <!-- Task Name Column -->
                <Column field="task_name" header="Όνομα Εργασίας" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα Εργασίας</span>
                        {{ data.task_name }}
                    </template>
                </Column>

                <!-- Volunteer Column -->
                <Column field="volunteer.name" header="Εθελοντής" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Εθελοντής</span>
                        {{ data.volunteer.name }}
                    </template>
                </Column>

                <!-- Status Column -->
                <Column field="status.name" header="Κατάσταση" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Κατάσταση</span>
                        <span class="flex items-center">
                            <Avatar
                                class="mr-2"
                                :style="{ 'background-color': data.status.hex_color, color: '#ffffff' }"
                                shape="circle"
                            ></Avatar>
                            {{ data.status.name }}
                        </span>
                    </template>
                </Column>

                <!-- Estimated Time Column -->
                <Column field="estimated_time" header="Εκτ. Χρόνος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Εκτ. Χρόνος</span>
                        {{ data.estimated_time ? data.estimated_time + ' λεπτά' : '---' }}
                    </template>
                </Column>

                <!-- Actual Time Column -->
                <Column field="actual_time" header="Συν. Χρόνος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Συν. Χρόνος</span>
                        {{ data.actual_time ? data.actual_time + ' λεπτά' : '---' }}
                    </template>
                </Column>

                <!-- Created At Column -->
                <Column field="created_at" header="Ημ/νια Δημιουργίας" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Ημ/νια Δημιουργίας</span>
                        {{ formatDate(data.created_at) }}
                    </template>
                </Column>

                <!-- Actions Column -->
                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button
                            icon="pi pi-pencil"
                            class="mr-2"
                            rounded
                            outlined
                            tooltip="Επεξεργασία"
                            @click="editTask(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            class="mt-2"
                            rounded
                            outlined
                            severity="danger"
                            tooltip="Διαγραφή"
                            @click="confirmDeleteTask(slotProps.data)"
                        />
                    </template>
                </Column>

                <!-- Paginator End Template -->
                <template #paginatorend>
                    Υπάρχουν {{ tasks.length }} εργασίες.
                </template>
            </DataTable>

            <!-- Delete Confirmation Dialog -->
            <!-- <Dialog
                v-model:visible="deleteDialog"
                :style="{ width: '450px' }"
                header="Επιβεβαίωση Διαγραφής"
                :modal="true"
            >
                <div class="flex align-items-center justify-content-center">
                    <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                    <span v-if="selectedTask">
                        Είστε σίγουροι ότι θέλετε να διαγράψετε την εργασία <b>{{ selectedTask.task_name }}</b>;
                    </span>
                </div>
                <template #footer>
                    <Button label="Όχι" icon="pi pi-times" text @click="deleteDialog = false" />
                    <Button label="Ναι" icon="pi pi-check" text severity="danger" @click="deleteTask" />
                </template>
            </Dialog> -->
        </template>
    </AppPageWrapper>
</template>

<style scoped lang="scss">
    /* Add any component-specific styles here */
    :deep(.p-datatable-frozen-tbody) {
        font-weight: bold;
    }

    :deep(.p-datatable-scrollable .p-frozen-column) {
        font-weight: bold;
    }
</style>
