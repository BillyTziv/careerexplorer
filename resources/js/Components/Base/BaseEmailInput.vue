<script setup>
    import { ref, watchEffect, defineEmits } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const emit = defineEmits(['update:modelValue']);

    const props = defineProps({
        modelValue: { type: String, default: '' },
        label: { type: String, default: '' },
        fieldType: { type: String, default: 'text' },
        placeholder: { type: String, default: '' },
        required: { type: Boolean, default: false },
        errors: { type: String, default: "" }
    });

    const inputValue = ref(props.modelValue);

    watchEffect(() => {
        inputValue.value = props.modelValue;
    });

    const updateValue = ( event ) => {
        emit('update:modelValue', event.target.value);
    };
</script>

<template>
    <div class="field">
        <label for="email" class="font-medium text-900"> 
            {{ label }}
        </label>

        <InputText
            label="email"
            type="email" 
            :placeholder="placeholder" 
            :value="inputValue"
            @input="updateValue"
        />

        <InputError class="mt-2" :message="errors" />
    </div>
</template>