<script setup>
    import { watch, ref } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const emit = defineEmits([
        'update:modelValue'
    ]);

    let props = defineProps({
        modelValue:     { type: Number, default: null },
        options:        { type: Array, default: [] },
        label:          { type: String, default: '' },
        placeholder:    { type: String, default: '' },
        required:       { type: Boolean, default: false },
        errors:         { type: String, default: '' },
        narrow:         { type: Boolean, default: false },
    });

    const inputValue = ref(props.modelValue);

    watch(() => props.modelValue, (newVal) => {
        inputValue.value = newVal;
    });

    const updateValue = (value) => {
        emit('update:modelValue', value);
    };
</script>

<template>
    <div :class="['field', { 'col-12': !props.narrow }]">
        <label class="font-medium text-lg text-900">
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
        </label>

        <Dropdown
            v-model="inputValue"
            @update:modelValue="updateValue"
            :options="options"
            optionLabel="label"
            optionValue="id"
            :placeholder="placeholder"
            :errors="errors.role"
            class="w-full"
        ></Dropdown>

        <InputError class="mt-2" :message="errors" />
    </div>
</template>

