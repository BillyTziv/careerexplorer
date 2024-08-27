<script setup>
    import { ref, watch, reactive } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    import { useUsersTableStore } from '@/Stores/useUsersTable.store';
    const usersTableStore = useUsersTableStore();

    let props = defineProps({
        user: Object,
        users: Object, 
        roleDropdownOptions: Array,
        filters: Object,
        response: Object,
    });

    const selectedUser = ref(null);
    const deleteUserDialog = ref(false);
    const usersTableRef = ref(null);
    const filterUsersTable = ref(props.filters);
    
    const exportCSV = () => {
        usersTableRef.value.exportCSV();
    };

    const editUser = ( user ) => {    
        router.visit(`/users/${user.id}/edit`);
    };

    const confirmDeleteUser = ( editUser ) => {
        selectedUser.value = editUser;
        deleteUserDialog.value = true;
    };

    const deleteUser = () => {
        router.delete(`/users/${selectedUser.value.id}`)
        deleteUserDialog.value = false;
        selectedUser.value = {};
        // users.value = users.value.filter((val) => val.id !== user.value.id);
    
        // toast.add({ severity: 'success', summary: 'Successful', detail: 'Ο χ Deleted', life: 3000 });
    };
    const filters = reactive( usersTableStore.getTableFilters );

    /* Watch for changes in the filters */
    watch(() => usersTableStore.getTableFilters, () => {
        router.get('/users/', usersTableStore.getTableFilters, { preserveState: true, replace: true });
    }, { deep: true });

    // Export the Users table to CSV
    const exportUsersAsCSV = () => {
        usersTableRef.value.exportCSV();
    };

    const redirectToCreate = () => {    
        router.visit(`/users/create`);
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Χρήστες
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="filters.search" placeholder="Search" :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>
                
                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportUsersAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

            <DataTable ref="usersTableRef" :value="users.data" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterUsersTable">
                <template #empty>Δεν βρέθηκαν χρήστες.</template>
                <Column field="firstname" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα</span>
                        {{ data.firstname }}
                    </template>
                </Column>

                <Column field="lastname" header="Επίθετο" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Επίθετο</span>
                        {{ data.lastname }}
                    </template>
                </Column>

                <Column field="user_role" header="Ρόλος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Ρόλος</span>
                        {{ data.user_role }}
                    </template>
                </Column>

                <Column field="phone" header="Τηλέφωνο" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Τηλέφωνο</span>
                        {{ data.phone }}
                    </template>
                </Column>

                <Column field="email" header="Email" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Email</span>
                        {{ data.email }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editUser(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteUser(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteUserDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής Χρήστη" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedUser">
                Είστε σίγουροι οτι θέλετε να διαγράψετε τον χρήστη <b>{{ selectedUser.firstname }} {{ selectedUser.lastname }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteUserDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteUser" />
        </template>
    </Dialog>
</template>

