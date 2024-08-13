<script setup>
    import { computed, watchEffect, ref } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const emit = defineEmits([
        'update:modelValue'
    ]);

    let props = defineProps({
        modelValue: { type: String, default: '' },
        field: { type: Object, default: {} },
        errors: { type: String, default: '' },
        rows: { type: Number, default: 4 },
        label: { type: String, default: '' },
        placeholder: { type: String, default: '' },
        required: { type: Boolean, default: false },
    });

    const inputValue = ref(props.modelValue);

    watchEffect(() => {
        inputValue.value = props.modelValue;
    });

    const updateValue = (event) => {
        emit('update:modelValue', event);
    };

    // const label = computed( function() {
    //     return props.field.label;
    // });

    const hasErrors = computed( function() {
        return props.errors.length > 0;
    });
</script>

<template>
    <div class="field mb-3  col-12">
        <label class="font-medium text-md text-900"> 
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
        </label>

        <Textarea
            @input="updateValue($event.target.value)"
            :value="inputValue"
            :invalid="hasErrors"
            :placeholder="placeholder"
            :autoResize="true"
            rows="4"
        />

        <InputError
            v-if="hasErrors"
            :message="errors"
            class="mt-2" 
        />
    </div>
</template>
  
