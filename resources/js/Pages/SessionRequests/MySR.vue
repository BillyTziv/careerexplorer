<script setup>
import { ref, watch, reactive } from 'vue';
import { router } from '@inertiajs/vue3'

/* Components */
// import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
// import BaseInfoText from '@/Components/Base/BaseInfoText.vue';

/* Layouts */
import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

import { useSessionRequestStatusMapper } from '@/Composables/useSessionRequestStatusMapper';

import { useSessionRequestStore } from '@/Stores/useSessionRequest.store';
import { useToastNotification } from '@/Composables/useToastNotification';

const sessionRequestStore = useSessionRequestStore();

let props = defineProps({
    user: Object,
    sessions: Object,
    roleDropdownOptions: Array,
    filters: Object,
    response: Object,
    sessionRequestStatusDropdownOptions: Array,
});

const { getStatusName, adjustOpacity, determineTextColor  } = useSessionRequestStatusMapper( props.sessionRequestStatusDropdownOptions );
const { notify } = useToastNotification();

const selectedSR = ref(null);
const sessionRequestsTableRef = ref(null);
const completeSRDialog = ref(false);
const selectedSessionRequests = ref();
const filterSessionRequestsTable     = ref( props.filters );
const size = ref({ label: 'Small', value: 'small' });

const filters = reactive(sessionRequestStore.getTableFilters);

const exportCSV = () => {
    sessionRequestsTableRef.value.exportCSV();
};

/* Watch for changes in the filters */
watch(() => sessionRequestStore.getTableFilters, () => {
    router.get('/my-session-requests/', sessionRequestStore.getTableFilters, { preserveState: true, replace: true });
}, { deep: true });

// Popup a dialog to confirm the deletion of a volunteer
const confirmCompleteSR = (sessionRequest) => {
    selectedSR.value = sessionRequest;
    completeSRDialog.value = true;
};

const viewDetails = ( sessionRequest ) => {
    router.visit(`/session-requests/${sessionRequest.id}`);
};

// Popup a dialog to confirm the deletion of a volunteer
const dropSR = (sessionRequest) => {
    selectedSR.value = sessionRequest;
    router.put(`/session-requests/${selectedSR.value.id}/reject`, {},
        {
            preserveState: true,
            replace: true,
            onSuccess: () => {
                completeSRDialog.value = false;

                notify('success', 'Ολοκληρώθηκε', `Η συνεδρία επαγγελματικού προσανατολισμού ολοκληρώθηκε επιτυχώς.`);
            }
        }
    );
};

const completeSR = () => {
    router.put(`/session-requests/${selectedSR.value.id}/complete`, {},
        {
            preserveState: true,
            replace: true,
            onSuccess: () => {
                completeSRDialog.value = false;

                notify('success', 'Ολοκληρώθηκε', `Η συνεδρία επαγγελματικού προσανατολισμού ολοκληρώθηκε επιτυχώς.`);
            }
        }
    );
};
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Οι Συνεδρίες Μου
        </template>

        <template #page-content>
            <!-- <BaseInfoText>
                Μπορείτε να έχετε ταυτόχρονα μέχρι και 10 συνεδρίες σε εξέλιξη. Μετά την ολοκλήρωση μιας συνεδρίας,
                μπορείτε να αποδεχτείτε νέα.
            </BaseInfoText> -->

            <div
                class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="filters.search" placeholder="Αναζήτηση.."
                        :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>
            </div>

            <DataTable
                dataKey="id"
                ref="sessionRequestsTableRef"
                :value="sessions"
                v-model:filters="filterSessionRequestsTable"
                v-model:selection="selectedSessionRequests"
                :filters="filters"
                :size="size.value"
                stripedRows
                responsiveLayout="scroll"
                paginator
                :rows="10"
                :rowsPerPageOptions="[5, 10, 20, 50]"
            >
                <template #empty>
                    Δεν βρέθηκαν ενεργές συνεδρίες. Επιλέξτε από το μενού αιτήσεις συνεδριών και κάνε αποδοχή.
                </template>


                <Column headerStyle="width: 5rem">
                    <template #header>

                    </template>
                    <template #body="slotProps">
                        <Button icon="pi pi-search" rounded  outlined  @click="viewDetails(slotProps.data)" />
                    </template>
                </Column>

                <Column field="firstname" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα</span>
                        {{ data.firstname }}
                    </template>
                </Column>

                <Column field="lastname" header="Επώνυμο" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Επώνυμο</span>
                        {{ data.lastname }}
                    </template>
                </Column>

                <!-- <Column field="assignee" header="Υπεύθυνος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Υπεύθυνος</span>
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

                <!-- <Column field="phone" header="Τηλέφωνο" sortable :headerStyle="{ minWidth: '12rem' }">
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
                </Column> -->

                <!-- <Column field="actions" header="Ενέργειες">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="confirmCompleteSR(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="dropSR(slotProps.data)" />
                    </template>
                </Column> -->
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="completeSRDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Ολοκλήρωσης"
        :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span>
                Είστε σίγουροι πως θέλετε να ολοκληρώσετε την συνεδρία? Με την ενέργεια αυτή, θα σταλεί ένα ενημερωτικό
                email στον υποψήφιο.
            </span>
        </div>

        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="completeSRDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="completeSR" />
        </template>
    </Dialog>
</template>
