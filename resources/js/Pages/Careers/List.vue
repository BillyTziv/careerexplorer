<template>
    <PublicPage :is-dark="false">
        <section class="bg-white dark:bg-white px-2 py-4">
            <!------------------------------------------------------
            | CAREER SEARCH INPUT
            ------------------------------------------------------->
            <CareerSearch
                :searchKeyword="filters.search"
                @on-search="searchCareers"
            />

            <!------------------------------------------------------
            | CAREER FILTERS
            ------------------------------------------------------->
            <CareerListFilters
                :hollandCodeFilters="filters.code"
                @on-filter="handleFilterCareers"
            />

            <!------------------------------------------------------
            | CAREER SEARCH RESULTS INFORMATION
            ------------------------------------------------------->
            <CareerListSearchResult
                :totalResults="careers.total"
            />

            <!------------------------------------------------------
            | CAREER LIST
            ------------------------------------------------------->
            <CareerList
                :careers="careers"
                @navigate-to-page="navigateToPage"
            />
        </section>
    </PublicPage>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { ref, onMounted, watch } from 'vue';

    /* Layouts */
    import PublicPage from '../Common/PublicPage.vue';

    /* Custom Components */
    import CareerList from '../../Components/CareerList/CareerList.vue';
    import CareerListSearchResult from '../../Components/CareerList/CareerListSearchResult.vue';
    import CareerListFilters from '../../Components/CareerList/CareerListFilters.vue';
    import CareerSearch from '../../Components/CareerList/CareerSearch.vue';

    import { useCareerStore } from '@/Stores/useCareer.store';

    const careerStore = useCareerStore();

    const props = defineProps({
        careers: Object,
        filters: Object,
    });
    
    let filters = ref({
        search: props.filters?.search ?? "",
        sort: props.filters?.sort ?? 'desc',
        code: props.filters?.hollandCode ?? 'riasec',
        page: props.filters?.page ?? 1,
    });

    onMounted(() => {
        let codeFilters = careerStore.getSearchFilters;
        let params = new URLSearchParams(window.location.search);
        let hcode = params.get('code');

        if( hcode ) {
            filters.value.code = hcode.toUpperCase();
        }else {
            filters.code = codeFilters;
        }
    });

    function searchCareers( searchTerm ) {
        filters.value.search = searchTerm;
        Inertia.get('/careers/list/', filters.value, { preserveState: true, replace: true });
    }

    function handleFilterCareers( selectedHollandCode ) {
        filters.value.code = selectedHollandCode;
        Inertia.get('/careers/list/', filters.value, { preserveState: true, replace: true });
    }

    function navigateToPage( pageNo ) {
        filters.value.page = pageNo;
        Inertia.get('/careers/list/', filters.value, { preserveState: true, replace: true });
    }
</script>