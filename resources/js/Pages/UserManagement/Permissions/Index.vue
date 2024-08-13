<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    let props = defineProps({
        user: Object,
        permissions: Object, 
        filters: Object,
        response: Object,
    });

    const selectedPermission = ref(null);
    const deletePermissionDialog = ref(false);
    const permissionsTableRef = ref(null);
    const filterPermissionsTable = ref(props.filters);

    const exportCSV = () => {
        permissionsTableRef.value.exportCSV();
    };

    const editPermission = ( permission ) => {    
        router.visit(`/permissions/${permission.id}/edit`);
    };

    const confirmDeletePermission = ( editPermission ) => {
        selectedPermission.value = editPermission;
        deletePermissionDialog.value = true;
    };

    const deletePermission = () => {
        router.delete(`/permissions/${selectedPermission.value.id}`)
        deletePermissionDialog.value = false;
        selectedPermission.value = {};
        // permissions.value = permissions.value.filter((val) => val.id !== user.value.id);
    
        // toast.add({ severity: 'success', summary: 'Successful', detail: 'Ο χ Deleted', life: 3000 });
    };
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Δικαιώματα
        </template>

        <template #page-content>
            <DataTable ref="salesTableRef" :value="permissions.data" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterpermissionsTable">
                <template #empty>Δεν βρέθηκαν δικαιώματα.</template>
                <Column field="name" header="Όνομα Δικαιώματος" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα Δικαιώματος</span>
                        {{ data.name }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editPermission(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded outlined severity="danger" @click="confirmDeletePermission(slotProps.data)" />
                    </template>
                </Column>
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

