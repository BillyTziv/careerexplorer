<script setup>
    import { computed, reactive } from 'vue';

    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseEmailInput from '@/Components/Base/BaseEmailInput.vue';
    import BaseNumberInput from '@/Components/Base/BaseNumberInput.vue';

    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    import { useHollandTestStore } from '@/Stores/useHollandTest.store';

    let props = defineProps({
        errors: {
            type: Object,
            required: false,
            default: () => ({}),
        },
    });

    const hollandTestStore = useHollandTestStore();

    const consentMessage = "Δηλώνω ότι συναινώ στη συλλογή και επεξεργασία των προσωπικών μου δεδομένων για σκοπούς μελλοντικής επικοινωνίας από την FutureGeneration και τους εξουσιοδοτημένους συνεργάτες της, σύμφωνα με τις διατάξεις του Γενικού Κανονισμού Προστασίας Δεδομένων (GDPR).";

    const form = reactive({
        firstname: "",
        lastname: "",
        phone: null,
        email: "",
        consent: []
    });

    const hasGivenConsent = computed(() => form.consent.includes(true));

    function submitForm() {
        hollandTestStore.setUserData( form );
    }
</script>

<template>
    <div >
        <div class="card">

            <form @submit.prevent="submitForm" autocomplete="off">
                <AppFormLayout>
                    <div class="p-fluid formgrid grid">
                        <BaseTextInput
                            v-model="form.firstname"
                            label="Όνομα"
                            :required="true"
                            placeholder="Όνομα.."
                            :errors="errors.firstname"
                            class="col-12 md:col-6"
                        />

                        <BaseTextInput
                            v-model="form.lastname" 
                            label="Επίθετο"
                            :required="true"
                            placeholder="Επίθετο.."
                            :errors="errors.lastname"
                            class="col-12 md:col-6"
                        />
                    </div>

                    <div class="p-fluid formgrid grid">
                        <BaseNumberInput
                            v-model="form.phone" 
                            label="Τηλέφωνο"
                            :required="true"
                            placeholder="Τηλέφωνο.."
                            :errors="errors.phone"
                            class="col-12 md:col-6"
                        />

                        <BaseEmailInput
                            v-model="form.email" 
                            label="Email"
                            :required="true"
                            placeholder="Email.."
                            :errors="errors.email"
                            class="col-12 md:col-6"
                        />
                    </div>
                </AppFormLayout>
                
                <div class="field col-12">
                    <div class="field-checkbox mb-4">
                        <Checkbox 
                            v-model="form.consent"
                            id="checkOption1" 
                            name="option" 
                            :value="true"
                        />

                        <label for="checkOption1">
                            {{ consentMessage }}
                        </label>
                    </div>

                    <Button
                        @click="submitForm"
                        :disabled="!hasGivenConsent"
                        icon="pi pi-arrow-right"
                        label="Συνέχεια" 
                        raised 
                        iconPos="right"
                        class="mb-2 mr-2"
                    />
                </div>
            </form>
        </div>
    </div>
</template>