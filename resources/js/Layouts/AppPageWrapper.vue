<template>
	<div :class="['layout-container', { ...containerClass }]">
		<AppSidebar ref="sidebarRef" />
	
 		<!-- <AppBreadCrumb class="content-breadcrumb"></AppBreadCrumb> -->

		<div class="layout-content-wrapper">
			<AppTopbar ref="topbarRef" />

			<div class="col-12 lg:col-12">
                <div class="card">
                    <div class="flex flex-column md:flex-row md:align-items-start md:justify-content-between mb-3">
                        <div class="text-900 text-2xl font-semibold mb-3 md:mb-0">
							<slot name="page-title"></slot>
						</div>

                        <div class="inline-flex align-items-center">
							<slot name="page-actions"></slot>

                           
                        </div>
                    </div>

					<slot name="page-content"></slot>
				</div>
			</div>
									
			
		</div>

		<AppProfileSidebar />

		<!-- <Toast></Toast> -->

		<div class="layout-mask"></div>
	</div>
</template>

<script setup>
    import { computed, ref } from 'vue';

	import AppTopbar from '@/Layouts/AppTopbar.vue';
    import AppSidebar from '@/Layouts/AppSidebar.vue';
    import AppProfileSidebar from '@/Layouts/AppProfileSidebar.vue';

	import { defineProps } from 'vue';
	import { useLayout } from '@/Layouts/composables/layout';
    import { usePrimeVue } from 'primevue/config';

	const props = defineProps({
		containerClass: {
			type: Object,
			default: () => ({}),
		}
	});

	const sidebarRef = ref(null);
    const topbarRef = ref(null);

	const { layoutConfig, layoutState, isSidebarActive } = useLayout();

    const $primevue = usePrimeVue();

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