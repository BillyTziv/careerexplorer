<script setup>
    import { watch, ref } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const emit = defineEmits([
        'update:modelValue'
    ]);

    let props = defineProps({
        modelValue:     { type: Number, default: '' },
        options:        { type: Array, default: [] },
        label:          { type: String, default: '' },
        placeholder:    { type: String, default: '' },
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
        <label class="font-medium text-900"> 
            {{ label }}
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

