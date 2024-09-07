<script setup>
    import { ref, computed, reactive } from 'vue';
    import { router } from '@inertiajs/vue3';

    /* Layouts */
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    /* Components */
    import BaseDynamicTextInput from '@/Components/Base/Dynamic/BaseDynamicTextInput.vue';
    // import BaseDynamicEmailInput from '@/Components/Base/Dynamic/BaseDynamicEmailInput.vue';
    // import BaseDynamicNumberInput from '@/Components/Base/Dynamic/BaseDynamicNumberInput.vue';
    import BaseDynamicDropdownInput from '@/Components/Base/Dynamic/BaseDynamicDropdownInput.vue';
    import BaseDynamicTextareaInput from '@/Components/Base/Dynamic/BaseDynamicTextareaInput.vue';
    // import BaseDynamicCalendarInput from '@/Components/Base/Dynamic/BaseDynamicCalendarInput.vue';
    //  import BaseDynamicCheckboxInput from '@/Components/Base/Dynamic/BaseDynamicCheckboxInput.vue';
    import BaseDynamicFileInput from '@/Components/Base/Dynamic/BaseDynamicFileInput.vue';
    import BaseDynamicSectionTitle from '@/Components/Base/Dynamic/BaseDynamicSectionTitle.vue';

    let props = defineProps({
        user: Object,
        response: Object,
        formFields: {
            type: Object,
            required: true,
        },
        submitRoute: {
            type: Object,
            required: true,
        },
        metaData: {
            type: Object,
            required: true,
        },
        errors: {
            type: Object,
            required: false,
            default: () => ({}),
        },
    });

    const components = {
        text: BaseDynamicTextInput,
        textarea: BaseDynamicTextareaInput,
        select: BaseDynamicDropdownInput,
        file: BaseDynamicFileInput,
        section: BaseDynamicSectionTitle
    };
    
    const form = reactive( props.formFields );
    const formTitle = computed(() => props.metaData.title);
    const formSubtitle = computed(() => props.metaData.subtitle);
    const formDisclaimer = computed(() => props.metaData.disclaimer);
    const consentValue = ref([]);
    const hasGivenConsent = computed(() => consentValue.value.includes(true));

    const getComponentType = (type) => components[type] || BaseDynamicTextInput;

    function submit() {
        // Check if user has conserned to the terms of use
        if( !hasGivenConsent ) {
            alert('Πρέπει να δώσετε τη συγκατάθεσή σας για τη συλλογή των προσωπικών σας δεδομένων πριν υποβάλετε την αίτηση.');
            return;
        }

        router.post(
            props.submitRoute.url, 
            {
                formType: props.metaData.formType,
                form: form,
            }
        );
    }

    const hasErrors = computed(() => {
        return Object.keys(props.errors).length > 0;
    });
</script>

<template>
    <GuestLayout>
        <template #form-title>{{ formTitle }}</template>

        <template #form-subtitle>{{ formSubtitle }}</template>

        <template #form-disclaimer>{{ formDisclaimer }}</template>

        <template #form-fields>
            <form @submit.prevent="submit" autocomplete="off" class="mt-3">
                <AppFormLayout>

                    <template 
                        v-for="(field, name) in form" 
                        :key="name"
                    >
                        <Component
                            :is="getComponentType(field.type)" 
                            v-model="field.value"
                            :properties="field"
                            :errors="errors['form.' + name + '.value']"
                        />
                    </template>
                </AppFormLayout>

                <div class="field col-12">
                    <div class="field-checkbox mb-4">
                        <Checkbox 
                            v-model="consentValue"
                            id="checkOption1" 
                            name="option" 
                            :value="true"
                        />

                        <label for="checkOption1">
                            {{ metaData.consentMessage }}
                        </label>
                    </div>

                    <Button
                        @click="submit"
                        :disabled="!hasGivenConsent"
                        icon="pi pi-check"
                        label="Υποβολή Αίτησης" 
                        raised 
                        class="mb-2 mr-2"
                    />
                </div>
            </form>
        </template>
    </GuestLayout>
</template>