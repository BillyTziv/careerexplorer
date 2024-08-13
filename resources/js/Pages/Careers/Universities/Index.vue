<template>
    <CustomNavbar :user="user">

        <template #page-title>
            Πανεπιστήμια
        </template>

        <template #page-actions>
            <!-- Add University -->
            <BaseLinkButton
                :link="'/universities/create'"
                :svg-path="['M12 6v6m0 0v6m0-6h6m-6 0H6']"
                :label="'Πανεπιστημίου'"
            />
        </template>

        <template #page-content>
            <BaseDatatable v-if="tableHeaders && tableActions && universities"
                :table-data="universities"
                :table-columns="tableHeaders"
                :table-actions="tableActions"
                :table-filters="filters"

                :enableSearch="true"
                :enablePagination="true"
                :enableFilters="false"

                @search-table="searchTable"
                @on-action="executeTableAction"
            ></BaseDatatable>
        </template>

    </CustomNavbar>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia';

    import BaseDatatable from '../../Common/Datatable/BaseDatatable.vue';
    import CustomNavbar from '../../Common/CustomNavbar.vue';

    /* Custom Components */
    import BaseLinkButton   from '@/Pages/Common/UI/Buttons/BaseLinkButton.vue';
    
    let props = defineProps({
        user: Object,
        universities: Object,
        filters: Object
    });
    
    const tableActions = [];
    const tableHeaders = [
        { id: 1, label: 'ΟΝΟΜΑ', data: 'name', type: 'string' },
        { id: 2, label: 'ΗΜ/ΝΙΑ ΚΑΤΑΧΩΡΗΣΗΣ', data: 'created_at', type: 'date' }
    ];


    function searchTable( searchTerm ) {
        Inertia.get('/universities/', { search: searchTerm }, { preserveState: true, replace: true });
    }

    function filterTable( filters ) {
        // TODO...
    }

    function executeTableAction( event, row ) {
        // TODO...
    }

</script>
