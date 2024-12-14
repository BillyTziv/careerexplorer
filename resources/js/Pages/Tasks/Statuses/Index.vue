<script setup>
    /* Core */
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Emits Actions */
    import { useFormatBoolean } from '@/Composables/useFormatBoolean';
    const { formatBoolean } = useFormatBoolean();

    /* Component Properties */
    let props = defineProps({
        user: Object,
        taskStatuses: Object,
        filters: Object,
        response: Object,
    });

    /* Component Reactive Variables */
    const selectedTaskStatus          = ref( null );              // Used for delete confirmation
    const deleteDialog                = ref( false );             // Used for delete confirmation
    const taskStatusTableRef          = ref( null );              // Used for exportCSV
    const filterTaskStatusTable       = ref( props.filters );     // Used for filtering the table

    const exportStatusesAsCSV = () => {
        taskStatusTableRef.value.exportCSV();
    };

    const editEntity = ( taskStatus ) => {
        router.visit(`/task-statuses/${taskStatus.id}/edit`);
    };

    const confirmDelete = ( taskStatus ) => {
        selectedTaskStatus.value = taskStatus;
        deleteDialog.value = true;
    };

    const deleteEntity = () => {
        if( !selectedTaskStatus.value.id || selectedTaskStatus.value.id <= 0 ) return;

        router.delete(`/task-statuses/${selectedTaskStatus.value.id}`);

        deleteDialog.value = false;
        selectedTaskStatus.value = null;
    };

    const redirectToCreate = () => {
        router.visit(`/task-statuses/create`);
    };

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
        <template #page-title>
            Καταστάσεις Εργασιών
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                </IconField>

                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportStatusesAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

            <DataTable
                ref="taskStatusTableRef"
                :value="taskStatuses"
                dataKey="id"
                paginator
                :rows="10"
                responsiveLayout="scroll"
                v-model:filters="filterTaskStatusTable"
                :size="size.value"
                stripedRows
                :rowsPerPageOptions="[5, 10, 20, 50]"
            >
                <template #empty>Δεν βρέθηκαν καταστάσεις εργασιών.</template>

                <Column field="name" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα</span>
                        {{ data.name }}
                    </template>
                </Column>

                <Column field="hex_color" header="Χρώμα">
                    <template #body="{ data }">
                        <span class="p-column-title">Χρώμα</span>
                        <span class="flex items-center ">
                            <Avatar class="mr-2" :style="{ 'background-color': data.hex_color, color: '#ffffff' }" shape="circle"></Avatar>
                        </span>
                    </template>
                </Column>

                <Column field="description" header="Περιγραφή" :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Περιγραφή</span>
                        {{ data.description }}
                    </template>
                </Column>

                <Column field="is_default" header="Προεπιλογή" :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Προεπιλογή</span>
                        {{ formatBoolean(data.is_default) }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editEntity(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDelete(slotProps.data)" />
                    </template>
                </Column>

                <template #paginatorend>
                    Υπάρχουν {{ props.taskStatuses ? props.taskStatuses.length : 0 }} καταστάσεις εργασιών.
                </template>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedTaskStatus">
                Είστε σίγουροι οτι θέλετε να διαγράψετε την κατάσταση εργασίας <b>{{ selectedTaskStatus.name }}</b>?
            </span>
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteEntity" />
        </template>
    </Dialog>
</template>
