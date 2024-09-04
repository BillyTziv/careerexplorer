<script setup>
    /* Core */
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Emits Actions */
    import { useFormatBoolean } from '@/Composables/useFormatBoolean';
    const { formatBoolean } = useFormatBoolean();

    /* Component Properties */
    let props = defineProps({
        user: Object,
        volunteerStatuses: Object, 
        filters: Object,
        response: Object,
    });

    /* Component Reactive Variables */
    const selectedVolunteerStatus         = ref( null );              // Used for delete confirmation
    const deleteDialog                  = ref( false );             // Used for delete confirmation
    const volunteerStatusTableRef        = ref( null );              // Used for exportCSV
    const filterVolunteerStatusTable     = ref( props.filters );     // Used for filtering the table
    
    const exportStatusesAsCSV = () => {
        volunteerRoleTableRef.value.exportCSV();
    };

    const editEntity = ( volunteerStatus ) => {    
        router.visit(`/volunteer-statuses/${volunteerStatus.id}/edit`);
    };

    const confirmDelete = ( volunteerStatus ) => {
        selectedVolunteerStatus.value = volunteerStatus;
        deleteDialog.value = true;
    };

    const deleteEntity = () => {
        if( !selectedVolunteerStatus.value.id || selectedVolunteerStatus.value.id <= 0 ) return;

        router.delete(`/volunteer-statuses/${selectedVolunteerStatus.value.id}`);

        deleteDialog.value = false;
        selectedVolunteerStatus.value = null;
    };

    const redirectToCreate = () => {    
        router.visit(`/volunteer-statuses/create`);
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
            Καταστάσεις Εθελοντών
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <!-- <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="searchFilter" placeholder="Search" :style="{ borderRadius: '2rem' }" class="w-full" /> -->
                </IconField>

                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportStatusesAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

            <DataTable
            ref="volunteerStatusTableRef" 
            :value="volunteerStatuses" 
            dataKey="id" 
            paginator 
            :rows="5" 
            responsiveLayout="scroll" 
            v-model:filters="filterVolunteerStatusTable"
            :size="size.value"
            stripedRows
            :rowsPerPageOptions="[5, 10, 20, 50]"
            >
                <template #empty>Δεν βρέθηκαν καταστάσεις εθελοντών.</template>
                
                <Column field="name" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα</span>
                        {{ data.name }}
                    </template>
                </Column>

                <Column field="hex_color" header="Χρώμα">
                    <template #body="{ data }">
                        <span class="p-column-title">Χρωμα</span>
                        <span class="flex items-center ">
                            <Avatar class="mr-2" :style="{ 'background-color': '#'+data.hex_color, color: '#ffffff' }" shape="circle"></Avatar>
                        </span>
                    </template>
                </Column>

                <Column field="name" header="Περιγραφή" :headerStyle="{ minWidth: '12rem' }">
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

                <Column field="is_active" header="Ενεργή" :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Ενεργή</span>
                        {{ formatBoolean(data.is_active) }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editEntity(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDelete(slotProps.data)" />
                    </template>
                </Column>

                <template #paginatorstart>
                    <!-- <Dropdown v-model="size" :options="sizeOptions" optionLabel="label" dataKey="label"  class="w-full md:w-15rem" @change="onSortChange($event)" /> -->
                </template>

                <template #paginatorend>
                    Υπάρχουν {{ props.volunteerStatuses ? props.volunteerStatuses.length : 0 }} καταστάσεις εθελοντών.
                </template>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedVolunteerStatus">
                Είστε σίγουροι οτι θέλετε να διαγράψετε την εθελοντική κατάσταση <b>{{ selectedVolunteerStatus.name }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteEntity" />
        </template>
    </Dialog>
</template>