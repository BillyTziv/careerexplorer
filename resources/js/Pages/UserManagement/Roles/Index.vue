<script setup>
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    let props = defineProps({
        user: Object,
        roles: Object, 
        filters: Object,
        response: Object,
    });

    const selectedRole = ref(null);
    const deleteRoleDialog = ref(false);
    const rolesTableRef = ref(null);
    const filterRolesTable = ref(props.filters);
    const searchFilter = ref('');

    const exportCSV = () => {
        rolesTableRef.value.exportCSV();
    };

    const searchRoles = ( searchTerm ) => {
        router.get('/roles', { search: searchTerm }, { preserveState: true, replace: true });
    }

    const editRole = ( role ) => {    
        router.visit(`/roles/${role.id}/edit`);
    };

    const confirmDeleteRole = ( editRole ) => {
        selectedRole.value = editRole;
        deleteRoleDialog.value = true;
    };
    
    const deleteRole = () => {
        router.delete(`/roles/${selectedRole.value.id}`)
        deleteRoleDialog.value = false;
        selectedRole.value = {};
        // roles.value = roles.value.filter((val) => val.id !== user.value.id);
    
        // toast.add({ severity: 'success', summary: 'Successful', detail: 'Ο χ Deleted', life: 3000 });
    };

    watch(() => searchFilter.value, (searchTerm) => {
        searchRoles(searchTerm);
    });

    const redirectToCreate = () => {    
        router.visit(`/roles/create`);
    };

    // Export the volunteers table to CSV
    const exportTableDataAsCSV = () => {
        rolesTableRef.value.exportCSV();
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Ρόλοι
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="searchFilter" placeholder="Search" :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>

                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportTableDataAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

            <DataTable ref="rolesTableRef" :value="roles.data" dataKey="id" paginator :rows="10" responsiveLayout="scroll" v-model:filters="filterRolesTable">
                <template #empty>Δεν βρέθηκαν ρόλοι.</template>
                <Column field="name" header="Όνομα Ρόλου" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα Ρόλου</span>
                        {{ data.name }}
                    </template>
                </Column>

                <Column header="Ενέργειες" headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editRole(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteRole(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteRoleDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής Χρήστη" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedRole">
                Είστε σίγουροι οτι θέλετε να διαγράψετε τον ρόλο <b>{{ selectedRole.name }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteRoleDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteRole" />
        </template>
    </Dialog>
</template>

