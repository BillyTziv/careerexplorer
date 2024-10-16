<script setup>
    import { useFormatDate } from '@/Composables/useFormatDate';

    let props = defineProps({
        comments: {
            type: Array,
            required: true,
            default: []
        }
    });

    const { formatDate } = useFormatDate();
</script>


<template>
    <ul style="list-style: none;" class="p-2 m-0">
        <li v-for="singleComment in props.comments" :key="singleComment" class="pb-3">
            <strong>{{ formatDate(singleComment.created_at, true) }}</strong> <br />
            <span style="white-space: pre-wrap;">

                {{ singleComment.comment }}
            </span>
        </li>
    </ul>
</template>