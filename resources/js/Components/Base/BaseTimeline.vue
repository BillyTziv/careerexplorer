<script setup>
    import { computed } from 'vue';

    import { useFormatDate } from '@/Composables/useFormatDate';

    const { formatDate } = useFormatDate();
    
    const props = defineProps({
        items: {
            type: Object,
            default: () => {}
        }
    });

    const calculatedItems = computed(() => {
        return props.items.map(item => {
            return {
                text: item.comment,
                date: formatDate(item.created_at, true)
            }
        });
    });

</script>

<template>
    <template v-for="(item, index) in calculatedItems" :key="index">
        <div class="py-2" style="border-left: solid 2px #f2f2f2; ">
            <div  style="position: relative;" >
                <div class="tag-style"></div>

                <span class="p-4">{{ item.text }}</span>
                
            </div>
        </div>
    </template>

    <!-- <Timeline v-if="calculatedItems" :value="calculatedItems" class="customized-timeline">
        <template #opposite="slotProps">
            <small class="p-text-secondary">{{ slotProps.item.date }}</small>
        </template>
        <template #content="slotProps">
            {{ slotProps.item.text }}
        </template>
    </Timeline>
     -->
    <!-- <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
        <div class="flex">
            <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
            <div>
            <p class="font-bold">
                {{ title }}
            </p>
            <p class="text-sm">
                {{  content }}
            </p>
            </div>
        </div>
    </div> -->
</template>

<style lang="scss" >
    .tag-style {
        height: 10px;
        width: 10px;
        border-radius: 50px;

        background-color: var(--primary-color);
        color: #000;
        position:absolute;
        left: -6px;
        top: 6px;

    }
</style>
