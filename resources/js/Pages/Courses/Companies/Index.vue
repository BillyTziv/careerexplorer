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
        courseCompanies: Object, 
        filters: Object,
        response: Object,
    });

    /* Component Reactive Variables */
    const courseCompaniesTableRef        = ref( null );              // Used for exportCSV
    const filterCourseCompaniesTable     = ref( props.filters );     // Used for filtering the table
    
    /* Component Methods */

    // Navigate to the edit page of a course
    const editEntity = ( course ) => {    
        router.visit(`/course-companies/${course.id}/edit`);
    };

    // Export the courses table to CSV
    const exportTableDataAsCSV = () => {
        courseCompaniesTableRef.value.exportCSV();
    };


    const redirectToCreate = () => {    
        router.visit(`/course-companies/create`);
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Φορείς Εκπαίδεσης
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <!-- <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="filters.search" placeholder="Search" :style="{ borderRadius: '2rem' }" class="w-full" /> -->
                </IconField>
                
                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportTableDataAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

            <DataTable ref="courseCompaniesTableRef" :value="courseCompanies.data" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterCourseCompaniesTable">
                <template #empty>Δεν βρέθηκαν φορείς εκπαίδευσης.</template>
                
                <Column field="name" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα</span>
                        {{ data.name }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editEntity(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>
</template>

