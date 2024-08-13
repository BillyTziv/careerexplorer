<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        currentValue: {
            type: Number,
            required: true,
            default: 0
        },
        totalValue: {
            type: Number,
            required: true,
            default: 0
        }
    });

    const calculatedProgressBarWidth = computed(() => {
        const currentProgress = ((props.currentValue + 1) / props.totalValue) * 100;

        return `${currentProgress}%`;
    });

    const calculatedProgressBarWidth2 = computed(() => {
    const currentProgress = ((props.currentValue + 1) / props.totalValue) * 100;

    return `${currentProgress.toFixed(0)}%`;
});

</script>

<template>
    <div class="fixed bottom-0 left-0 right-0 m-5">
        <div
            class="flex items-center justify-end transition-all"
            :style="{ width: calculatedProgressBarWidth }"
        >
            <div class="dark:text-white text-md font-bold">{{ calculatedProgressBarWidth2 }}</div>
        </div>

        <div class="relative rounded-full h-4 dark:bg-slate-700 bg-slate-900">
            <div
                class="bg-gradient-to-br from-orange-500 via-pink-500 to-purple-500 h-4 rounded-full flex items-center justify-center transition-all"
                :style="{ width: calculatedProgressBarWidth }"
            >
            </div>
        </div>
    </div>
        
    <!-- <div class="relative bg-gray-700 rounded-full h-2.5 dark:bg-gray-700 w-full">
        <div
            class="bg-gradient-to-br from-orange-500 via-pink-500 to-purple-500 h-2.5 rounded-full"
            :style="{ width: calculatedProgressBarWidth }"
        ></div>
        <div class="dark:text-slate-300 text-lg font-bold absolute bottom-full left-0">0%</div>
        <div class="dark:text-slate-300 text-lg font-bold absolute bottom-full right-0">100%</div>
    </div> -->
</template>

<style scoped>
    @keyframes wave {
        0%, 100% {
            background-position: 0;
        }
        50% {
            background-position: 100px;
        }
    }

    .wave {
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
        background-size: 200px 100%;
        animation: wave 2s linear infinite;
    }

    .transition-all {
        transition: width 0.5s ease;
    }
</style>