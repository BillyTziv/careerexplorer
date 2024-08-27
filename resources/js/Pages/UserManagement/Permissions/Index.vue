<script setup>
    import { ref, reactive, watch, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Components */
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    import { usePermissionsStore } from '@/Stores/usePermission.store';

    // import { useVolunteerRoleMapper } from '@/Composables/useVolunteerRoleMapper';
    // import { useVolunteerStatusMapper } from '@/Composables/useVolunteerStatusMapper';
    // import { useFormatDate } from '@/Composables/useFormatDate';

    const permissionsStore = usePermissionsStore();

    let props = defineProps({
        user: Object,
        permissions: {
            type: Array,
            default: () => [],
        },
        permissionCategories: {
            type: Array,
            default: () => [],
        },
        filters: Object,
        response: Object,
    });

    const selectedPermission = ref(null);
    const deletePermissionDialog = ref(false);
    const permissionsTableRef = ref(null);
    const filterPermissionsTable = ref(props.filters);

    const editPermission = ( permission ) => {    
        router.visit(`/permissions/${permission.id}/edit`);
    };

    const confirmDeletePermission = ( editPermission ) => {
        selectedPermission.value = editPermission;
        deletePermissionDialog.value = true;
    };

    /* Delete Permission */
    const deletePermission = () => {
        router.delete(`/permissions/${selectedPermission.value.id}`);

        deletePermissionDialog.value = false;
        selectedPermission.value = {};
    
        // toast.add({ severity: 'success', summary: 'Successful', detail: 'Ο χ Deleted', life: 3000 });
    };

    /* Table Configuration */
    const size = ref({ label: 'Small', value: 'small' });

    /* Get the filters from the store */
    const filters = reactive( permissionsStore.getTableFilters );

    /* Watch for changes in the filters */
    watch(() => permissionsStore.getTableFilters, () => {
        router.get('/permissions/', permissionsStore.getTableFilters, { preserveState: true, replace: true });
    }, { deep: true });

    /* Clear Filters */
    function clearFilters() {
        permissionsStore.resetTableFilters();
    }

    /* Export Table Data */
    const exportPermissionsAsCSV = () => {
        permissionsTableRef.value.exportCSV();
    };

    // -------------------------------------------------------------
    // HOOKS
    // -------------------------------------------------------------
    onMounted(() => {
        permissionsStore.setCategoryDropdownOptions( props.permissionCategories);
    });

    const redirectToCreate = () => {    
        router.visit(`/permissions/create`);
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Δικαιώματα Χρηστών
        </template>

        <template #page-content>
            <div class="flex flex-column md:flex-row align-items-start md:align-items-start mb-3 w-full">
                <BaseDropdownInput
                    v-model="filters.category"
                    placeholder="Κατηγορία"
                    :options="permissionsStore.getCategoryDropdownOptions"
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

                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportPermissionsAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

            <DataTable
                dataKey="id"
                ref="permissionsTableRef"
                :value="permissions"
                v-model:filters="filterPermissionsTable"
                :filters="filters"
                :size="size.value"
                stripedRows
                responsiveLayout="scroll"
                paginator
                :rows="5"
                :rowsPerPageOptions="[5, 10, 20, 50]"
            >
                <template #empty>Δεν βρέθηκαν δικαιώματα.</template>

                <Column field="name" header="Όνομα Δικαιώματος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα Δικαιώματος</span>
                        {{ data.name }}
                    </template>
                </Column>

                <Column field="name" header="Κατηγορία" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Κατηγορία</span>
                        {{ data.entity?.toLowerCase()
                            .split('_')
                            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                            .join(' ') 
                        }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editPermission(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeletePermission(slotProps.data)" />
                    </template>
                </Column>

                <template #paginatorstart>
                    <!-- <Dropdown v-model="size" :options="sizeOptions" optionLabel="label" dataKey="label"  class="w-full md:w-15rem" @change="onSortChange($event)" /> -->
                </template>

                <template #paginatorend>
                    Εμφάνιση υπάρχουν {{ props.permissions ? props.permissions.length : 0 }} δικαιώματα.
                </template>
            </DataTable>
        </template>
    </AppPageWrapper>

    <Dialog v-model:visible="deletePermissionDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Διαγραφής Χρήστη" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span v-if="selectedPermission">
                Είστε σίγουροι οτι θέλετε να διαγράψετε το δικαίωμα <b>{{ selectedPermission.name }}</b>?
            </span
            >
        </div>
        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="deletePermissionDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="deletePermission" />
        </template>
    </Dialog>
</template>

