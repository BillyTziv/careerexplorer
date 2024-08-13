<script setup>
    import { computed, ref } from 'vue';
    import BaseErrorMessage from '@/Components/Base/BaseErrorMessage.vue';

    const emit = defineEmits(['update:field']);

    let props = defineProps({
        field: { type: Object, default: {} },
        errors: { type: String, default: '' }
    });

    const label = computed( function() {
        return props.field.label;
    });

    const hasErrors = computed( function() {
        return props.errors;
    });

    const fileName = ref('No file selected');

    function uploadCV( event ) {
        const file = event.target.files[0];
        const reader = new FileReader();
        fileName.value = file ? `Selected file: ${file.name}` : 'No file selected';

        reader.readAsDataURL(file);
        reader.onload = () => {
            props.field.value = reader.result;
            emit('update:field', props.field);
        };
    }
</script>


<template>
    <div class="relative py-4">
        <!-- <label
            :for="field.accessKey"
            class="absolute text-sm text-gray-500 dark:text-slate-300 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-2.5 peer-focus:text-slate-300 peer-focus:dark:text-slate-300 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">
            {{ field.label }}
            <span v-if="field.required" class="text-red-600 dark:text-red-500"> *</span>
        </label> -->

        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            {{ field.label }}
            <span v-if="field.required" class="text-red-600 dark:text-red-500"> *</span>
        </label>

        <input
            @input="uploadCV"
            :id="field.accessKey"
            :type="field.type"
            :readonly="field.readonly"
            :accept="field.accept"
            :class="{
                'border-red-600 dark:border-red-500 dark:focus:border-red-500 focus:border-red-600': hasErrors
            }"
            class="base-input-field block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-lime-500 focus:outline-none focus:ring-0 focus:border-lime-600 peer"    
        >

        <small class="dark:text-gray-300">{{ props.field.hint }}</small>

        <BaseErrorMessage :error="errors" />
    </div>
</template>