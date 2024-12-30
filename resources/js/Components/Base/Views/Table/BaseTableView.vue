<script setup>
import { ref, computed } from 'vue';

/* Components */
import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';

const emit = defineEmits([
    'action'
]);

/* Props */
const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    columns: {
        type: Array,
        required: true
    },
    filters: {
        type: Object,
        default: () => null
    }
});

const searchQuery = ref('');
const selectedRows = ref([]);
const size = ref("10");
const tableRef = ref(null);

const computedFilters = computed(() => {
    const filters = {
        global: {
            value: searchQuery.value,
            matchMode: 'contains'
        }
    };
    if (props.filters) {
        if (props.filters.selectedValue) {
            filters[props.filters.field] = {
                value: props.filters.selectedValue,
                matchMode: 'equals'
            };
        }
    }
    return filters;
});

// const clearFilters = () => {
//     searchQuery.value = '';
//     if (props.filters) {
//         props.filters.selectedValue = null;
//     }
// };

const formatDate = (date) => {
  if (!date) return '---';
  const formattedDate = new Date(date);
  return formattedDate.toLocaleDateString('en-GB');
};

const renderColumn = (data, field) => {
    if (field === 'created_at' || field === 'due_date' || field === 'completed_date') {
        return formatDate(data[field]);
    }
    return data[field] || '---';
};

const getTagLabel = (tag) => {
    return tag.name;
};

const getTagStyle = (tag) => {
    return {
        backgroundColor: tag.hex_color,
        borderRadius: '0.80rem',
        padding: '0.5rem 0.7rem',
        'margin-right': '0.5rem',
    };
};

const exportTableDataAsCSV = () => {
    if (tableRef.value) {
        tableRef.value.exportCSV();
    }
};

const onViewClick = (data) => {
    emit('action', { type: 'view', data });
};

const onCreateClick = () => {
    emit('action', { type: 'create' });
};

const onEditClick = (data) => {
    emit('action', { type: 'edit', data });
};

const onDeleteClick = (data) => {
    emit('action', { type: 'delete', data });
};
</script>

<template>
    <div class="p-4">
        <!-- Action Buttons -->
        <div class="flex flex-column md:flex-row align-items-start md:align-items-start mb-3 w-full">
            <!-- <BaseDropdownInput
                v-model="filters.volunteer"
                placeholder="Όλοι οι εθελοντές"
                :options="taskStore.getVolunteerDropdownOptions"
                :narrow="true"
                class="mx-1"
            />

            <BaseDropdownInput
                v-model="filters.status"
                placeholder="Όλες οι Καταστάσεις"
                :options="taskStore.getStatusDropdownOptions"
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
            /> -->
        </div>

        <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
            <IconField iconPosition="left">
                <InputIcon class="pi pi-search" />
                <InputText v-model="filters.search" type="text"  placeholder="Αναζήτηση.." :style="{ borderRadius: '2rem' }" class="w-full" />
            </IconField>

            <div class="flex">
                <Button @click="exportTableDataAsCSV" type="button" icon="pi pi-download" text rounded v-tooltip="'Εξαγωγή Δεδομένων'" />
                <Button @click="onCreateClick" type="button" icon="pi pi-plus" rounded v-tooltip="'Δημιουργίας Νέας Εγγραφής'" />
            </div>
        </div>

        <!-- Data Table Component -->
        <DataTable
            ref="tableRef"
            :value="props.data"
            :rows="10"
            paginator
            :size="size"
            :rowsPerPageOptions="[5, 10, 20, 50]"
            :filters="computedFilters"
            :selection="selectedRows"
            v-model:selection="selectedRows"
            responsiveLayout="scroll"
        >
            <template #empty>
                Δεν υπάρχουν δεδομένα.
            </template>

            <!-- Dynamic Columns Definitions -->
            <Column
                v-for="col in props.columns"
                :key="col.field"
                :field="col.field"
                :header="col.header"
                :sortable="col.sortable"
                :headerStyle="col.headerStyle"
            >
                <template #body="slotProps">
                    <!-- Render status dynamically -->
                    <span v-if="col.field === 'status'">
                        <span class="flex items-center">
                            <Tag
                                shape="circle"
                                :style="getTagStyle(slotProps.data[col.field])"
                            ></Tag>
                            {{ getTagLabel(slotProps.data[col.field]) }}
                        </span>
                    </span>
                    <span v-else-if="col.field === 'priority'">
                        <!-- Priority is Low, Medium, High-->
                        <Tag v-if="slotProps.data[col.field] == 0" value="Χαμηλή" />
                        <Tag v-else-if="slotProps.data[col.field] == 1" value="Μέτρια" />
                        <Tag v-else value="Υψηλή" />
                    </span>
                    <span v-else>
                        {{ renderColumn(slotProps.data, col.field) }}
                    </span>
                </template>
            </Column>

            <!-- Action Column (Edit, Delete, etc.) -->
            <Column headerStyle="min-width:10rem;">
                <template #body="slotProps">
                    <Button
                        icon="pi pi-eye"
                        class="mr-2"
                        rounded
                        outlined
                        tooltip="Προβολή"
                        @click="onViewClick(slotProps.data)"
                    />

                    <Button
                        icon="pi pi-pencil"
                        class="mr-2"
                        rounded
                        outlined
                        tooltip="Επεξεργασία"
                        @click="onEditClick(slotProps.data)"
                    />
                    <Button
                        icon="pi pi-trash"
                        class="mt-2"
                        rounded
                        outlined
                        severity="danger"
                        tooltip="Διαγραφή"
                        @click="onDeleteClick(slotProps.data)"
                    />
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<style scoped>
    .actions {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .actions-right {
        display: flex;
        gap: 1rem;
    }
</style>
