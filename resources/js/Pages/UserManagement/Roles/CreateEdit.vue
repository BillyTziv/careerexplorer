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
    import BaseNumberInput from '@/Components/Base/BaseNumberInput.vue';

    const sidebarRef = ref(null);
    const topbarRef = ref(null);
    const $primevue = usePrimeVue();

    let props = defineProps({
        user: Object,
        role: Object,
        permissions: Object,
        errors: Object,
        response: Object
    });

    const roleForm = useForm({
        id: props.role.id ? props.role.id : null,
        name: props.role.name ? props.role.name : null,
        permissions: props.permissions ? props.permissions : null,
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
        roleForm.role = role;
    }
    
    const isEditMode = computed(() => { return roleForm.id > 0 } );

    function saveRole() {
        if( roleForm.id > 0 ) {
            roleForm.put('/roles/' + roleForm.id, {
                preserveScroll: true,
                onSuccess: () => roleForm.reset('role'),
            })
        }else {
            roleForm.post('/roles', {
                preserveScroll: true,
                onSuccess: () => roleForm.reset('role'),
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
                            <span v-if="!isEditMode">Δημιουργία Ρόλου</span>
                            <span v-if="isEditMode">Επεξεργασία Ρόλου</span>
                        </div>
                    </div>

                    <form @submit.prevent="saveRole" autocomplete="off">
                        <div class="col-12 lg:col-10">
                            <div class="grid formgrid p-fluid">
                                <BaseTextInput
                                    v-model="roleForm.name"
                                    label="Όνομα Ρόλου"
                                    :errors="errors.name"
                                />
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="dark:text-slate-300 mb-2">Δικαιώματα</label>

                            <template
                                v-for="(permission, permissionIndex) in roleForm.permissions"
                                :key="permissionIndex"
                            >
                                <div class="border-1 surface-border flex justify-content-between align-items-center px-3 border-round">
                                    <InputSwitch
                                        v-model="permission.value"
                                    ></InputSwitch>

                                    <span class="text-900 font-bold p-3">
                                        {{ permission.name }}
                                    </span>
                                </div>
                           
                                <!-- <InputSwitch v-model="permission.value" :label="permission.name"/>
                                {{ permission.name }}    
                            
                                :modelValue="rippleActive" @update:modelValue="onRippleChange" -->

                            </template>
                        </div>

                     

                        <Button 
                            @click="saveRole"
                            label="Δημιουργία Ρόλου" 
                            raised 
                            class="mb-2 mt-4"
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