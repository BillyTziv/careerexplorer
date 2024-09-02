<script setup>
    import { computed } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const props = defineProps({
        modelValue: { type: String, default: '' },
        field: { type: Object, default: {} },
        errors: { type: String, default: "" }
    });

    const modelValue = defineModel(props, 'modelValue', emit);

    const { label, required, type, placeholder, readonly } = props.field;

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

        <InputText
            v-model="modelValue"
            :type="type" 
            :placeholder="placeholder" 
            :readonly="readonly"
            :invalid="hasErrors"
            class="w-full"
        />

        <InputError
            v-if="hasErrors"
            :message="errors"
            class="mt-2" 
        />
    </div>
</template>