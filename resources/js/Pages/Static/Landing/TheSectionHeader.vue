<script setup>
    const props = defineProps({
        title: String,
        description: String,
        alignContent: {
            type: String,
            default: 'center'
        }
    });
</script>

<template>
    <div class="px-0 py-4 overflow-hidden">
        <div :class="`font-bold text-900 mb-3 text-center sm:text-${ props.alignContent } text-3xl sm:text-4xl md:text-5xl lg:text-5xl xl:text-5xl`">
            <slot name="title"></slot> 
        </div>
        <div :class="`text-700 text-${ alignContent } mb-5 line-height-3 font-light text-xl sm:text-xl md:text-xl lg:text-2xl xl:text-2xl`">
            <slot name="description"></slot>
        </div>
    </div>
</template>