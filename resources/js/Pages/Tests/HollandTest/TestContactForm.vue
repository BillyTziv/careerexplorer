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

        hollandTestStore.setSessionRequest( sessionRequest.value );

        router.post('/tests/holland/submit', {
            test: hollandTestStore.getSubmitPayload
        }, { 
            preserveState: true, 
            replace: true, 
            onSuccess: () => {
                notify('success', 'Ολοκληρώθηκε', `Μπράβο! Το test επαγγελματικού προσανατολισμού ολοκληρώθηκε με επιτυχία!`);
            }
        });
    }
</script>

<template>
    <div class="card" style="max-width: 1000px; margin: auto; padding-top: 15px;">
        <div class="card-title">
            <!-- <span class="text-700 text-xl">Μπράβο!</span> -->
            <div class="text-900 font-bold text-4xl my-2"> Hey explorer,</div>
            <p class="text-700 text-2xl mt-0 mb-4 p-0">είσαι <strong>ένα βήμα μακριά</strong>, απο την <strong>ολοκλήρωση του τεστ</strong>!</p>

            <div :style="{ height: '3px', background: 'linear-gradient(90deg, var(--primary-color) 0%, rgba(33, 150, 243, 0) 50%)' }"></div>

            <p class="text-700 text-xl mt-3 mb-4 p-0">Συμπλήρωσε τα στοιχεία σου παρακάτω και θα μεταφερθείς αυτόματα στα αποτελέσματα.</p>
        </div>
        <div class="card-body">
            <form @submit.prevent="submitForm" autocomplete="off">
                <AppFormLayout>
                    <div class="p-fluid formgrid grid">
                        <BaseTextInput
                            v-model="form.firstname"
                            label="Όνομα"
                            :required="true"
                            placeholder="Γράψε το όνομά σου.."
                            :errors="errors.firstname"
                            class="col-12 md:col-6"
                        />

                        <BaseTextInput
                            v-model="form.lastname" 
                            label="Επίθετο"
                            :required="true"
                            placeholder="Γράψε το επίθετό σου.."
                            :errors="errors.lastname"
                            class="col-12 md:col-6"
                        />
                    </div>

                    <div class="p-fluid formgrid grid">
                        <BaseNumberInput
                            v-model="form.phone" 
                            label="Τηλέφωνο"
                            :required="true"
                            placeholder="Γράψε το κινητό σου.."
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