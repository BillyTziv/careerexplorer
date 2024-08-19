<script setup>
    /* Core */
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Emits Actions */


    /* Component Properties */
    let props = defineProps({
        user: Object,
        interests: Object, 
        filters: Object,
        response: Object,
    });

    /* Component Reactive Variables */
    const selectedInterest         = ref( null );              // Used for delete confirmation
    const deleteInterestDialog     = ref( false );             // Used for delete confirmation
    const interestsTableRef        = ref( null );              // Used for exportCSV
    const filterInterestsTable     = ref( props.filters );     // Used for filtering the table
    
    /* Component Methods */

    // Export the interest table to CSV
    const exportCSV = () => {
        interestsTableRef.value.exportCSV();
    };

    // Navigate to the edit page of a interest
    const editEntity = ( interest ) => {    
        router.visit(`/career-interests/${interest.id}/edit`);
    };

    // Popup a dialog to confirm the deletion of a interest
    const confirmDeleteInterest = ( interest ) => {
        selectedInterest.value = interest;
        deleteInterestDialog.value = true;
    };

    // Delete the interest
    const deleteInterest = () => {
        if( !selectedInterest.value.id || selectedInterest.value.id <= 0 ) return;

        router.delete(`/career-interests/${selectedInterest.value.id}`);

        deleteInterestDialog.value = false;
        selectedInterest.value = null;
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Ενδιαφέροντα
        </template>

        <template #page-content>
            <DataTable ref="interestsTableRef" :value="interests.data" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterInterestsTable">
                <template #empty>Δεν βρέθηκαν ενδιαφέροντα.</template>
                
                <Column field="name" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα</span>
                        {{ data.name }}
                    </template>
                </Column>

                <Column field="description" header="Περιγραφή" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Περιγραφή</span>
                        {{ data.description }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editEntity(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteInterest(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteInterestDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedInterest">
                Είστε σίγουροι οτι θέλετε να διαγράψετε το ενδιαφέρον <b>{{ selectedInterest.name }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteInterestDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteInterest" />
        </template>
    </Dialog>
</template>

