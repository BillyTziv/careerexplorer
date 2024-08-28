<script setup>
    import { ref, watch, reactive } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Components */
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    import { useSessionRequestStatusMapper } from '@/Composables/useSessionRequestStatusMapper';

    import { useSessionRequestStore } from '@/Stores/useSessionRequest.store';

    const sessionRequestStore = useSessionRequestStore();

    let props = defineProps({
        user: Object,
        sessions: Object, 
        roleDropdownOptions: Array,
        filters: Object,
        response: Object,
    });

    const { getStatusName } = useSessionRequestStatusMapper();

    const selectedSessionRequest = ref(null);
    const sessionRequestsTableRef = ref(null);
    const filterUsersTable = ref(props.filters);
    const completeSRDialog = ref( false );

    const filters = reactive( sessionRequestStore.getTableFilters );

    const exportCSV = () => {
        sessionRequestsTableRef.value.exportCSV();
    };

    const editSessionRequest = ( user ) => {
        router.visit(`/session-requests/${user.id}/edit`);
    };

    const assignSessionRequest = ( sessionRequest ) => {
        router.visit(`/session-requests/${sessionRequest.id}/assign`);
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

    /* Watch for changes in the filters */
    watch(() => sessionRequestStore.getTableFilters, () => {
        router.get('/session-requests/', sessionRequestStore.getTableFilters, { preserveState: true, replace: true });
    }, { deep: true });

    function clearFilters() {
        sessionRequestStore.resetTableFilters();
    }

    const redirectToCreate = () => {    
        router.visit(`/session-requests/create`);
    };

    const acceptSessionRequest = (sessionRequest) => {
        router.put(`/session-requests/${sessionRequest.id}/accept`)
        // users.value = users.value.filter((val) => val.id !== user.value.id);
    
        // toast.add({ severity: 'success', summary: 'Successful', detail: 'Ο χ Deleted', life: 3000 });
    };


    // Popup a dialog to confirm the deletion of a volunteer
    const confirmCompleteSR = ( sessionRequest ) => {
        selectedSessionRequest.value = sessionRequest;
        completeSRDialog.value = true;
    };

    const completeSR = () => {
        
        router.put(`/session-requests/${selectedSessionRequest.value.id}/complete`)
        // users.value = users.value.filter((val) => val.id !== user.value.id);
        completeSRDialog.value = false;

        // toast.add({ severity: 'success', summary: 'Successful', detail: 'Ο χ Deleted', life: 3000 });
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Οι Συνεδρίες μου
        </template>

        <template #page-subtitle>
            
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="filters.search" placeholder="Αναζήτηση.." :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>
            </div>

            <DataTable ref="sessionRequestsTableRef" :value="sessions" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterUsersTable">
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

                <Column field="assignee" header="Υπεύθυνος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Υπεύθυνος</span>
                        {{ data.assignee_firstname }} {{ data.assignee_lastname }}
                    </template>
                </Column>

                <Column field="status" header="Κατάσταση" sortable :headerStyle="{ minWidth: '15rem' }">
                    <template #body="{ data }">
                        <span
                            class="whitespace-nowrap text-md mr-2 border-l-2 dark:text-slate-300 px-4 py-1 rounded-md shadow-md"
                        >
                           {{ getStatusName( data.status ) }}
                        </span>
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

               <Column field="actions" header="Ενέργειες" headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button v-if="slotProps.data.status === 2" icon="pi pi-check" label="Ολοκλήρωση" class="mr-2" rounded outlined severity="success" @click="confirmCompleteSR(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog
        v-model:visible="completeSRDialog" 
        :style="{ width: '450px' }" 
        header="Επιβεβαίωση Ολοκλήρωσης" 
        :modal="true"
    >
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span>
                Είστε σίγουροι πως θέλετε να ολοκληρώσετε την συνεδρία? Με την ενέργεια αυτή, θα σταλεί ένα ενημερωτικό email στον υποψήφιο.
            </span>
        </div>

        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="completeSRDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="completeSR" />
        </template>
    </Dialog>
</template>