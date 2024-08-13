<template>
    <div class="px-4 py-0 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full">
      <slot />
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: 'Profile Tab'
        },
        content: {
            type: String,
            default: 'This is some placeholder content the Profile tab\'s associated content, clicking another tab will toggle the visibility of this one for the next.'
        },
        footer: {
            type: String,
            default: 'The tab JavaScript swaps classes to control the content visibility and styling.'
        }
    }
}
</script>