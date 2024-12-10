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
    import { useFormatDate } from '@/Composables/useFormatDate';
	import { useToastNotification } from '@/Composables/useToastNotification';

    let props = defineProps({
        user: Object,
        sessions: Object,
        roleDropdownOptions: Array,
        sessionRequestStatusDropdownOptions: Array,
        response: Object,
    });

    const { getStatusName, adjustOpacity, determineTextColor  } = useSessionRequestStatusMapper( props.sessionRequestStatusDropdownOptions );
    const { formatDate } = useFormatDate( );
	const { notify } = useToastNotification();

    // const deleteUserDialog = ref(false);
    // const selectedSessionRequest = ref(null);
    const sessionRequestsTableRef = ref(null);

    const sessionRequestFilters = reactive( sessionRequestStore.getTableFilters );
    const filterVolunteersTable     = ref( null );     // Used for filtering the table

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
        router.put(`/session-requests/${sessionRequest.id}/accept`, {},
        {
            preserveState: true,
            replace: true,
            onSuccess: () => {
                notify('success', 'Ολοκληρώθηκε', `Η αίτηση συνεδρίας έχει ανατεθεί σε σύμβουλο Ε.Π. επιτυχώς.`);
            }
        });
    };

    const completeSessionRequest = (sessionRequest) => {
        router.put(`/session-requests/${sessionRequest.id}/complete`, {},
        {
            preserveState: true,
            replace: true,
            onSuccess: () => {
                notify('success', 'Ολοκληρώθηκε', `Η αίτηση συνεδρίας του χρήστη ${sessionRequest.firstname} ${sessionRequest.lastname} ολοκληρώθηκε επιτυχώς.`);
            }
        })
    };

    // const confirmDeleteSessionRequest = ( editUser ) => {
    //     selectedSessionRequest.value = editUser;
    //     deleteUserDialog.value = true;
    // };

    // const deleteSessionRequest = () => {
    //     router.delete(`/session-requests/${selectedSessionRequest.value.id}`)
    //     deleteUserDialog.value = false;
    //     selectedSessionRequest.value = {};
    // };

    const exportSessionRequestsAsCSV = () => {
        sessionRequestsTableRef.value.exportCSV();
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Αιτήσεις Συνεδριών
        </template>

        <template #page-subtitle>

        </template>

        <template #page-content>
            <!-- <div class="flex flex-column md:flex-row align-items-start md:align-items-start mb-3 w-full">
                <BaseDropdownInput
                    v-model="sessionRequestFilters.status"
                    placeholder="Όλες οι Καταστάσεις"
                    :options="sessionRequestStore.getStatusDropdownOptions"
                    class="mx-1"
                    :narrow="true"
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
                    <!-- <InputIcon class="pi pi-search" /> -->
                    <!-- <InputText type="text" v-model="sessionRequestFilters.search" placeholder="Αναζήτηση.." :style="{ borderRadius: '2rem' }" class="w-full" /> -->
                </IconField>

                <div class="flex">
                    <Button @click="exportSessionRequestsAsCSV" type="button" icon="pi pi-download" v-tooltip="'Λήψη των αιτήσεων συνεδρίας'" rounded outlined class="mx-1" />
                    <Button @click="redirectToCreate" type="button" icon="pi pi-plus" rounded class="mx-1" />
                </div>
            </div>

            <DataTable
                ref="sessionRequestsTableRef"
                :value="sessions"
                dataKey="id"
                paginator
                :rows="15"
                responsiveLayout="scroll"
                v-model="sessionRequestFilters"
                v-model:filters="filterVolunteersTable"

            >
                <template #empty>Δεν βρέθηκαν αιτήσεις συνεδριών.</template>

                <Column field="id" header="A/A">
                    <template #body="{ data }">
                        <span class="p-column-title">A/A</span>
                        {{ data.id }}
                    </template>
                </Column>

                <!-- <Column headerStyle="width: 5rem">
                    <template #header>

                    </template>
                    <template #body="slotProps">
                        <Button icon="pi pi-search" rounded text @click="viewDetails(slotProps.data)" />
                    </template>
                </Column> -->

                <Column field="firstname" header="Όνομα Υποψηφίου" sortable>
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα Υποψηφίου</span>
                        {{ data.firstname }}
                    </template>
                </Column>

                <Column field="lastname" header="Επίθετο Υποψηφίου" sortable>
                    <template #body="{ data }">
                        <span class="p-column-title">Επίθετο Υποψηφίου</span>
                        {{ data.lastname.toUpperCase() }}
                    </template>
                </Column>

                <!-- <Column field="assignee" header="Σύμβουλος Ε.Π." sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Σύμβουλος Ε.Π.</span>
                        {{ data.assignee_firstname }} {{ data.assignee_lastname }}
                    </template>
                </Column> -->

                <Column field="status" header="Κατάσταση" sortable>
                    <template #body="{ data }">
                        <span
                            class="whitespace-nowrap text-md mr-2 border-l-2 dark:text-slate-300 px-4 py-1 rounded-md shadow-md"
                            :style="{
                                'background-color': adjustOpacity(data.status, 0.2),
                                'color': determineTextColor(data.status),
                                'white-space': 'nowrap',
                                'overflow': 'hidden',
                                'text-overflow': 'ellipsis',
                            }"
                        >
                            {{ getStatusName(data.status) }}
                        </span>
                    </template>
                </Column>

                <!-- <Column field="status" header="Κατάσταση" sortable :headerStyle="{ minWidth: '15rem' }">
                    <template #body="{ data }">
                        <span
                            class="whitespace-nowrap text-md mr-2 border-l-2 dark:text-slate-300 px-4 py-1 rounded-md shadow-md"
                        >
                           {{ getStatusName( data.status ) }}
                        </span>
                    </template>
                </Column> -->

                <!-- <Column field="phone" header="Τηλέφωνο" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Τηλέφωνο</span>
                        {{ data.phone }}
                    </template>
                </Column> -->

                <Column field="email" header="Ημ/νία Αίτησης" sortable>
                    <template #body="{ data }">
                        <span class="p-column-title">Ημ/νία Αίτησης</span>
                        {{ formatDate(data.created_at) }}
                    </template>
                </Column>

               <Column field="actions" header="Ενέργειες" headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button label="Αποδοχή" class="mr-2" rounded outlined severity="success" @click="acceptSessionRequest(slotProps.data)" />
                        <!-- <Button v-if="slotProps.data.status === 2" icon="pi pi-times" label="Απόρριψη" class="mr-2" rounded outlined severity="danger" @click="rejectSessionRequest(slotProps.data)" /> -->
                        <!-- <Button  icon="pi pi-check" label="Ολοκλήρωση" class="mr-2" rounded outlined severity="success" @click="completeSessionRequest(slotProps.data)" /> -->

                        <!-- <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editSessionRequest(slotProps.data)" /> -->
                            <!-- <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteSessionRequest(slotProps.data)" /> -->
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>
</template>
