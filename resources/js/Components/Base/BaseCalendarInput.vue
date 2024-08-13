<script setup>
    import { ref, computed, watch, defineEmits } from 'vue';
    import InputError from '@/Components/InputError.vue';
    import moment from 'moment';

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

    const inputValue = ref( props.modelValue );

    watch(inputValue, () => {
        updateValue( inputValue );
    });

    const updateValue = ( selectedDate ) => {
        emit('update:modelValue', moment( selectedDate ).format('DD/MM/YYYY HH:mm'));
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

        <Calendar
            dateFormat="mm-dd-yy" 
            inputId="start" 
            :invalid="hasErrors"
            v-model="inputValue"
        ></Calendar>

        <!-- <InputText
            :type="fieldType" 
            :placeholder="placeholder" 
            :value="inputValue"
            :invalid="hasErrors"
            
        /> -->

        <InputError
            v-if="hasErrors"
            :message="errors"
            class="mt-2" 
        />
    </div>
</template>