<script setup>
    import { watch, ref } from 'vue';
    import InputError from '@/Components/InputError.vue';

    const emit = defineEmits([
        'update:modelValue'
    ]);

    let props = defineProps({
        modelValue:     { type: Array, default: [] },
        options:        { type: Array, default: [] },
        label:          { type: String, default: '' },
        errors:         { type: String, default: '' },
    });

    const inputValue = ref(props.modelValue);

    watch(() => props.modelValue, (newVal) => {
        inputValue.value = newVal;
    });

    const updateValue = (value) => {
        emit('update:modelValue', value);
    };
</script>

<template>
    <div class="field mb-4 col-12">
        <label class="font-medium text-900"> 
            {{ label }}
        </label>

        <MultiSelect
            v-model="inputValue"
            @update:modelValue="updateValue"
            :options="options"
            optionLabel="label"
            optionValue="id"
            :placeholder="label"
            :filter="true"
        >
            <!-- <template #value="slotProps">
                <div class="inline-flex align-items-center py-1 px-2 bg-primary text-primary border-round mr-2" v-for="option of slotProps.value" :key="option.code">
                    <span :class="'mr-2 flag flag-' + option.code.toLowerCase()" style="width: 18px; height: 12px" />
                    <div>{{ option.name }}</div>
                </div>
                <template v-if="!slotProps.value || slotProps.value.length === 0">
                    <div>Select Countries</div>
                </template>
            </template>
            <template #option="slotProps">
                <div class="flex align-items-center">
                    <span :class="'mr-2 flag flag-' + slotProps.option.code.toLowerCase()" style="width: 18px; height: 12px" />
                    <div>{{ slotProps.option.name }}</div>
                </div>
            </template> -->
        </MultiSelect>

        <!-- <Dropdown
            v-model="inputValue"
            
            :options="options" 
            optionLabel="label"
            optionValue="id"
            :placeholder="label"
            :errors="errors.role"
        ></Dropdown> -->

        <InputError class="mt-2" :message="errors" />
    </div> 

    
</template>