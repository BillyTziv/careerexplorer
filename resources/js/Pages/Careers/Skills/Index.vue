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
        skills: Object, 
        filters: Object,
        response: Object,
    });

    /* Component Reactive Variables */
    const selectedSkill         = ref( null );              // Used for delete confirmation
    const deleteSkillDialog     = ref( false );             // Used for delete confirmation
    const skillsTableRef        = ref( null );              // Used for exportCSV
    const filterSkillsTable     = ref( props.filters );     // Used for filtering the table
    
    /* Component Methods */

    // Export the skills table to CSV
    const exportCSV = () => {
        skillsTableRef.value.exportCSV();
    };

    // Navigate to the edit page of a skill
    const editEntity = ( skill ) => {    
        router.visit(`/career-skills/${skill.id}/edit`);
    };

    // Popup a dialog to confirm the deletion of a skill
    const confirmDeleteSkill = ( skill ) => {
        selectedSkill.value = skill;
        deleteSkillDialog.value = true;
    };

    // Delete the skill
    const deleteSkill = () => {
        if( !selectedSkill.value.id || selectedSkill.value.id <= 0 ) return;

        router.delete(`/career-skills/${selectedSkill.value.id}`);

        deleteSkillDialog.value = false;
        selectedSkill.value = null;
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Δεξιότητες
        </template>

        <template #page-content>
            <DataTable ref="skillsTableRef" :value="skills.data" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterSkillsTable">
                <template #empty>Δεν βρέθηκαν δεξιότητες.</template>
                
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
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteSkill(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteSkillDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedSkill">
                Είστε σίγουροι οτι θέλετε να διαγράψετε την δεξιότητες <b>{{ selectedSkill.name }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteSkillDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteSkill" />
        </template>
    </Dialog>
</template>

