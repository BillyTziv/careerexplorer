<script setup>
    const props = defineProps({
        progress: {
            type: Number,
            required: true,
            default: 0
        },
        color: {
            type: Number,
            required: true,
            default: 'blue'
        },
        total: {
            type: Number,
            required: true,
            default: 100
        }
    });
</script>

<template>
    <div class="absolute bottom-0 left-0 border-round font-bold w-full">
        <ProgressBar
            :value="props.progress"
            class="mb-2"
        >
            {{ props.progress }}/100
        </ProgressBar>
    </div>
</template>

<style scoped>
    .p-progressbar {
        background: #323232;
    }
    
    .p-progressbar >>> .p-progressbar-label	 {
        color: white;
    }

    .p-progressbar >>> .p-progressbar-value {
        background: linear-gradient(to bottom right, #f97316, #ec4899, #a855f7) !important;
    }
</style>