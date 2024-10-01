<script setup>
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import { useFormatDate } from '@/Composables/useFormatDate';

    /* Component Properties */
    let props = defineProps({
        user: Object,
        testTemplates: {
            type: Object,
            required: true,
        }, 
        filters: Object,
        response: Object,
    });

    const { formatDate } = useFormatDate( );

    /* Component Reactive Variables */
    const testTemplateTableRef        = ref( null );              // Used for exportCSV
    const searchFilter                  = ref( "" );
    const filtertestTemplatesTable    = ref( props.filters );     // Used for filtering the table

    const viewTestTemplate = ( testTemplate ) => {    
        router.visit(`/test-templates/${testTemplate.id}`);
    };

    watch(() => searchFilter, () => {
        router.get('/test-templates/', { search: searchFilter.value }, { preserveState: true, replace: true });
    }, { deep: true });
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Πρότυπα Τεστ
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <!-- <InputText type="text" v-model="searchFilter" placeholder="Αναζήτηση.." :style="{ borderRadius: '2rem' }" class="w-full" /> -->
                </IconField>
                
                <div class="flex">
                    <!-- <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportTableDataAsCSV"></Button> -->
                    <!-- <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" /> -->
                </div>
            </div>

            <!-- <DataTable ref="testTemplateTableRef" :value="testTemplates" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filtertestTemplatesTable">
                <template #empty>Δεν βρέθηκαν καταχωρήσεις.</template>
                
                <Column field="title" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα</span>
                        {{ data.user_firstname }}
                    </template>
                </Column>

                <Column field="title" header="Επίθετο" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Επίθετο</span>
                        {{ data.user_lastname }}
                    </template>
                </Column>

                <Column field="title" header="Πρότυπο Test" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Πρότυπο Test</span>
                        {{ data.test_template_name }}
                    </template>
                </Column>

                <Column field="title" header="Ημ/νία Καταχώρησης" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Ημ/νία Καταχώρησης</span>
                        {{ formatDate(data.created_at, true) }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-eye" class="mr-2" rounded outlined @click="viewTestTemplate(slotProps.data)" />
                    </template>
                </Column>
            </DataTable> -->
        </template>
    </AppPageWrapper>
</template>

