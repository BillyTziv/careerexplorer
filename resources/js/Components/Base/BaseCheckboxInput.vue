<script setup>
    import { ref, watchEffect, computed, defineProps } from 'vue';

    const emit = defineEmits([
        'update:modelValue'
    ]);

    const props = defineProps({
        modelValue: { type: Boolean, default: false },
        label: { type: String, default: '' },
        required: { type: Boolean, default: false },
        errors: { type: String, default: "" }
    });

    const inputValue = ref( props.modelValue );

    watchEffect(() => {
        emit('update:modelValue', inputValue.value.length > 0);
    });

    // const updateValue = ( event ) => {
    //     const isChecked = event.target.checked;

    //     emit('update:modelValue', isChecked);
    // };

    const hasErrors = computed(() => {
        return props.errors.length > 0;
    });
    
</script>

<template>
    <div class="field-checkbox col-12">
        <Checkbox
            v-model="inputValue"
            :value="'false'"
            :id="label"
            :invalid="hasErrors"
            name="option"
        />

        <label :for="label" >
            {{ label }}
        </label>
    </div>
</template>