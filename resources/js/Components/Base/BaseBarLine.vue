<template>
	<div class="flex justify-between mb-1">
		<span :style="{ color: color }" class="text-base font-medium">
			{{ name }}
		</span>
	</div>

	<div class="flex">
		<div class="dark:text-white mr-2">
			{{ value + '%' }}
		</div>

		<div class="flex items-center w-full bg-SLATE-300 rounded-full h-6 dark:bg-slate-700">
			<div class="h-6 rounded-full" :style="{ background: color, width: value + '%' }"></div>
		</div>
	</div>

	<!-- <div class="flex justify-between">
		<span class="text-base text-gray-700 dark:text-white">0%</span>
		<span class="text-base text-gray-700 dark:text-white">100%</span>
	</div>
   -->
	<p class="dark:text-slate-300 text-slate-800 text-sm text-left">
		{{ description }}
	</p>
</template>
  
<script setup>
    import { defineProps } from 'vue';
  
    const props = defineProps({
        name:           { type: String, default: '' },
        description:    { type: String, default: '' },
        value:          { type: Number, default: 0 },
        color:          { type: String, default: '#000000' }
    });
</script>
  