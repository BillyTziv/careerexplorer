<script setup>
    import { computed, watch, ref, onBeforeUnmount } from 'vue';
    import { usePrimeVue } from 'primevue/config';
    import AppTopbar from '@/Layouts/AppTopbar.vue';
    import AppSidebar from '@/Layouts/AppSidebar.vue';
    import AppConfig from '@/Layouts/AppConfig.vue';
    import AppProfileSidebar from '@/Layouts/AppProfileSidebar.vue';
    // import AppBreadCrumb from '@/Layouts/AppBreadcrumb.vue';
    import { useLayout } from '@/Layouts/composables/layout';
    import { onMounted } from 'vue';
    
    import { FilterMatchMode } from 'primevue/api';

    let props = defineProps({
        user: Object,
        users: Object, 
        roleDropdownOptions: Array,
        filters: Object,
        response: Object,
    });

    const $primevue = usePrimeVue();
    const { layoutConfig, layoutState, isSidebarActive } = useLayout();
    const outsideClickListener = ref(null);
    const sidebarRef = ref(null);
    const topbarRef = ref(null);

    watch(isSidebarActive, (newVal) => {
        if (newVal) {
            bindOutsideClickListener();
        } else {
            unbindOutsideClickListener();
        }
    });

    onBeforeUnmount(() => {
        unbindOutsideClickListener();
    });

    const containerClass = computed(() => {
        return {
            'layout-light': layoutConfig.colorScheme.value === 'light',
            'layout-dim': layoutConfig.colorScheme.value === 'dim',
            'layout-dark': layoutConfig.colorScheme.value === 'dark',
            'layout-colorscheme-menu': layoutConfig.menuTheme.value === 'colorScheme',
            'layout-primarycolor-menu': layoutConfig.menuTheme.value === 'primaryColor',
            'layout-transparent-menu': layoutConfig.menuTheme.value === 'transparent',
            'layout-overlay': layoutConfig.menuMode.value === 'overlay',
            'layout-static': layoutConfig.menuMode.value === 'static',
            'layout-slim': layoutConfig.menuMode.value === 'slim',
            'layout-slim-plus': layoutConfig.menuMode.value === 'slim-plus',
            'layout-horizontal': layoutConfig.menuMode.value === 'horizontal',
            'layout-reveal': layoutConfig.menuMode.value === 'reveal',
            'layout-drawer': layoutConfig.menuMode.value === 'drawer',
            'layout-static-inactive': layoutState.staticMenuDesktopInactive.value && layoutConfig.menuMode.value === 'static',
            'layout-overlay-active': layoutState.overlayMenuActive.value,
            'layout-mobile-active': layoutState.staticMenuMobileActive.value,
            'p-ripple-disabled': $primevue.config.ripple === false,
            'layout-sidebar-active': layoutState.sidebarActive.value,
            'layout-sidebar-anchored': layoutState.anchored.value
        };
    });
                            
    const bindOutsideClickListener = () => {
        if (!outsideClickListener.value) {
            outsideClickListener.value = (event) => {
                if (isOutsideClicked(event)) {
                    layoutState.overlayMenuActive.value = false;
                    layoutState.overlaySubmenuActive.value = false;
                    layoutState.staticMenuMobileActive.value = false;
                    layoutState.menuHoverActive.value = false;
                }
            };
            document.addEventListener('click', outsideClickListener.value);
        }
    };
    const unbindOutsideClickListener = () => {
        if (outsideClickListener.value) {
            document.removeEventListener('click', outsideClickListener);
            outsideClickListener.value = null;
        }
    };
    const isOutsideClicked = (event) => {
        const sidebarEl = sidebarRef?.value.$el;
        const topbarEl = topbarRef?.value.$el.querySelector('.topbar-menubutton');

        return !(sidebarEl.isSameNode(event.target) || sidebarEl.contains(event.target) || topbarEl.isSameNode(event.target) || topbarEl.contains(event.target));
    };


    const knobValue = ref(90);
const weeks = ref([
    {
        label: 'Last Week',
        value: 0,
        data: [
            [65, 59, 80, 81, 56, 55, 40],
            [28, 48, 40, 19, 86, 27, 90]
        ]
    },
    {
        label: 'This Week',
        value: 1,
        data: [
            [35, 19, 40, 61, 16, 55, 30],
            [48, 78, 10, 29, 76, 77, 10]
        ]
    }
]);
const selectedWeek = ref(weeks.value[0]);
const pieOptions = ref({});
const barOptions = ref({});
const barData = ref({});
const pieData = ref({});
const salesTableRef = ref(null);
const filterSalesTable = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
onMounted(() => {
    selectedWeek.value = weeks.value[0];
});

const exportCSV = () => {
    salesTableRef.value.exportCSV();
};
</script>

<template>
    <div :class="['layout-container', { ...containerClass }]">
        <AppSidebar ref="sidebarRef" />

        <div class="layout-content-wrapper">
            <AppTopbar ref="topbarRef" />
            <!-- <AppBreadCrumb class="content-breadcrumb"></AppBreadCrumb> -->
            
            <div class="col-12 lg:col-12">
                <div class="card">
                    <div class="flex flex-column md:flex-row md:align-items-start md:justify-content-between mb-3">
                        <div class="text-900 text-xl font-semibold mb-3 md:mb-0">Χρηστες</div>
                        <div class="inline-flex align-items-center">
                            <IconField iconPosition="left">
                                <InputIcon class="pi pi-search" />
                                <InputText type="text" v-model="filterSalesTable.global.value" placeholder="Search" :style="{ borderRadius: '2rem' }" class="w-full" />
                            </IconField>
                            <Button icon="pi pi-upload" class="mx-3 export-target-button" rounded v-tooltip="'Export'" @click="exportCSV"></Button>
                        </div>
                    </div>

                    <DataTable ref="salesTableRef" :value="users.data" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterSalesTable">
                        <template #empty>Δεν βρέθηκαν χρήστες.</template>
                        <Column field="firstname" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                            <template #body="{ data }">
                                <span class="p-column-title">Όνομα</span>
                                {{ data.firstname }}
                            </template>
                        </Column>

                        <Column field="lastname" header="Επίθετο" sortable :headerStyle="{ minWidth: '12rem' }">
                            <template #body="{ data }">
                                <span class="p-column-title">Επίθετο</span>
                                {{ data.lastname }}
                            </template>
                        </Column>

                        <Column field="phone" header="Τηλέφωνο" sortable :headerStyle="{ minWidth: '12rem' }">
                            <template #body="{ data }">
                                <span class="p-column-title">Τηλέφωνο</span>
                                {{ data.phone }}
                            </template>
                        </Column>

                        <Column field="email" header="Email" sortable :headerStyle="{ minWidth: '12rem' }">
                            <template #body="{ data }">
                                <span class="p-column-title">Email</span>
                                {{ data.email }}
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <AppProfileSidebar />
        <AppConfig />

        <Toast></Toast>
        <div class="layout-mask"></div>
    </div>
</template>

