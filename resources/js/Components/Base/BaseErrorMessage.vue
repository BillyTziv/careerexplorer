<template>
    <p v-if="error" class="text-sm text-red-600 dark:text-red-500">
        <span class="font-medium">{{ error }}</span>
    </p>
</template>

<script>
export default {
    props: {
        error: {
            type: String,
            default: null
        }
    }
}
</script>