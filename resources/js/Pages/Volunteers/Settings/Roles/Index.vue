<script setup>
    /* Core */
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import { useVolunteerStatusMapper } from '@/Composables/useVolunteerStatusMapper';

    /* Emits Actions */


    /* Component Properties */
    let props = defineProps({
        user: Object,
        volunteerRoles: Object, 
        filters: Object,
        response: Object,
        volunteerStatusDropdownOptions: Array,
    });

    /* Component Reactive Variables */
    const selectedvolunteerRole        = ref( null );              // Used for delete confirmation
    const deleteDialog                 = ref( false );             // Used for delete confirmation
    const volunteerRoleTableRef        = ref( null );              // Used for exportCSV
    const filterVolunteerRoleTable     = ref( props.filters );     // Used for filtering the table
    const searchFilter                 = ref( '' );                // Used for searching the table

    /* Component Methods */

    // Export the volunteerRole table to CSV
    const exportCSV = () => {
        volunteerRoleTableRef.value.exportCSV();
    };
    const { getStatusName, getStatusDecoration, adjustOpacity, determineTextColor  } = useVolunteerStatusMapper( props.volunteerStatusDropdownOptions );

    const exportRolesDatatableAsCSV = () => {
        volunteerRoleTableRef.value.exportCSV();
    };

    // Navigate to the edit page of a course
    const editEntity = ( volunteerRole ) => {    
        router.visit(`/volunteer-roles/${volunteerRole.id}/edit`);
    };

    const confirmDelete = ( volunteerRole ) => {
        selectedvolunteerRole.value = volunteerRole;
        deleteDialog.value = true;
    };

    const deleteEntity = () => {
        if( !selectedvolunteerRole.value.id || selectedvolunteerRole.value.id <= 0 ) return;

        router.delete(`/volunteer-roles/${selectedvolunteerRole.value.id}`);

        deleteDialog.value = false;
        selectedvolunteerRole.value = null;
    };

    const redirectToCreate = () => {    
        router.visit(`/volunteer-roles/create`);
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Ρόλοι Εθελοντών
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <!-- <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="searchFilter" placeholder="Αναζήτηση.." :style="{ borderRadius: '2rem' }" class="w-full" /> -->
                </IconField>

                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportRolesDatatableAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

            <DataTable 
                ref="volunteerRoleTableRef" 
                :value="volunteerRoles.data" 
                dataKey="id" 
                paginator 
                :rows="10" 
                responsiveLayout="scroll" 
                v-model:filters="filterVolunteerRoleTable"
            >
                <template #empty>Δεν βρέθηκαν ρόλοι.</template>
                
                <Column field="name" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα Ρόλου</span>
                        {{ data.name }}
                    </template>
                </Column>

                <!-- <Column field="coverage_percentage" header="% Κάλυψης" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">% Κάλυψης</span>
                        {{ data.coverage_percentage }}
                    </template>
                </Column> -->

                <Column field="volunteer_count" header="Πλήθος Εθελοντών" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Πλήθος Εθελοντών</span>
                        {{ data.volunteer_count }}
                    </template>
                </Column>

                <!-- <Column field="volunteers_needed" header="Ανάγκη Για" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Ανάγκη Για</span>
                        {{ data.volunteers_needed }}
                    </template>
                </Column> -->

                <Column field="description" header="Περιγραφή" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Περιγραφή</span>
                        {{ data.description }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editEntity(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDelete(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedvolunteerRole">
                Είστε σίγουροι οτι θέλετε να διαγράψετε τον εθελοντικό ρόλο <b>{{ selectedvolunteerRole.name }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteEntity" />
        </template>
    </Dialog>
</template>