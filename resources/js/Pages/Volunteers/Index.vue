<script setup>
    /* Core */
    import { computed, ref, reactive, provide, watch, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Components */
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';

    import { useVolunteersStore } from '@/Stores/useVolunteer.store';

    import { useVolunteerAssigneeRecruiterMapper } from '@/Composables/useVolunteerAssigneeRecruiterMapper';
    import { useVolunteerRoleMapper } from '@/Composables/useVolunteerRoleMapper';
    import { useVolunteerStatusMapper } from '@/Composables/useVolunteerStatusMapper';
    import { useFormatDate } from '@/Composables/useFormatDate';
	import { useToastNotification } from '@/Composables/useToastNotification';

    const volunteerStore = useVolunteersStore();

    /* Component Properties */
    let props = defineProps({
        auth: {
            type: Object,
            default: {}
        },
        response: {
            type: Object,
            default: {}
        },
        volunteers: {
            type: Array,
            default: ({}) => []
        },
        volunteerRoleDropdownOptions: {
            type: Array,
            default: () => []
        },
        volunteerStatusDropdownOptions: {
            type: Array,
            default: () => []
        },
        volunteerAssignedRecruiterDropdownOptions: {
            type: Array,
            default: () => []
        },
    });
    
    const { getRecruiterName } = useVolunteerAssigneeRecruiterMapper( props.volunteerAssignedRecruiterDropdownOptions );
    const { getRoleName } = useVolunteerRoleMapper( props.volunteerRoleDropdownOptions );
    const { getStatusName, adjustOpacity, determineTextColor  } = useVolunteerStatusMapper( props.volunteerStatusDropdownOptions );
    const { formatDate } = useFormatDate( );
	const { notify } = useToastNotification();

    // Used to provide the status options into the datatable component that render the status column.
    provide('volunteerStatusDropdownOptions', props.volunteerStatusDropdownOptions);

    /* Component Reactive Variables */
    const selectVolunteer           = ref( null );              // Used for delete confirmation
    const deleteVolunteerDialog     = ref( false );             // Used for delete confirmation
    const previewEmailDialog = ref( false );             // Used for preview email dialog
    const volunteersTableRef        = ref( null );              // Used for exportCSV
    const filterVolunteersTable     = ref( props.filters );     // Used for filtering the table
    const volunteerEmailList        = ref( '' );                // Used for preview email dialog
    
    /* Component Methods */

    // Export the volunteers table to CSV
    const exportVolunteersAsCSV = () => {
        volunteersTableRef.value.exportCSV();
    };

    // Navigate to the edit page of a volunteer
    const editEntity = ( volunteer ) => {    
        router.visit(`/volunteers/${volunteer.id}/edit`);
    };

    const viewDetails = ( volunteer ) => {
        router.visit(`/volunteers/${volunteer.id}`);
    };

    // Popup a dialog to confirm the deletion of a volunteer
    const confirmDeleteVolunteer = ( volunteer ) => {
        selectVolunteer.value = volunteer;
        deleteVolunteerDialog.value = true;
    };

    // Delete the volunteer
    const deleteVolunteer = () => {
        if( !selectVolunteer.value.id || selectVolunteer.value.id <= 0 ) return;

        router.delete(`/volunteers/${selectVolunteer.value.id}`, 
            { 
                preserveState: true, 
                replace: true, 
                onSuccess: () => {
                    notify('success', 'Ολοκληρώθηκε', `Ο εθελοντής ${selectVolunteer.value.firstname} ${selectVolunteer.value.lastname} διαγράφηκε επιτυχώς.`);
                    deleteVolunteerDialog.value = false;
                    selectVolunteer.value = null;
                }
            }
        );
    };

    const filters = reactive( volunteerStore.getTableFilters );


    /* Watch for changes in the filters */
    watch(() => volunteerStore.getTableFilters, () => {
        router.get('/volunteers/', volunteerStore.getTableFilters, { preserveState: true, replace: true });
    }, { deep: true });

    // -------------------------------------------------------------
    // FUNCTIONS
    // -------------------------------------------------------------

    function clearFilters() {
        volunteerStore.resetTableFilters();
    }

    // -------------------------------------------------------------
    // HOOKS
    // -------------------------------------------------------------
    onMounted(() => {
        volunteerStore.setRoleDropdownOptions( props.volunteerRoleDropdownOptions);
        volunteerStore.setStatusDropdownOptions( props.volunteerStatusDropdownOptions );
        volunteerStore.setRecruiterDropdownOptions( props.volunteerAssignedRecruiterDropdownOptions );

        router.reload({ only: ['volunteers'] });
    });

    const currentPageReportTemplate = computed(() => {
        return `Προβολή ${volunteerStore.from} μέχρι ${volunteerStore.to} απο ${volunteerStore.total} εγγραφές`;
    });

    /* Datatable Size Change */
    const size = ref({ label: 'Small', value: 'small' });
    
    const sizeOptions = ref([
        { label: 'Small', value: 'small' },
        { label: 'Normal', value: 'null' },
        { label: 'Large', value: 'large' }
    ]);

    const selectedVolunteers = ref();

    const redirectToCreate = () => {    
        router.visit(`/volunteers/create`);
    };

    const viewSelectedVolunteerEmail = () => {
        if( selectedVolunteers.value.length <= 0 ) {
            notify('error', 'Σφάλμα', 'Δεν έχετε επιλέξει κανέναν εθελοντή.');
            return;
        }

        let emails = selectedVolunteers.value.map( volunteer => volunteer.email ).join(', ');
        volunteerEmailList.value = emails;
        previewEmailDialog.value = true;
    };

    const copyVolunteerEmailListToClipboard = () => {
        navigator.clipboard.writeText( volunteerEmailList.value );
        notify('success', 'Επιτυχία', 'Η λίστα με τα email αντιγράφτηκε στο πρόχειρο.');
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Εθελοντές
        </template>

        <template #page-content>
            <!-- <InviteVolunteerModal
                v-if="showInviteVolunteerModal"
                @close-modal="showInviteVolunteerModal = false"
            /> -->

            <!-- <BaseTableFiltersLayout>
                <BaseSingleSelect
                    @change="filterVolunteersByRole( $event )"
                    v-model="filters.role"
                    :options="volunteerStore.getRoleDropdownOptions"
                    label="Όλοι οι ρόλοι"
                    accessKey="role"
                    :errors="''"
                />

                <BaseSingleSelect
                    @change="filterVolunteersByStatus( $event )"
                    v-model="filters.status"
                    :options="volunteerStore.getStatusDropdownOptions"
                    label="Όλες οι Καταστάσεις"
                    accessKey="role"
                    :errors="''"
                />

                <BaseClickButton
                    @click="volunteerStore.resetTableFilters"
                    :svg-path="['M6 18 18 6M6 6l12 12']"
                    :label="'Καθαρισμός Φίλτρων'"
                />
            </BaseTableFiltersLayout> -->

            <div class="flex flex-column md:flex-row align-items-start md:align-items-start mb-3 w-full">
                <BaseDropdownInput
                    v-model="filters.role"
                    placeholder="Όλοι οι Ρόλοι"
                    :options="volunteerStore.getRoleDropdownOptions"
                    :narrow="true"
                    class="mx-1"
                />

                <BaseDropdownInput
                    v-model="filters.status"
                    placeholder="Όλες οι Καταστάσεις"
                    :options="volunteerStore.getStatusDropdownOptions"
                    :narrow="true"
                    class="mx-1"
                />
            
                <BaseDropdownInput
                    v-model="filters.assigned_recruiter"
                    placeholder="Όλοι οι recruiter"
                    :options="volunteerStore.getRecruiterDropdownOptions"
                    :narrow="true"
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
            </div>

            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="filters.search" placeholder="Αναζήτηση με όνομα, email, τηλ.." :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>

                <div class="flex">
                    <Button type="button" icon="pi pi-envelope" rounded v-tooltip="'Preview Emails'" text @click="viewSelectedVolunteerEmail"></Button>
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportVolunteersAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

            <DataTable
                dataKey="id"
                ref="volunteersTableRef"
                :value="volunteers"
                v-model:filters="filterVolunteersTable"
                v-model:selection="selectedVolunteers"
                :filters="filters"
                :size="size.value"
                stripedRows
                responsiveLayout="scroll"
                paginator
                :rows="10"
                :rowsPerPageOptions="[5, 10, 20, 50]"
            >
                <template #empty>Δεν βρέθηκαν εθελοντές.</template>
                
                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>

                <Column headerStyle="width: 5rem">
                    <template #header>
                        
                    </template>
                    <template #body="slotProps">
                        <Button icon="pi pi-search" rounded text @click="viewDetails(slotProps.data)" />
                    </template>
                </Column>

                <Column field="firstname" header="Όνομα" sortable >
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα</span>
                        {{ data.firstname }}
                    </template>
                </Column>

                <Column field="lastname" header="Επώνυμο" sortable >
                    <template #body="{ data }">
                        <span class="p-column-title">Επώνυμο</span>
                        {{ data.lastname }}
                    </template>
                </Column>

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

                <Column field="role" header="Ρόλος" sortable>
                    <template #body="{ data }">
                        <span class="p-column-title">Ρόλος</span>
                        {{ getRoleName(data.role) }}
                    </template>
                </Column>

                <Column field="assigneeRecruiter" header="Υπεύθυνος" sortable>
                    <template #body="{ data }">
                        <span class="p-column-title">Υπεύθυνος</span>
                        {{ getRecruiterName(data.assigned_to) }}
                    </template>
                </Column>


                <!-- <Column field="email" header="Email" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Email</span>
                        {{ data.email }}
                    </template>
                </Column> -->

                <!-- <Column field="phone" header="Τηλέφωνο" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Τηλέφωνο</span>
                        {{ data.phone }}
                    </template>
                </Column> -->

                <Column field="created_at" header="Ημ/νια Αίτησης" sortable :frozen="true">
                    <template #body="{ data }">
                        <span class="p-column-title">Ημ/νια Αίτησης</span>
                        {{ formatDate(data.created_at) }}
                    </template>
                </Column>
 
                <Column header="Ενέργειες">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editEntity(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteVolunteer(slotProps.data)" />
                    </template>
                </Column>

                <template #paginatorstart>
                    <!-- <Dropdown v-model="size" :options="sizeOptions" optionLabel="label" dataKey="label"  class="w-full md:w-15rem" @change="onSortChange($event)" /> -->
                </template>

                <template #paginatorend>
                    {{ currentPageReportTemplate }}

                    Εμφάνιση υπάρχουν {{ props.volunteers ? props.volunteers.length : 0 }} εθελοντές.
                </template>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="previewEmailDialog" :style="{ width: '450px' }" header="Λίστα με Email" :modal="true">
        <div class="flex align-items-center justify-content-center">
            {{ volunteerEmailList }}
        </div>
        <template #footer>
            <Button class="flex align-items-center justify-content-center" label="Αντιγραφή" icon="pi pi-copy" @click="copyVolunteerEmailListToClipboard" />
        </template>
    </Dialog>

    <Dialog v-model:visible="deleteVolunteerDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectVolunteer">
                Είστε σίγουροι οτι θέλετε να διαγράψετε τον εθελοντή: <b>{{ selectVolunteer.firstname }} {{ selectVolunteer.lastname }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteVolunteerDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteVolunteer" />
        </template>
    </Dialog>
</template>

<style scoped lang="scss">
    :deep(.p-datatable-frozen-tbody) {
        font-weight: bold;
    }

    :deep(.p-datatable-scrollable .p-frozen-column) {
        font-weight: bold;
    }
</style>