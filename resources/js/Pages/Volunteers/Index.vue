<script setup>
    /* Core */
    import { ref, reactive, provide, watch, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Components */
    import InviteVolunteerModal from './InviteVolunteerModal.vue';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';

    /* Shared Functions - Composables */
    import usePermissions from '@/Composables/usePermissions';

    import { useVolunteersStore } from '@/Stores/useVolunteer.store';

    import { useVolunteerRoleMapper } from '@/Composables/useVolunteerRoleMapper';
    import { useVolunteerStatusMapper } from '@/Composables/useVolunteerStatusMapper';
    import { useFormatDate } from '@/Composables/useFormatDate';

    const volunteerStore = useVolunteersStore();

    /* Componnet Emits */

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
            type: Object,
            default: {}
        },
        volunteerRoleDropdownOptions: {
            type: Array,
            default: () => []
        },
        volunteerStatusDropdownOptions: {
            type: Array,
            default: () => []
        }
    });
    const { getRoleName } = useVolunteerRoleMapper( props.volunteerRoleDropdownOptions );
    const { getStatusName, getStatusDecoration } = useVolunteerStatusMapper( props.volunteerStatusDropdownOptions );
    const { formatDate } = useFormatDate( );

    // Check if the user has the permission to view the page
    const { hasPermission } = usePermissions( props.auth.user );

    // Used to provide the status options into the datatable component that render the status column.
    provide('volunteerStatusDropdownOptions', props.volunteerStatusDropdownOptions);

    /* Component Reactive Variables */
    const selectVolunteer           = ref( null );              // Used for delete confirmation
    const deleteVolunteerDialog     = ref( false );             // Used for delete confirmation
    const volunteersTableRef        = ref( null );              // Used for exportCSV
    const filterVolunteersTable     = ref( props.filters );     // Used for filtering the table
    
    /* Component Methods */

    // Export the volunteers table to CSV
    const exportVolunteersAsCSV = () => {
        volunteersTableRef.value.exportCSV();
    };

    // Navigate to the edit page of a volunteer
    const editEntity = ( volunteer ) => {    
        router.visit(`/volunteers/${volunteer.id}/edit`);
    };

    // Popup a dialog to confirm the deletion of a volunteer
    const confirmDeleteVolunteer = ( volunteer ) => {
        selectVolunteer.value = volunteer;
        deleteVolunteerDialog.value = true;
    };

    // Delete the volunteer
    const deleteVolunteer = () => {
        if( !selectVolunteer.value.id || selectVolunteer.value.id <= 0 ) return;

        router.delete(`/volunteers/${selectVolunteer.value.id}`);

        deleteVolunteerDialog.value = false;
        selectVolunteer.value = null;
    };

    const pageActionButtons = reactive([
        {
            link: '/volunteers/create',
            svgPath: ['M12 6v6m0 0v6m0-6h6m-6 0H6'],
            label: 'Προσθήκη Εθελοντή',
            visible: hasPermission('create-volunteer')
        },
        {
            link: '',
            click: () => showInviteVolunteerModal.value = true,
            svgPath: ['M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75'],
            label: 'Αποστολή πρόσκλησης με email',
            visible: hasPermission('invite-volunteer')
        },
        {
            link: '/volunteer-settings',
            svgPath: ['M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            label: 'Ρυθμίσεις',
            visible: hasPermission('view-volunteer-settings')
        }
    ]);

    const filters = reactive( volunteerStore.getTableFilters );

    const showInviteVolunteerModal = ref(false);

    /* Watch for changes in the filters */
    watch(() => volunteerStore.getTableFilters, () => {
        router.get('/volunteers/', volunteerStore.getTableFilters, { preserveState: true, replace: true });
    }, { deep: true });

    // -------------------------------------------------------------
    // FUNCTIONS
    // -------------------------------------------------------------

    // Execute an action on a table row
    function executeTableAction( event, row ) {
        let volunteerId = row.id;

        switch( event ) {
            case 'view':
                Inertia.get('/volunteers/' + volunteerId);
                break;
            case 'edit':
                Inertia.get('/volunteers/' + volunteerId + '/edit');
                break;
            case 'delete':
                Inertia.delete('/volunteers/' + volunteerId, {
                    onBefore: () => confirm('Επιβεβαίωση διαγραφής?'),
                } );
                break;
            default:
                break;
        }
    }

    /* Filter volunteers by ROLE */
    function filterVolunteersByRole( event ) {
        volunteerStore.setTableFilterByKey('role', event.target.value);
    }

    /* Filter volunteers by STATUS */
    function filterVolunteersByStatus( event ) {
        volunteerStore.setTableFilterByKey('status', event.target.value);
    }

    /* Filter volunteers by SEARCH TERM (can be: firstname, lastname, email, phone) */
    function searchVolunteers( searchTerm ) {
        volunteerStore.setTableFilterByKey('search', searchTerm);
    }

    /* Filter volunteers by PAEG NUMBER */
    function navigateToPage( pageNo ) {
        volunteerStore.setTableFilterByKey('page', pageNo);
    }

    function clearFilters() {
        volunteerStore.resetTableFilters();
    }

    // -------------------------------------------------------------
    // HOOKS
    // -------------------------------------------------------------
    onMounted(() => {
        volunteerStore.setRoleDropdownOptions( props.volunteerRoleDropdownOptions);
        volunteerStore.setStatusDropdownOptions( props.volunteerStatusDropdownOptions );
    });
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

            <div class="flex flex-column md:flex-row align-items-start md:align-items-start mb-3">
                <BaseDropdownInput
                    v-model="filters.role"
                    placeholder="Όλοι οι Ρόλοι"
                    :options="volunteerStore.getRoleDropdownOptions"
                    class="mx-1"
                />

                <BaseDropdownInput
                    v-model="filters.status"
                    placeholder="Όλες οι Καταστάσεις"
                    :options="volunteerStore.getStatusDropdownOptions"
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
                    <InputText type="text" v-model="filters.search" placeholder="Search" :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>
                <Button icon="pi pi-upload" class="mx-3 export-target-button" rounded v-tooltip="'Export'" @click="exportVolunteersAsCSV"></Button>
            </div>

            <DataTable ref="volunteersTableRef" :value="volunteers.data" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterVolunteersTable">
                <template #empty>Δεν βρέθηκαν εθελοντές.</template>
                
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

                <Column field="role" header="Ρόλος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Ρόλος</span>
                        {{ getRoleName(data.role) }}
                    </template>
                </Column>

                <Column field="status" header="Κατάσταση" sortable :headerStyle="{ minWidth: '12rem' }">
                    <!-- <template #body="{ data }">
                        <span class="p-column-title">Κατάσταση</span>
                        {{  }}
                    </template> -->

                    <template #body="{ data }">
                        <Tag :class="getStatusDecoration">{{ getStatusName(data.status) }}</Tag>
                    </template>
                </Column>

                <Column field="email" header="Email" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Email</span>
                        {{ data.email }}
                    </template>
                </Column>

                <Column field="phone" header="Τηλέφωνο" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Τηλέφωνο</span>
                        {{ data.phone }}
                    </template>
                </Column>

                <Column field="created_at" header="Ημ/νια Έναρξης" sortable :headerStyle="{ minWidth: '12rem' }" :frozen="true">
                    <template #body="{ data }">
                        <span class="p-column-title">Ημ/νια Έναρξης</span>
                        {{ formatDate(data.created_at) }}
                    </template>
                </Column>
 
                <Column headerStyle="min-width:10rem;" frozen alignFrozen="right">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editEntity(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteVolunteer(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteVolunteerDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectVolunteer">
                Είστε σίγουροι οτι θέλετε να διαγράψετε τον εθελοντή`` <b>{{ selectVolunteer.firstname }} {{ selectVolunteer.lastname }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteVolunteerDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteVolunteer" />
        </template>
    </Dialog>
</template>

