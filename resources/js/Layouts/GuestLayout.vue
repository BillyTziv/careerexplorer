<!-- <script setup>
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
    import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <Link href="/">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template> -->

<script setup>
    import { computed, watch, ref, onBeforeUnmount } from 'vue';
    import { usePrimeVue } from 'primevue/config';
    // import AppTopbar from './AppTopbar.vue';
    // import AppSidebar from './AppSidebar.vue';
    // import AppConfig from './AppConfig.vue';
    // import AppProfileSidebar from './AppProfileSidebar.vue';
    // import AppBreadCrumb from './AppBreadcrumb.vue';
    import { useLayout } from '@/Layouts/composables/layout';
    import BaseInfoText from '@/Components/Base/BaseInfoText.vue';

    const $primevue = usePrimeVue();
    const { layoutConfig, layoutState, isSidebarActive } = useLayout();
    // const outsideClickListener = ref(null);
    // const sidebarRef = ref(null);
    // const topbarRef = ref(null);

    watch(isSidebarActive, (newVal) => {
        if (newVal) {
            bindOutsideClickListener();
        } else {
            unbindOutsideClickListener();
        }
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

</script>

<template>

        <div style="margin: 25px;">
            <!-- <AppBreadCrumb class="content-breadcrumb"></AppBreadCrumb> -->
            <div class="layout-content">
                <div class="card">
                    <div class="text-900 text-2xl font-semibold mb-3 md:mb-0">
                        <slot name="form-title"></slot>
                    </div>

                    <div class="text-900 text-lg font-normal mb-5 md:mb-0">
                        <slot name="form-subtitle"></slot>
                    </div>

                    <!-- <div v-if="" class="text-900 text-sm font-light mb-3 md:mb-0">
                        <BaseInfoText type="info">
                            <slot name="form-disclaimer"></slot>
                        </BaseInfoText>
                    </div> -->

					<!-- <div class="flex flex-column md:flex-row md:align-items-start md:justify-content-between mb-3">
						

						<div class="inline-flex align-items-center">
							<slot name="page-actions"></slot>

						
						</div>
					</div> -->

                    <div>
                        <slot name="form-fields"></slot>
                    </div>
				</div>
            </div>
        </div>

        <!-- <AppProfileSidebar />
        <AppConfig /> -->

        <Toast></Toast>
        <div class="layout-mask"></div>
</template>