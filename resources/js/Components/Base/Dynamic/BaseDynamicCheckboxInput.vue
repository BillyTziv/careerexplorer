<script setup>
    import { computed } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const props = defineProps({
        modelValue: { type: String, default: '' },
        properties: { type: Object, default: {} },
        errors: { type: String, default: "" }
    });

    const model = defineModel();

    const { label, required, type, placeholder, readonly } = props.properties;

    const hasErrors = computed(() => {
        return props.errors.length > 0;
    });
</script>

<template>
    <div class="field-checkbox col-12">
        <Checkbox
            v-model="model"
            name="option"
            :id="label"
             :readonly="readonly"
            :invalid="hasErrors"
            class="w-full"
        />

        <label :for="label" class="font-medium text-md text-900"> 
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
        </label>

        <InputError
            v-if="hasErrors"
            :message="errors"
            class="mt-2" 
        />
    </div>
</template>