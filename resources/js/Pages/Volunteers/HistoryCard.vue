<script setup>
import { useFormatDate } from '@/Composables/useFormatDate';


const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const { formatDate } = useFormatDate();
</script>

<template>
    <div class="flex flex-nowrap justify-content-between align-items-center p-2 hover:surface-hover transition-colors transition-duration-150"
        tabindex="0">
        <div class="flex align-items-center">
            <!-- <div class="relative md:mr-3">
                <img :src="'/demo/images/avatar/' + user.image" alt="user" class="w-3rem h-3rem border-circle shadow-4" />
                <span
                    class="w-1rem h-1rem border-circle border-2 surface-border absolute"
                    :class="{ 'bg-green-400': user.status === 'active', 'bg-red-400': user.status === 'busy', 'bg-yellow-400': user.status === 'away' }"
                    style="bottom: 2px; right: 2px"
                ></span>
            </div> -->
            <div class="flex-column hidden md:flex">
                <span class="text-900 font-semibold block">{{ user.fullname }}</span>
                <!-- Display the description directly from the user prop -->
                <span class="block text-600 white-space-nowrap w-10rem text-sm">{{ user.description }}</span>
            </div>
        </div>

        <!-- Display the created_at directly from the user prop -->
        <span class="text-700 font-light ml-auto hidden md:inline">{{ formatDate(user.created_at, true) }}</span>
    </div>
</template>