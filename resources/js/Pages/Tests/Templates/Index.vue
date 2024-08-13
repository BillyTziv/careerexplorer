<template>
    <CustomNavbar :user="user">
        <template #page-title>
            Πρότυπα Τεστ
        </template>

        <template #page-actions>
            <!-- Add Test Template -->
            <BaseLinkButton
                :link="'/tests/create'"
                :svg-path="['M12 6v6m0 0v6m0-6h6m-6 0H6']"
                :label="'Προτύπου Test'"
            />
        </template>

        <template #page-content>
            <BaseDatatable v-if="tableColumns && tableActions && tests"
                :table-columns="tableColumns"
                :table-actions="tableActions"
                :table-data="tests"
                :table-filters="filters"
                :enableSearch="true"
                :enablePagination="true"
                :enableFilters="false"
                @search-table="searchTests"
                @on-action="executeTableAction"
            ></BaseDatatable> 
        </template>
    </CustomNavbar>
</template>

<script setup>
    import { Link } from '@inertiajs/inertia-vue3';
    import { Inertia } from '@inertiajs/inertia';

    import CustomNavbar from '../../Common/CustomNavbar.vue';
    import BaseDatatable from '../../Common/Datatable/BaseDatatable.vue';
    /* Custom Components */
    import BaseLinkButton   from '@/Pages/Common/UI/Buttons/BaseLinkButton.vue';
    let props = defineProps({
        user: Object,
        response: Object,
        testCategories: Object,
        testTypes: Object,
        tests: Object,
        filters: Object,
        templateDropdownList: Object,
        testTemplate: Object,
    });

    let tableActions = [
        {
            id: 2, 
            label: 'Προβολή', 
            icon: '',
            type: 'view',
            class: 'px-3 py-1.5 mx-1 text-gray-700 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'
        }
    ];

    // Used to pass the columns in the datatable component.
    let tableColumns = [
        { id: 1, label: 'ΟΝΟΜΑ', data: 'name', type: 'string' },
        { id: 2, label: 'ΗΜ/ΝΙΑ', data: 'created_at', type: 'date' },
    ];

    function executeTableAction( event, row ) {
        switch( event ) {
            case 'view':
                Inertia.get('/tests/' + row.id)
                break;
            case 'edit':
                Inertia.get('/tests/' + row.id + '/update')
                break;
            case 'run':
                Inertia.get('/submissions/' + row.id + '/start/')
                break;
            case 'delete':
                Inertia.delete('/tests/' + testId, {
                    onBefore: () => confirm('Είστε σίγουροι πως θέλετε να διαγράψετε το πρότυπο?'),
                } )
                break;
            default:
                break;
        }
    }

    function searchTests( searchTerm ) {
        Inertia.get('/tests/', { search: searchTerm }, { preserveState: true, replace: true });
    }

    function filterTests() {

    }
</script>
