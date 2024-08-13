<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    let props = defineProps({
        user: Object,
        sessions: Object, 
        roleDropdownOptions: Array,
        filters: Object,
        response: Object,
    });

    const deleteUserDialog = ref(false);
    const selectedSessionRequest = ref(null);
    const sessionRequestsTableRef = ref(null);
    const filterUsersTable = ref(props.filters);
    
    const exportCSV = () => {
        sessionRequestsTableRef.value.exportCSV();
    };

    const editSessionRequest = ( user ) => {    
        router.visit(`/session-requests/${user.id}/edit`);
    };

    const confirmDeleteSessionRequest = ( editUser ) => {
        selectedSessionRequest.value = editUser;
        deleteUserDialog.value = true;
    };

    const deleteSessionRequest = () => {
        router.delete(`/session-requests/${selectedSessionRequest.value.id}`)
        deleteUserDialog.value = false;
        selectedSessionRequest.value = {};
        // users.value = users.value.filter((val) => val.id !== user.value.id);
    
        // toast.add({ severity: 'success', summary: 'Successful', detail: 'Ο χ Deleted', life: 3000 });
    };

    const test = ref('test');
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Οι συνεδρίες μου
        </template>

        <template #page-subtitle>
            
        </template>

        <template #page-actions>
            <IconField iconPosition="left">
                <InputIcon class="pi pi-search" />
                <InputText type="text" v-model="test" placeholder="Αναζήτηση.." :style="{ borderRadius: '2rem' }" class="w-full" />
            </IconField>
            <Button icon="pi pi-upload" class="mx-3 export-target-button" rounded v-tooltip="'Export'" @click="exportCSV"></Button>
        </template>

        <template #page-content>
            <DataTable ref="sessionRequestsTableRef" :value="sessions.data" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterUsersTable">
                <template #empty>Δεν βρέθηκαν συνεδρίες.</template>
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

                <Column field="status" header="Κατάσταση" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Κατάσταση</span>
                        {{ data.status }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editSessionRequest(slotProps.data)" />
                        <!-- <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteSessionRequest(slotProps.data)" /> -->
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>
</template>