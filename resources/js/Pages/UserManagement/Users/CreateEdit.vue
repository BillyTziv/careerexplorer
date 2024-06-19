<script setup>
    import { computed, ref } from 'vue';
    import { usePrimeVue } from 'primevue/config';

    import AppTopbar from '@/Layouts/AppTopbar.vue';
    import AppSidebar from '@/Layouts/AppSidebar.vue';
    import AppConfig from '@/Layouts/AppConfig.vue';
    import AppProfileSidebar from '@/Layouts/AppProfileSidebar.vue';    
    import { useForm } from '@inertiajs/inertia-vue3';
    import { useLayout } from '@/Layouts/composables/layout';

    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseNumberInput from '@/Components/Base/BaseNumberInput.vue';

    const sidebarRef = ref(null);
    const topbarRef = ref(null);
    const $primevue = usePrimeVue();

    let props = defineProps({
        user: Object,
        userData: Object,
        errors: Object,
        response: Object
    });

    const user = useForm({
        firstname: ''
    });

    const form = useForm({
        id: props.userData.id ? props.userData.id : null,
        firstname: props.userData.firstname ? props.userData.firstname : null,
        lastname: props.userData.lastname ? props.userData.lastname : null,
        phone: props.userData.phone ? props.userData.phone : null,
        email: props.userData.email ? props.userData.email : null,
        username: props.userData.username ? props.userData.username : null,
        role: props.userData.role ? props.userData.role : null,
        password: null,
        password_confirmation: null,
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
        form.role = role;
    }
    
    const isEditMode = computed(() => { return form.id > 0 } );

    function saveUser() {
        if( form.id > 0 ) {
            form.put('/users/' + form.id, {
                preserveScroll: true,
                onSuccess: () => form.reset('firstname'),
            })
        }else {
            form.post('/users', {
                preserveScroll: true,
                onSuccess: () => form.reset('firstname'),
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
                            <span v-if="!isEditMode">Δημιουργία Χρήστη</span>
                            <span v-if="isEditMode">Επεξεργασία Χρήστη</span>
                        </div>
                    </div>

                    <form @submit.prevent="submitForm" autocomplete="off">
                        <div class="col-12 lg:col-10">
                            <div class="grid formgrid p-fluid">
                                <BaseTextInput
                                    v-model="form.firstname"
                                    label="Όνομα"
                                />

                                <BaseTextInput
                                    v-model="form.lastname" 
                                    label="Επίθετο"
                                />

                                <BaseTextInput
                                    v-model="form.phone" 
                                    label="Τηλέφωνο"
                                    field-type="number"
                                />

                                <BaseTextInput
                                    v-model="form.email" 
                                    label="Email"
                                />

                                <BaseTextInput
                                    v-model="form.username" 
                                    label="Όνομα Χρήστη"
                                />

                                <BaseTextInput
                                    v-model="form.password" 
                                    label="Κωδικός Χρήστη"
                                />

                                <BaseTextInput
                                    v-model="form.password_confirmation" 
                                    label="Επιβεβαίωση Κωδικού"
                                />
                            </div>
                        </div>

                        <Button 
                            @click="saveUser"
                            label="Δημιουργία Χρήστη" 
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