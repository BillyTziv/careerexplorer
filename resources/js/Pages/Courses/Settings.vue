<template>
    <CustomNavbar :user="user">

        <template #page-header>
            Ρυθμίσεις Μαθημάτων
        </template>

        <template #page-actions>
            
        </template>

        <template #main>
            <BaseGridContainer>
                <template
                    v-for="item in gridItems"
                    :key="item"
                >
                    <BaseGridContainerItem
                        v-if="item.show"
                        :item="item"
                    />
                </template>
            </BaseGridContainer>

        </template>
    </CustomNavbar>
</template>

<script setup>
    /*-------------------------------------------------------------------
     * Components
     -------------------------------------------------------------------*/
    import CustomNavbar from '@/Pages/Common/CustomNavbar.vue';
    import BaseGridContainer from '@/Components/Layouts/BaseGridContainer.vue';
    import BaseGridContainerItem from '@/Components/Layouts/BaseGridContainerItem.vue';

    /*-------------------------------------------------------------------
     * Composables
     -------------------------------------------------------------------*/
    import usePermissions from '@/Composables/usePermissions';

    /*-------------------------------------------------------------------
     * Props
     -------------------------------------------------------------------*/
    const props = defineProps({
        user: Object
    });

    const { hasPermission } = usePermissions( props.user );

    const gridItems = [
        {
            title: "Εκπαδευτικοί Φορείς",
            link: '/course-companies',
            show: hasPermission('view-course-settings-companies'),
            icon: "M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"
        }
    ]
</script>
