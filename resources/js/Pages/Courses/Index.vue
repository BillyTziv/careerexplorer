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
        courses: Object, 
        filters: Object,
        response: Object,
    });

    /* Component Reactive Variables */
    const selectedCourse         = ref( null );              // Used for delete confirmation
    const deleteCourseDialog     = ref( false );             // Used for delete confirmation
    const coursesTableRef        = ref( null );              // Used for exportCSV
    const filterCoursesTable     = ref( props.filters );     // Used for filtering the table
    
    /* Component Methods */

    // Export the courses table to CSV
    const exportCSV = () => {
        coursesTableRef.value.exportCSV();
    };

    // Navigate to the edit page of a course
    const editEntity = ( course ) => {    
        router.visit(`/courses/${course.id}/edit`);
    };

    // Popup a dialog to confirm the deletion of a course
    const confirmDeleteCourse = ( course ) => {
        selectedCourse.value = course;
        deleteCourseDialog.value = true;
    };

    // Delete the course
    const deleteCourse = () => {
        if( !selectedCourse.value.id || selectedCourse.value.id <= 0 ) return;

        router.delete(`/courses/${selectedCourse.value.id}`);   // Delete the course

        deleteCourseDialog.value = false;                       // Close the dialog
        selectedCourse.value = null;                            // Reset the selected course
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Μαθήματα
        </template>

        <template #page-content>
            <DataTable ref="coursesTableRef" :value="courses.data" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterCoursesTable">
                <template #empty>Δεν βρέθηκαν μαθήματα.</template>
                
                <Column field="title" header="Όνομα Μαθήματος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα Μαθήματος</span>
                        {{ data.title }}
                    </template>
                </Column>

                <Column field="company_name" header="Φορέας Εκπαίδευσης" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-company_name">Φορέας Εκπαίδευσης</span>
                        {{ data.company_name }}
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
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteCourse(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteCourseDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedCourse">
                Είστε σίγουροι οτι θέλετε να διαγράψετε το μάθημα <b>{{ selectedCourse.title }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteCourseDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteCourse" />
        </template>
    </Dialog>
</template>

