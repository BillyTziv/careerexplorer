<script setup>
    /* Core */
    import { onMounted } from 'vue';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Componnet Emits */
    import { useUserStore } from '@/Stores/useUser.store';

    const userStore = useUserStore();

    /* Component Properties */
    let props = defineProps({
        user: Object
    });

    onMounted(() => {
        userStore.setUser( props.user )
    });
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Dashboard
        </template>

        <template #page-content>
            You are logged in.
            <pre>{{ user }}</pre>
        </template>
    </AppPageWrapper>
</template>