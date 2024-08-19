<script setup>
    import { ref, computed, watchEffect, defineEmits } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const emit = defineEmits([
        'update:modelValue',
        'update:field'
    ]);

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

    const hasErrors = computed(() => {
        return props.errors.length > 0;
    });
</script>

<template>
    <div class="field">
        <label class="font-medium text-md text-900"> 
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
        </label>

        <InputText
            :type="fieldType" 
            :placeholder="placeholder" 
            :value="inputValue"
            :invalid="hasErrors"
            @input="updateValue"
        />

        <InputError
            v-if="hasErrors"
            :message="errors"
            class="mt-2" 
        />
    </div>
</template>