<script setup>
    import { computed, ref } from 'vue';
    import { usePrimeVue } from 'primevue/config';

    import AppTopbar from '@/Layouts/AppTopbar.vue';
    import AppSidebar from '@/Layouts/AppSidebar.vue';
    import AppConfig from '@/Layouts/AppConfig.vue';
    import AppProfileSidebar from '@/Layouts/AppProfileSidebar.vue';    
    import { useForm } from '@inertiajs/vue3';
    import { useLayout } from '@/Layouts/composables/layout';

    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';

    const sidebarRef = ref(null);
    const topbarRef = ref(null);
    const $primevue = usePrimeVue();

    let props = defineProps({
        user: Object,
        permission: Object,
        errors: Object,
        response: Object
    });

    const permissionForm = useForm({
        id: props.permission.id ? props.permission.id : null,
        name: props.permission.name ? props.permission.name : null,
        code: props.permission.code ? props.permission.code : null,
    })

    const { layoutConfig, layoutState, isSidebarActive } = useLayout();

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

    function setVolunteerRole (role) {
        permissionForm.role = role;
    }
    
    const isEditMode = computed(() => { return permissionForm.id > 0 } );

    function savePermission() {
        if( permissionForm.id > 0 ) {
            permissionForm.put('/permissions/' + permissionForm.id, {
                preserveScroll: true,
                onSuccess: () => permissionForm.reset('role'),
            })
        }else {
            permissionForm.post('/permissions', {
                preserveScroll: true,
                onSuccess: () => permissionForm.reset('role'),
            })
        }
    }
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
                        <div class="text-900 text-xl font-semibold mb-3 md:mb-0">
                            <span v-if="!isEditMode">Δημιουργία Δικαιώματος</span>
                            <span v-if="isEditMode">Επεξεργασία Δικαιώματος</span>
                        </div>
                    </div>

                    <form @submit.prevent="saveRole" autocomplete="off">
                        <div class="col-12 lg:col-10">
                            <div class="grid formgrid p-fluid">
                                <BaseTextInput
                                    v-model="permissionForm.name"
                                    label="Όνομα"
                                />
                            </div>
                        </div>

                        <div class="col-12 lg:col-10">
                            <div class="grid formgrid p-fluid">
                                <BaseTextInput
                                    v-model="permissionForm.code"
                                    label="Κωδικός"
                                />
                            </div>
                        </div>

                        <Button 
                            @click="savePermission"
                            label="Δημιουργία Δικαιώματος" 
                            raised 
                            class="mb-2 mr-2"
                        />
                    </form>
                </div>
            </div>
        </div>

        <AppProfileSidebar />
        <AppConfig />

        <!-- <Toast></Toast> -->
        <div class="layout-mask"></div>
    </div>
</template>