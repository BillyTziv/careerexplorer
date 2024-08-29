<script setup>
    import { ref, reactive, watch } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Stores */
    import { useCareersStore } from '@/Stores/useCareers.store';

    const careersStore = useCareersStore();

    let props = defineProps({
        career: Object,
        careers: Array, 
        roleDropdownOptions: Array,
        filters: Object,
        response: Object,
    });

    const selectedCareer = ref(null);           // The selected career to edit or delete
    const deleteCareerDialog = ref(false);      // Dialog to confirm the deletion of a career
    const careersTableRef = ref(null);          // Reference to the careers datatable
    const filters = reactive( careersStore.getTableFilters );
    const filterCareerTable = ref(props.filters);

    const redirectToCreate = () => {    
        router.visit(`/careers/create`);
    };

    /****************************************************************************************************
     * DATATABLE ACTIONS
     ***************************************************************************************************/
    function openLink( link ) {
        window.open(link, '_blank');
    }

    const editCareer = ( career ) => {    
        router.visit(`/careers/${career.id}/edit`);
    };

    const confirmDeleteCareer = ( editCareer ) => {
        selectedCareer.value = editCareer;
        deleteCareerDialog.value = true;
    };

    const deleteCareer = () => {
        router.delete(`/careers/${selectedCareer.value.id}`)

        deleteCareerDialog.value = false;
        selectedCareer.value = {};
        // careers.value = careers.value.filter((val) => val.id !== career.value.id);
    
        // toast.add({ severity: 'success', summary: 'Successful', detail: 'Ο χ Deleted', life: 3000 });
    };

    // Export the careers table to CSV
    const exportCareersAsCSV = () => {
        careersTableRef.value.exportCSV();
    };

    /****************************************************************************************************
     * DATATABLE FILTERS
     ***************************************************************************************************/

    /* Watch for changes in the filters */
    watch(() => careersStore.getTableFilters, () => {
        router.get('/careers/', careersStore.getTableFilters, { 
            preserveState: true, replace: true 
        });
    }, { deep: true });
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Επαγγέλματα
        </template>

        <template #page-content>
            <!-- <div class="flex flex-column md:flex-row align-items-start md:align-items-start mb-3 w-full">
                <BaseDropdownInput
                    v-model="filters.role"
                    placeholder="Όλοι οι Ρόλοι"
                    :options="volunteerStore.getRoleDropdownOptions"
                    class="mx-1"
                />

                <BaseDropdownInput
                    v-model="filters.status"
                    placeholder="Όλες οι Καταστάσεις"
                    :options="volunteerStore.getStatusDropdownOptions"
                    class="mx-1"
                />
            
                <Button
                    type="button"
                    icon="pi pi-filter-slash"
                    label="Καθαρισμός"
                    outlined
                    @click="clearFilters()"
                    class="mx-1"
                />
            </div> -->

            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="filters.search" placeholder="Search" :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>
                
                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportCareersAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>
                    
            <DataTable ref="careersTableRef" :value="careers" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterCareerTable">
                <template #empty>Δεν βρέθηκαν επαγγέλματα.</template>
                
                <Column field="name" header="Τίτλος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Τίτλος</span>
                        {{ data.title }}
                    </template>
                </Column>

                <Column field="name" header="Σύνδεσμος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Σύνδεσμος</span>
                        <Button @click="openLink(data.link)" icon="pi pi-link" text rounded />
                    </template>
                </Column>

                <Column field="holland_code" header="Holland Code" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Holland Code</span>
                        {{ data.riasecCodes }}
                    </template>
                </Column>

                <Column header="Ενέργειες" headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editCareer(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteCareer(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteCareerDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής Χρήστη" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedCareer">
                Είστε σίγουροι οτι θέλετε να διαγράψετε το επάγγελμα <b>{{ selectedCareer.title }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteCareerDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteCareer" />
        </template>
    </Dialog>
</template>