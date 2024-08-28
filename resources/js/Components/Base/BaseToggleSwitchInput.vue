<script setup>
    import { ref, computed, watch, defineEmits } from 'vue';
    import InputError from '@/Components/InputError.vue';

    let props = defineProps({
        modelValue:         { type: Boolean, default: false },
        label:              { type: String, default: '' },
        errors:             { type: String, default: '' },
        required:           { type: Boolean, default: false },
        fullRow:            { type: Boolean, default: false },
    });

    let emit = defineEmits([
        'update:modelValue'
    ]);

    const innerValue = ref(props.modelValue);

    watch(innerValue, (newValue) => {
        emit('update:modelValue', newValue);
    });

    const hasErrors = computed(() => {
        return props.errors.length > 0;
    });
</script>

<template>
    <div :class="['field', { 'col-12': fullRow }]">

        <div class="flex flex-col justify-between">
            <InputSwitch
                v-model="innerValue"
                class="mr-4"
            ></InputSwitch>

            <label class="font-medium text-md text-900"> 
                {{ label }}
                <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
            </label>
        </div>

        <InputError
            v-if="hasErrors"
            :message="errors"
            class="mt-2" 
        />
    </div>
</template>
   