<script setup>
    import { ref, reactive } from 'vue';
    import { router } from '@inertiajs/vue3'

    import usePermissions from '@/Composables/usePermissions';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    let props = defineProps({
        career: Object,
        careers: Object, 
        roleDropdownOptions: Array,
        filters: Object,
        response: Object,
    });
    let filters = reactive( props.filters );
    const { hasPermission } = usePermissions(props.career);

    const deleteCareerDialog = ref(false);
    const selectedCareer = ref(null);
    const CareersTableRef = ref(null);
    const filterCareersTable = ref(props.filters);

    // const pageActionButtons = reactive([
    //     {
    //         link: '/careers/create',
    //         svgPath: ['M12 6v6m0 0v6m0-6h6m-6 0H6'],
    //         label: 'Επαγγέλματος',
    //         visible: hasPermission('create-career')
    //     },
    //     {
    //         link: '/career-experiences/create',
    //         svgPath: ['M12 6v6m0 0v6m0-6h6m-6 0H6'],
    //         label: 'Εργασιακής Εμπειρίας',
    //         visible: hasPermission('create-career')
    //     },
    //     {
    //         link: '/careers/list',
    //         svgPath: ['M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z'],
    //         label: 'Δημόσιας Λίστας',
    //         visible: true
    //     },
    //     {
    //         link: '/career-requests',
    //         svgPath: ['M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
    //         label: 'Στατιστικά',
    //         visible: hasPermission('view-all-career-requests')
    //     },
    //     {
    //         link: '/career-settings',
    //         svgPath: ['M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    //         label: 'Ρυθμίσεις',
    //         visible: hasPermission('view-career-settings')
    //     },
    // ]);

    const exportCSV = () => {
        CareersTableRef.value.exportCSV();
    };

    const editCareer = ( career ) => {    
        router.visit(`/careers/${career.id}/edit`);
    };

    const confirmDeleteCareer = ( editCareer ) => {
        selectedCareer.value = editCareer;
        deleteCareerDialog.value = true;
    };

    const deleteCareer = () => {
        router.delete(`/careers/${selectedCareer.value.id}`)
        deleteCareerDialog.value = false;
        selectedCareer.value = {};
        // careers.value = careers.value.filter((val) => val.id !== career.value.id);
    
        // toast.add({ severity: 'success', summary: 'Successful', detail: 'Ο χ Deleted', life: 3000 });
    };

    function searchCareer( searchTerm ) {
        Inertia.get('/careers', { search: searchTerm }, { preserveState: true, replace: true });
    }
    
    function filterCareer( filters ) {
        // TODO...
    }

    function navigateToPage( pageNo ) {
        if( pageNo ) filters.page = pageNo;

        Inertia.get('/careers/', filters, { preserveState: true, replace: true });
    }

    function executeTableAction( event, row ) {
        let payload = row;

        switch( event ) {
            case 'approve':
                payload.status = 2;
                Inertia.put('/careers/' + row.id, payload, { preserveState: true, replace: true });
                break;
            case 'reject':
                payload.status = 3;
                Inertia.put('/careers/' + row.id, payload, { preserveState: true, replace: true });
                break;
            case 'edit':
                Inertia.get('/careers/' + row.id + '/edit');
                break;
            case 'delete':
                Inertia.delete('/careers/' + row.id, {
                    onBefore: () => confirm('Επιβεβαίωση διαγραφής?'),
                } );
                break;
            default:
                break;
        }
    }

    // Export the careers table to CSV
    const exportCareersAsCSV = () => {
        careersTableRef.value.exportCSV();
    };

    const redirectToCreate = () => {    
        router.visit(`/careers/create`);
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Επαγγέλματα
        </template>

        <template #page-content>
            <!-- <div class="flex flex-column md:flex-row align-items-start md:align-items-start mb-3 w-full">
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
            </div> -->

            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="filters.search" placeholder="Search" :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>
                
                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportCareersAsCSV"></Button>
                    <Button label="Προσθήκη" type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>
                    
            <DataTable ref="careersTableRef" :value="careers.data" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filtercareersTable">
                <template #empty>Δεν βρέθηκαν επαγγέλματα.</template>
                <Column field="name" header="Τίτλος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Τίτλος</span>
                        {{ data.title }}
                    </template>
                </Column>

                <Column field="holland_code" header="Holland Code" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Holland Code</span>
                        {{ data.riasec_codes }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editCareer(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeleteCareer(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deleteCareerDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής Χρήστη" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedCareer">
                Είστε σίγουροι οτι θέλετε να διαγράψετε το επάγγελμα <b>{{ selectedCareer.title }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deleteCareerDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deleteCareer" />
        </template>
    </Dialog>
</template>