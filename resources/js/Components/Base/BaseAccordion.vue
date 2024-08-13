<script setup>
    let props = defineProps({
        accordionTabs: {
            type: Array,
            default: []
        }
    });
</script>

<template>    
    <Accordion :activeIndex="0">
        <AccordionTab
            :header="tab.header" 
            v-for="tab in accordionTabs"
            :key="tab.header"
        >
            <p class="line-height-3 m-0">
                {{ tab.content }}
            </p>
        </AccordionTab>
    </Accordion>
</template>