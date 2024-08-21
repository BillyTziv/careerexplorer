<script setup>
    import { ref, computed, watchEffect, watch, defineEmits } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const emit = defineEmits([
        'update:modelValue'
        // 'update:field'
    ]);

    const props = defineProps({
        modelValue: { type: String, default: '' },
        label: { type: String, default: '' },
        placeholder: { type: String, default: '' },
        required: { type: Boolean, default: false },
        errors: { type: String, default: "" }
    });

    const selectedColor = ref(props.modelValue);

    // watchEffect(() => {
    //     inputValue.value = props.modelValue;
    // });

    //const updateValue = ( event ) => {
        //console.log("Event", event);

        //inputValue.value = event.target.value;
        //emit('update:modelValue', event.target.value);
        //emit('update:field', props.field);
    //};

    watch(selectedColor, (newColor) => {
        emit('update:modelValue', newColor);
    });

    const hasErrors = computed(() => {
        return props.errors.length > 0;
    });
</script>

<template>
    <div class="field col-12">
        <label class="font-medium text-md text-900"> 
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-500"> *</span>
        </label>

        <div class="flex items-center">
            <ColorPicker
                v-model="selectedColor"
                class="mr-4 flex-shrink-0 flex align-items-center"
            />
            
            <InputText
                v-model="selectedColor"
                :placeholder="placeholder" 
                :invalid="hasErrors"
                class="w-full"
            />
        </div>

        <InputError
            v-if="hasErrors"
            :message="errors"
            class="mt-2" 
        />
    </div>
</template>