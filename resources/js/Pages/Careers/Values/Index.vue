<script setup>
    /* Core */
    import { computed, ref, reactive, provide, watch, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Emits Actions */

    /* Component Properties */
    let props = defineProps({
        user: Object,
        careerValues: {
            type: Array,
            default: ({}) => []
        }, 
        filters: Object,
        response: Object,
    });

    /* Component Reactive Variables */
    const selectedCareerValue         = ref( null );              // Used for delete confirmation
    const deleteCareerValueDialog     = ref( false );             // Used for delete confirmation
    const careerValuesTableRef        = ref( null );              // Used for exportCSV
    const filterCareerValuesTable     = ref( props.filters );     // Used for filtering the table
    
    const redirectToCreate = () => {    
        router.visit(`/career-values/create`);
    };

    /* Component Methods */

    // Export the careerValues table to CSV
    const exportCareerValuesAsCSV = () => {
        careerValuesTableRef.value.exportCSV();
    };

    // Navigate to the edit page of a careerValue
    const editEntity = ( careerValue ) => {    
        router.visit(`/career-values/${careerValue.id}/edit`);
    };

    // Popup a dialog to confirm the deletion of a careerValue
    const confirmdeleteCareerValue = ( careerValue ) => {
        selectedCareerValue.value = careerValue;
        deleteCareerValueDialog.value = true;
    };

    // Delete the career value
    const deleteCareerValue = () => {
        if( !selectedCareerValue.value.id || selectedCareerValue.value.id <= 0 ) return;

        router.delete(`/career-values/${selectedCareerValue.value.id}`);

        deleteCareerValueDialog.value = false;
        selectedCareerValue.value = null;
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Εργασιακές Αξίες
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="filters.search" placeholder="Search" :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>
                
                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportCareerValuesAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

    
            <DataTable ref="careerValuesTableRef" :value="careerValues" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterCareerValuesTable">
                <template #empty>Δεν βρέθηκαν εργασιακές αξίες.</template>
                
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
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmdeleteCareerValue(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <!-- <Dialog v-model:visible="deleteCareerValueDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedCareerValue">
                Είστε σίγουροι οτι θέλετε να διαγράψετε την εργασιακές αξίες <b>{{ selectedCareerValue.name }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteCareerValueDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteCareerValue" />
        </template>
    </Dialog> -->
</template>

