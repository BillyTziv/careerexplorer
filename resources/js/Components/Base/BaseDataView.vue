<script setup>
const props = defineProps({
    title: {
        type: String,
        required: true,
        default: ''
    },
    items: {
        type: Object,
        required: true,
        default: () => []
    },
    rows: {
        type: Number,
        required: true,
        default: 10
    },
    layout: {
        type: String,
        required: true,
        default: 'list'
    },
    sortOrder: {
        type: Number,
        required: false,
        default: -1
    },
    sortField: {
        type: String,
        required: false,
        default: ''
    },
    sortOptions: {
        type: Array,
        required: false,
        default: () => []
    },
    sortKey: {
        type: String,
        required: false,
        default: null
    }
});
</script>

<template>
    <DataView :value="props.items" :rows="props.rows" :layout="props.layout" :sortOrder="props.sortOrder"
        :sortField="props.sortField" paginator>
        <template #grid="slotProps">
            <div class="grid grid-nogutter">
                <div v-for="(item, index) in slotProps.items" :key="index" class="col-12 md:col-4">
                    <div class="p-3">
                        <div class="surface-100 cursor-pointer z-index border-round">
                            <div class="relative">
                                <img v-if="item.coverImage" :src="item.coverImage" class="w-full"
                                    :alt="item.description.split(' ', 1)" />
                                <img v-if="item.profile" :src="item.profile" class="flex absolute w-4rem h-4rem"
                                    :style="{ bottom: '-1.5rem', right: '1.5rem' }"
                                    :alt="item.description.split(' ', 1)" />
                            </div>
                            <div class="p-3">
                                <div class="text-900 font-semibold text-xl mb-3">{{ item.title }}</div>
                                <p class="text-700 text-lg mt-0 mb-5">{{ item.description }}</p>

                                <!-- <div class="flex flex-wrap gap-2 align-items-center justify-content-between">
                                    <span class="flex align-items-center text-900">
                                        <i class="pi pi-comment mr-2"></i>
                                        <span class="font-semibold">{{ item.comment }}</span>
                                    </span>
                                    <span class="flex align-items-center text-900">
                                        <i class="pi pi-share-alt mr-2"></i>
                                        <span class="font-semibold">{{ item.share }}</span>
                                    </span>
                                    <span class="flex align-items-center text-900">
                                        <i class="pi pi-clock mr-2"></i>
                                        <span class="font-semibold mr-1">{{ item.day }}</span>
                                        <span class="font-semibold">{{ item.month }}</span>
                                    </span>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </DataView>
</template>

<style scoped></style>