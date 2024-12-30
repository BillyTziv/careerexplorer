<script setup>
    /* Core */
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Component Properties */
    let props = defineProps({
        teams: Object,
        filters: Object,
        response: Object,
    });

    /* Component Reactive Variables */
    //const selectedTeam         = ref( null );              // Used for delete confirmation
    //const deleteTeamDialog     = ref( false );             // Used for delete confirmation
    //const TeamsTableRef        = ref( null );              // Used for exportCSV
    const filterTeamsTable     = ref( props.filters );     // Used for filtering the table

    // const redirectToCreate = () => {
    //     router.visit(`/career-Teams/create`);
    // };


    // Export the Team table to CSV
    // const exportCareerTeamsAsCSV = () => {
    //     TeamsTableRef.value.exportCSV();
    // };

    // Navigate to the edit page of a Team
    // const editEntity = ( Team ) => {
    //     router.visit(`/career-Teams/${Team.id}/edit`);
    // };

    // Popup a dialog to confirm the deletion of a Team
    // const confirmDeleteTeam = ( Team ) => {
    //     selectedTeam.value = Team;
    //     deleteTeamDialog.value = true;
    // };

    // Delete the Team
    // const deleteTeam = () => {
    //     if( !selectedTeam.value.id || selectedTeam.value.id <= 0 ) return;

    //     router.delete(`/career-Teams/${selectedTeam.value.id}`);

    //     deleteTeamDialog.value = false;
    //     selectedTeam.value = null;
    // };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Εθελοντικές Ομάδες
        </template>

        <template #page-content>
            <!-- <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="filters.search" placeholder="Search" :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>

                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportCareerTeamsAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div> -->

            <DataTable ref="TeamsTableRef" :value="teams" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterTeamsTable">
                <template #empty>Δεν βρέθηκαν εθελοντικές ομάδες.</template>

                <Column field="name" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα Ομάδας</span>
                        {{ data.name }}
                    </template>
                </Column>

                <Column field="description" header="Περιγραφή" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Περιγραφή</span>
                        {{ data.description }}
                    </template>
                </Column>

                <!-- <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editEntity(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteTeam(slotProps.data)" />
                    </template>
                </Column> -->
            </DataTable>
        </template>
    </AppPageWrapper>

    <!-- <Dialog v-model:visible="deleteTeamDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedTeam">
                Είστε σίγουροι οτι θέλετε να διαγράψετε το ενδιαφέρον <b>{{ selectedTeam.name }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteTeamDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteTeam" />
        </template>
    </Dialog> -->
</template>

