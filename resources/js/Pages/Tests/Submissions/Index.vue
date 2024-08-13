<template>
    <CustomNavbar :user="user">

        <template #page-title>
            Απαντήσεις του Test Holland
        </template>

        <template #page-content>
            <BaseDatatable v-if="tableColumns && tableActions && submissions"
                :table-columns="tableColumns"
                :table-actions="tableActions"
                :table-data="submissions"
                :table-filters="filters"
                
                :enableSearch="true"
                :enablePagination="true"
                :enableFilters="false"

                :enable-row-link="true"

                @search-table="searchSubmissions"
                @on-action="executeTableAction"
                @pagination-triggered="navigateToPage"
            ></BaseDatatable> 
        </template>

    </CustomNavbar>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { reactive } from 'vue';

    /* Custom Components */
    import CustomNavbar from '../../Common/CustomNavbar.vue';
    import BaseDatatable from '../../Common/Datatable/BaseDatatable.vue';

    let props = defineProps({
        user: Object,
        submissions: Object,
        filters: Object,
        response: Object
    });

    let tableActions = [];
    let filters = reactive( props.filters );

    // Used to pass the columns in the datatable component.
    let tableColumns = [
        { id: 1, label: 'ΟΝΟΜΑ', data: 'user_firstname', type: 'string' },
        { id: 2, label: 'ΕΠΙΘΕΤΟ', data: 'user_lastname', type: 'string' },
        { id: 3, label: 'ΗΜ/ΝΙΑ', data: 'created_at', type: 'date' },
    ];

    // -------------------------------------------------------------
    // TABLE ROW ACTIONS
    // -------------------------------------------------------------

    function executeTableAction( event, row ) {
        let submissionId = row.id;

        switch( event ) {
            case 'view':
                Inertia.get('/submissions/' + submissionId);
                break;
            default:
                break;
        }
    }

    // -------------------------------------------------------------
    // TABLE PAGINATION
    // -------------------------------------------------------------

    /* Table Pagination */
    function navigateToPage( pageNo ) {
        if( pageNo ) filters.page = pageNo;

        Inertia.get('/submissions/', filters, { preserveState: true, replace: true });
    }

    // -------------------------------------------------------------
    // TABLE FILTERS
    // -------------------------------------------------------------

    /* Search Filter */
    function searchSubmissions( searchTerm ) {
        Inertia.get('/submissions/', { search: searchTerm }, { preserveState: true, replace: true });
    }
</script>