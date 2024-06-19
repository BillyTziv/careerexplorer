<script setup>
    import { ref, watchEffect, defineEmits } from 'vue';

    const emit = defineEmits(['update:modelValue']);

    const props = defineProps({
        modelValue: { type: String, default: '' },
        label: { type: String, default: '' },
        placeholder: { type: String, default: '' }
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
    <div class="field mb-4 col-12">
        <label for="nickname" class="font-medium text-900"> 
            {{ label }}
        </label>

        <InputText
            label="label"
            type="number" 
            :placeholder="placeholder" 
            :value="inputValue"
            @input="updateValue"
        />

        <InputError class="mt-2" :message="errors" />
    </div>
</template>