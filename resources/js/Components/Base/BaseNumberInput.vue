<script setup>
    import { ref, watchEffect, defineEmits } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const emit = defineEmits(['update:modelValue']);

    const props = defineProps({
        modelValue: { type: Number, default: null },
        label: { type: String, default: '' },
        placeholder: { type: String, default: '' },
        required: { type: Boolean, default: false },
        errors: { type: String, default: "" }
    });

    const inputValue = ref(props.modelValue);

    watchEffect(() => {
        inputValue.value = props.modelValue;
    });

    const updateValue = ( event ) => {
        emit('update:modelValue', parseInt(event.target.value));
    };
</script>

<template>
    <div class="field col-12">
        <label for="nickname" class="font-medium text-900"> 
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
        </label>

        <InputText
            label="label"
            type="number" 
            :placeholder="placeholder" 
            :value="inputValue"
            @input="updateValue"
            class="w-full"
        />

        <InputError class="mt-2" :message="errors" />
    </div>
</template>