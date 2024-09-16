<script setup>
    import { ref, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';

    import PublicPageLayout from '@/Pages/Static/PublicPageLayout.vue';
    import PublicMenu from '@/Pages/Static/PublicMenu.vue';

    const props = defineProps({
            careers: {
                type: Object,
                required: true
            },
            filters: Object,
            response: Object,
        });

    const dataviewValue = ref(null);
    const layout = ref('list');
    const globalFilterValue = ref('');
    const filteredValue = ref(null);
    const sortOrder = ref(null);
    const sortField = ref(null);

    onMounted(() => {
        dataviewValue.value = props.careers;
    });

    const onFilter = (e) => {
        const value = e.target.value;
        globalFilterValue.value = value;

        if (value.length === 0) {
            filteredValue.value = null;
        } else {
            const filtered = dataviewValue.value.filter((product) => {
                return product.name.toLowerCase().includes(value.toLowerCase());
            });
            filteredValue.value = filtered;
        }
    };

    const getHollandCodeColor = ( hollandCode ) => {
        switch ( hollandCode ) {
            case 'R':
                return '#7F1D1D';

            case 'I':
                return '#15803D';

            case 'A':
                return '#EC4899';

            case 'S':
                return '#FACC15';

            case 'E':
                return '#F97316';

            case 'C':
                return '#6B7280'; 

            default:
                return '#323232';
        }
    };

    const popularCareerColor = ( career ) => {
        if( career.is_popular ) {
            return '#ec4955';
        }
        return '#e3e3e3';
    };

    function viewCareer( route ) {
        router.visit(route);
    }
</script>

<template>
    <PublicPageLayout>
        <PublicMenu />
    
        <div class="py-4 px-4 mx-0 md:mx-6 lg:mx-8 lg:px-8 z-2">
            <div id="personality-summary" class="mb-6 pb-6 md:my-8 md:py-8">
                <span class="text-900 block font-bold text-2xl sm:text-5xl mb-4 text-center">
                    Ανακάλυψε πως είναι να είσαι..
                </span>

                <div class="flex justify-content-center flex-wrap">
                    <div class="flex align-items-center justify-content-center border-round m-2">
                        <IconField iconPosition="left" >
                        <InputIcon class="pi pi-search" />
                            <InputText 
                                v-model="globalFilterValue" 
                                rounded 
                                @input="onFilter"
                                placeholder="πχ. προγραμματιστής, γραφίστας, μηχανικός.."
                                class="w-full border-round text-lg border font-bold text-primary"
                                style="border-color: #6366F1; border-radius: 50px !important;"
                            />
                        </IconField>
                    </div>
                </div>

                <!-- Search Results -->
                <DataView
                    :value="filteredValue || dataviewValue" 
                    :layout="layout" 
                    :paginator="true" 
                    :rows="9"
                    :sortOrder="sortOrder" 
                    :sortField="sortField"
                >
                    <template #list="slotProps">
                        <ul class="list-none p-0 m-0">
                            <li v-for="(item, index) in slotProps.items" :key="index" class="p-3 mb-3 shadow-2 border-round m-2 bg-white">
                                <div class="flex justify-content-between md:align-items-center flex-1 gap-0">
                                    <!-- Popular Icon-->
                                    <div class="flex align-items-center md:w-2rem relative mr-3">
                                        <svg :fill="popularCareerColor( item )" height="22px" width="22px" version="1.1" id="Capa_1" viewBox="0 0 611.999 611.999"
                                            xml:space="preserve">
                                            <g>
                                                <path
                                                    d="M216.02,611.195c5.978,3.178,12.284-3.704,8.624-9.4c-19.866-30.919-38.678-82.947-8.706-149.952   c49.982-111.737,80.396-169.609,80.396-169.609s16.177,67.536,60.029,127.585c42.205,57.793,65.306,130.478,28.064,191.029   c-3.495,5.683,2.668,12.388,8.607,9.349c46.1-23.582,97.806-70.885,103.64-165.017c2.151-28.764-1.075-69.034-17.206-119.851   c-20.741-64.406-46.239-94.459-60.992-107.365c-4.413-3.861-11.276-0.439-10.914,5.413c4.299,69.494-21.845,87.129-36.726,47.386   c-5.943-15.874-9.409-43.33-9.409-76.766c0-55.665-16.15-112.967-51.755-159.531c-9.259-12.109-20.093-23.424-32.523-33.073   c-4.5-3.494-11.023,0.018-10.611,5.7c2.734,37.736,0.257,145.885-94.624,275.089c-86.029,119.851-52.693,211.896-40.864,236.826   C153.666,566.767,185.212,594.814,216.02,611.195z"
                                                />
                                            </g>
                                        </svg>
                                    </div>

                                    <!-- Career Title -->
                                    <div
                                        class="flex flex-row justify-content-start align-items-center gap-0 flex-1">

                                        <div>
                                            <div>
                                                <template v-for="(code, index) in item.riasec_codes" :key="index">
                                                    <Tag :value="''" :style="{ 
                                                            backgroundColor: getHollandCodeColor(code), 
                                                            width: '10px', 
                                                            height: '11px', 
                                                            margin: '1px' 
                                                        }" :rounded="true"></Tag>
                                                </template>
                                            </div>
                                            <div class="text-lg font-medium text-900 ">{{ item.title }}</div>
                                        </div>
                                    </div>

                                    <!-- View Button -->
                                    <div class="flex flex-column md:align-items-end gap-0 justify-end">
                                        <Button @click="viewCareer( route('singleCareer', item.id) )"
                                            icon="pi pi-arrow-right" label="" iconPos="right" text :rounded="true"
                                            class="flex-auto md:flex-initial white-space-nowrap" />
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </template>
                </DataView>
            </div>
        </div>
    </PublicPageLayout>
</template>

<style scoped>
    .bg-circle {
        width: 1000px;
        height: 1000px;
        border-radius: 50%;
        background-image: linear-gradient(140deg, var(--primary-color), var(--surface-ground) 80%);
        position: absolute;
        opacity: 0.25;
        z-index: -1;
    }

    .p-dataview >>> .p-dataview-content {
        background: transparent;
    }
</style>