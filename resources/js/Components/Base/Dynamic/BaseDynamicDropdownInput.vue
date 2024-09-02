<script setup>
    import { computed } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const props = defineProps({
        modelValue: { type: String, default: '' },
        properties: { type: Object, default: {} },
        errors: { type: String, default: "" }
    });

    const model = defineModel();

    const { label, required, options, placeholder, readonly, disabled } = props.properties;

    const hasErrors = computed(() => {
        return props.errors.length > 0;
    });
</script>

<template>
    <div class="field col-12">
        <label class="font-medium text-md text-900"> 
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
        </label>

        <Dropdown
            v-model="model"
            :options="options" 
            optionLabel="label"
            optionValue="id"
            :placeholder="placeholder"
            :invalid="hasErrors"
            :errors="errors.role"
            class="w-full"
        ></Dropdown>

        <InputError
            v-if="hasErrors"
            :message="errors"
            class="mt-2" 
        />
    </div>
</template>