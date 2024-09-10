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
    <div class="field col-12">
        <label for="email" class="font-medium text-900"> 
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
        </label>

        <InputGroup>
            <InputGroupAddon>
                <i class="pi pi-envelope"></i>
            </InputGroupAddon>

            <InputText
                label="email"
                type="email" 
                :placeholder="placeholder" 
                :value="inputValue"
                @input="updateValue"
                class="w-full"
            />
        </InputGroup>

        <InputError class="mt-2" :message="errors" />
    </div>
</template>