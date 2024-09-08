<script setup>
    import { ref } from 'vue';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Component Properties */
    let props = defineProps({
        user: Object,
        riasecCodes: Object, 
        filters: Object,
        response: Object,
    });

    const riasecCodesTableRef           = ref( null );              // Used for exportCSV
    const filterRiasecCodesTable        = ref( props.filters );     // Used for filtering the table
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Holland Codes
        </template>

        <template #page-content>
            <DataTable ref="riasecCodesTableRef" :value="riasecCodes.data" dataKey="id" responsiveLayout="scroll">
                <template #empty>Δεν βρέθηκαν holland codes.</template>
                
                <Column field="name" header="Όνομα">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα</span>
                        {{ data.name }}
                    </template>
                </Column>

                <Column field="name" header="Κωδικός">
                    <template #body="{ data }">
                        <span class="p-column-title">Κωδικός</span>
                        {{ data.code }}
                    </template>
                </Column>

                <Column field="description" header="Περιγραφή">
                    <template #body="{ data }">
                        <span class="p-column-title">Περιγραφή</span>
                        {{ data.description }}
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>
</template>

