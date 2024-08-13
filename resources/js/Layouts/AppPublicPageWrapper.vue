<template>
		<div class="layout-content-wrapper">
			<div class="col-12 lg:col-12">
                <div class="card">
                    <div class="flex flex-column md:flex-row md:align-items-start md:justify-content-between mb-3">
                        <div class="text-900 text-2xl font-semibold mb-3 md:mb-0">
							<slot name="page-header-title"></slot>
						</div>

						

                        <div class="inline-flex align-items-center">

							<slot name="page-header-actions"></slot>

                        </div>
                    </div>

					<div class="text-900 text-xl font-light mb-3 md:mb-0">
						<slot name="page-header-subtitle"></slot>
					</div>

					<div>
						<slot name="page-header-disclaimer"></slot>
					</div>

					<slot name="page-content"></slot>
				</div>
			</div>
		</div>

		<!-- <Toast></Toast> -->

		<div class="layout-mask"></div>
</template>

<script setup>

	import { defineProps } from 'vue';

	const props = defineProps({
		containerClass: {
			type: Object,
			default: () => ({}),
		}
	});
</script>