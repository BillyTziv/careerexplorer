<template>
    <CustomNavbar :user="user">

        <template #page-header>
            Αιτήσεις
        </template>

        <template #page-actions>
            <Notification2 v-if="$page.props.response.status" :response="$page.props.response" />

            <PageHeaderActionButton>
                <!-- Add application -->
               <BaseLinkButton
                    :link="'/applications/create'"
                    :svg-path="['M12 6v6m0 0v6m0-6h6m-6 0H6']"
                    :label="'Αίτησης'"
                />

                <!-- Invite application by Email -->
                <!-- <BaseClickButton
                    v-if="hasPermission('invite-application')"
                    @click="applicationOptions = !applicationOptions"
                    :svg-path="['M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75']"
                    :label="'Πρόσκληση'"
                /> -->

                <!-- application Settings -->
                <!-- <BaseLinkButton
                    v-if="hasPermission('view-application-settings')"
                    :link="'/application-settings'"
                    :svg-path="['M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z']"
                    :label="'Ρυθμίσεις'"
                /> -->
            </PageHeaderActionButton>
        </template>

        <template #main>
            <section v-show="applicationOptions">
                <!-- <div class="grid grid-cols-2 mb-4 w-full bg-gray-50 rounded-t-md border-b border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                    <ul class="flex text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                        <li>
                            <span class="inline-block p-2 px-3 w-full text-gray-800 bg-gray-100 border-r border-gray-200 dark:text-white dark:bg-gray-800 dark:border-gray-600">
                                <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                            </span>
                        </li>
                        <li>
                            <span class="inline-block p-2 px-3 w-full text-gray-800 border-r border-gray-200 dark:text-white dark:bg-gray-800 dark:border-gray-600">
                                <span id="applicationLink">
                                    {{ applicationLink }}
                                </span>
                            </span>
                        </li>
                    </ul>
                    <div class="flex justify-end">
                        <button @click="copyApplicationLinkToClipboard()" data-tooltip-target="input-field-sizes-example-copy-clipboard-tooltip" data-tooltip-placement="bottom" type="button" data-copy-state="copy" class="flex items-center py-2 px-3 text-xs font-medium text-gray-600 bg-gray-100 border-l border-gray-200 dark:border-gray-600 dark:text-gray-400 dark:bg-gray-800 hover:text-lime-700 dark:hover:text-white copy-to-clipboard-button">
                            <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                            </svg>
                            <span class="copy-text pl-2">Copy</span>
                                            </button>
                    </div>
                </div> -->                                     
            </section>
           
            <!-- // && isPermissionsReady -->
            <BaseDatatable v-if="tableColumns && tableActions && applications" 
                :table-columns="tableColumns"
                :table-actions="tableActions"
                :table-data="applications"
                :table-filters="filters"

                :enableSearch="false"
                :enablePagination="true"
                :enableFilters="false"

                :enable-row-link="true"

                @search-table="searchApplications"
                @on-action="executeTableAction"
                @pagination-triggered="navigateToPage"
            ></BaseDatatable>
        </template>

    </CustomNavbar>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { ref, reactive } from 'vue';

    import BaseDatatable from '../Common/Datatable/BaseDatatable.vue';
    import Notification2 from '@/Pages/Common/Notification2.vue';

    /* Layouts */
    import PageHeaderActionButton from '../../Components/Layouts/PageHeaderActionButton.vue';
    import CustomNavbar from '@/Pages/Common/CustomNavbar.vue';

    /* Custom Components */
    import BaseLinkButton   from '@/Pages/Common/UI/Buttons/BaseLinkButton.vue';

    /* Shared Functions - Composables */
    import usePermissions from '@/Composables/usePermissions';


    let props = defineProps({
        user: Object,                       // Loggedin user.
        response: Object,                   // Backend response.
        applications: Object,               // Datatable data.
        filters: Object,                    // Datatable filters
    });
    
    const { hasPermission } = usePermissions(props.user);

    let invitationEmail = ref(null);
    let showFilters = ref(true);
    let applicationOptions = ref(false);
    let tableActions = [];
    let filters = reactive( props.filters );

    // Used to pass the columns in the datatable component.
    let tableColumns = [
        { id: 1, label: 'A/A', data: 'id', type: 'number' },
        { id: 2, label: 'Ημ/ΝΙΑ ΑΙΤΗΣΗΣ', data: 'created_at', type: 'date' },
    ];

    function executeTableAction( event, row ) {
        switch( event ) {
            case 'view':
                Inertia.get('/applications/' + row.id);
                break;
            case 'edit':
                Inertia.get('/applications/' + row.id + '/edit');
                break;
            case 'delete':
                Inertia.delete('/applications/' + row.id, {
                    onBefore: () => confirm('Επιβεβαίωση διαγραφής?'),
                } );
                break;
            default:
                break;
        }
    }

    function navigateToPage( pageNo ) {
        if (pageNo) filters.page = pageNo;

        Inertia.get('/applications/', filters, { preserveState: true, replace: true });
    }

    // -------------------------------------------------------------
    // TABLE FILTERS
    // -------------------------------------------------------------
    function filterTableByEvent( event ) {
        filters.page = 1;
        Inertia.get('/applications/', filters, {preserveState: true, replace: true });
    }

    function searchapplications( searchTerm ) {
        //filters.page = 1;
        //Inertia.get('/applications/', { search: searchTerm }, { preserveState: true, replace: true });
    }
</script>