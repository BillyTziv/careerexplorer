<template>
    <div class="col-6">
        <Card>
            <template #title>
                {{ title }}
            </template>

            <template #content>
                <ul>
                    <li v-for="task in tasks" :key="task.task_id">{{ task.task_name }}</li>
                </ul>
            </template>

        </Card>
    </div>
</template>

<script setup>
    import { defineProps } from 'vue';

    // Define the props for title and tasks
    const props = defineProps({
    title: {
        type: String,
        required: true
    },
    tasks: {
        type: Array,
        required: true
    }
    });
</script>
