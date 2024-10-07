<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: { type: String, default: '' },
    items: { type: Array, default: [] },
});

const MAX_VISIBLE_ITEMS = 5;

const totalItemsMissing = computed(() => {
    return props.items.length - MAX_VISIBLE_ITEMS;
});

const visibleIteme = computed(() => {
    return props.items.slice(0, MAX_VISIBLE_ITEMS)
});

const hasMore = computed(() => {
    return props.items.length > MAX_VISIBLE_ITEMS;
});
// let emits = defineEmits([
//     'update:modelValue'
// ]);

// let selectedOptions = reactive(props.modelValue);

// function updateSelectedOptions(event, id) {
//     if (event.target.checked) {
//         selectedOptions.push(id);
//     } else {
//         selectedOptions.splice(selectedOptions.indexOf(id), 1);
//     }

//     emits('update:modelValue', selectedOptions);
// }
</script>

<template>
    <ul class="text-lg list-none">
        <li v-for="(item, index) in visibleIteme" :key="index">
            <div class="flex mb-2">
                <i class="pi pi-check mt-1 mr-2" style="color: green; font-size: 1.2rem; font-weight: bold;"></i>
                <span>
                    {{ item.text }}
                </span>
            </div>
        </li>
    </ul>

    <!-- <div class="flex flex-col md:flex-row align-content-center justify-content-between">
        <Message
            v-if="hasMore" 
            severity="info"
            icon="pi pi-lock"
            :closable="false"
        >
            {{ `Υπάρχει κλειδωμένο περιεχόμενο με ${ totalItemsMissing } επιπλέον στοιχεία. Κάνε εγγραφή για να απκτήσεις πρόσβαση!` }}
        </Message>

        <div class="flex align-items-center justify-content-center">
            <Button
                @click="() => router.push('/subscribe')"
                class="my-2 mx-4 "
            >
                Εγγραφή
            </Button>
        </div>
    </div> -->
    <!-- <label for="checkboxex" class="block mb-2 text-md font-bold text-gray-900 dark:text-gray-400">
        {{ label }}
    </label>
    
    <span v-for="(option, index) in options" :key="option.id">
        <div class="flex">
            <div class="flex items-center h-5">
                <input :id="`${option.name}-${index}`" :aria-describedby="`${option.name}-${index}`" type="checkbox" :value="option.id" class="w-4 h-4 text-lime-600 bg-gray-100 border-gray-300 rounded focus:ring-lime-500 dark:focus:ring-lime-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                :checked="selectedOptions.includes(option.id)"
                @change="updateSelectedOptions($event, option.id)">
            </div>
            <div class="ml-2 text-sm">
                <label :for="`${option.name}-${index}`" class="font-medium text-gray-900 dark:text-gray-300">
                    {{ option.name }}
                </label>
                <p :id="`${option.name}-${index}`" class="text-xs font-normal text-gray-500 dark:text-gray-300">
                    {{ option.description }}
                </p>
            </div>
        </div>
    </span> -->
</template>


<style scoped>
.list-none {
    list-style-type: none;
    padding: 0 0;
}
</style>