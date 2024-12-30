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

    const inputValue = ref( moment( props.modelValue ).format('DD-MM-YYYY') );

    const hasErrors = computed(() => {
        return props.errors.length > 0;
    });

    watch(inputValue, () => {
        emit('update:modelValue', moment( inputValue.value ).format('YYYY-MM-DD'));
    });
</script>

<template>
    <div class="field col-12">
        <label class="font-medium text-lg text-900">
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
        </label>
<br/>
        <Calendar
            dateFormat="dd-mm-yy"
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
