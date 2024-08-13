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
        inputValue.value = event.target.value;
        emit('update:modelValue', event.target.value);
        //emit('update:field', props.field);
    };

    const hasErrors = computed(() => {
        return props.errors.length > 0;
    });
</script>

<template>
    <div class="field mb-3 col-12">
        <label class="font-medium text-md text-900"> 
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
        </label>

        <div class="flex justify-between content-center align-items-center">
            <ColorPicker
                :value="inputValue"
                style="width: 2rem" 
                @input="updateValue"
                class="mr-4"
            />
            
            <InputText
                :type="fieldType" 
                :placeholder="placeholder" 
                :value="inputValue"
                :invalid="hasErrors"
                @input="updateValue"
            />
        </div>
        <InputError
            v-if="hasErrors"
            :message="errors"
            class="mt-2" 
        />
    </div>
</template>