<template>
    <li>
        <a :href="link" class="inline-flex items-center px-4 py-3 rounded-lg hover:text-slate-900 bg-slate-50 hover:bg-slate-100 w-full dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white" :aria-current="current">
            <!-- <svg class="w-4 h-4 me-2 text-white" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
            </svg> -->
            {{ label }}
        </a>
    </li>
</template>

<script>
export default {
    props: {
        link: {
            type: String,
            default: '#'
        },
        current: {
            type: String,
            default: 'page'
        },
        label: {
            type: String,
            default: 'Profile'
        }
    }
}
</script>