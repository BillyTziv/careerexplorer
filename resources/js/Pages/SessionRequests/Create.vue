<script setup>
    import { ref, reactive } from 'vue';

    // import { useForm } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPublicPageWrapper from '@/Layouts/AppPublicPageWrapper.vue';

    /* Field Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
    import BaseFileInput from '@/Components/Base/BaseFileInput.vue';
    import BaseCheckboxInput from '@/Components/Base/BaseCheckboxInput.vue';

    let props = defineProps({
        user: Object,
        formFields: {
            type: Object,
            required: true,
        },
        submitRoute: {
            type: Object,
            required: true,
        },
        errors: Object,
        response: Object
    });

    const components = {
        text: BaseTextInput,
        textarea: BaseTextareaInput,
        checkbox: BaseCheckboxInput,
        select: BaseDropdownInput,
        file: BaseFileInput,
    };

    const consentValue = ref([]);
    const form = reactive( props.formFields );

    const getComponentType = (type) => components[type] || BaseTextInput;

    function submitForm() {
        const transformedForm = Object.fromEntries(
            Object.entries(form).map(([key, value]) => [key, value.value])
        );

        router.post('/session-requests/', transformedForm);
    }    
</script>

<template>
    <AppPublicPageWrapper>
        <template #page-header-title>
            Αίτηση Δωρεάν Συνεδρίας Επαγγελματικού Προσανατολισμού
        </template>

        <template #page-header-subtitle>
        </template>

        <template #page-header-actions>
        
        </template>

        <template #page-header-disclaimer>
            <Message
                severity="info" 
            >
                <p>
                    Ο οργανισμός FutureGeneration, στην προσπάθειά του να ενισχύει συνεχώς τις νέες γενιές με τα εργαλεία και τη γνώση που χρειάζονται για να εξελιχθούν στην καριέρα τους, προσφέρει μια δωρεάν συνεδρία επαγγελματικού προσανατολισμού.
                </p>
            </Message>

            <Message
                severity="info" 
            >
                <p>
                    Η διάρκεια της συνεδρίας είναι περίπου 30 λεπτά, δεν υπάρχει περιορισμός στην ηλικία και γίνεται ψηφιακά με βάση τη δική σου διαθεσιμότητα. Λόγω μεγάλου όγκου αιτήσεων, θα τηρηθεί σειρά προτεραιότητας. Για να την αποκτήσεις, απλά συμπληρώνεις τα στοιχεία σου και στο email που θα λάβεις, επιλέγεις την ημέρα και την ώρα που θέλεις να γίνει η ψηφιακή συνεδρία επαγγελματικού προσανατολισμού.
                </p>
            </Message>
        </template>
        
        <template #page-content>
            <form @submit.prevent="submitForm" autocomplete="off">
                <div class="col-12 lg:col-10">
                    <div class="grid formgrid p-fluid">
                        <template 
                            v-for="(field, name) in form" 
                            :key="name"
                        >
                            <component
                                :is="getComponentType(field.type)"
                                v-model="field.value"
                                :label="field.label"
                                :placeholder="field.placeholder"
                                :required="field.required"
                                :errors="errors[name]"
                            />
                        </template>
                    </div>
                </div>

                <div class="field-checkbox mb-3">
                    <Checkbox id="checkOption1" name="option" value="true" v-model="consentValue" />
                    <label for="checkOption1">
                        Συναινώ στη συλλογή των δεδομένων μου για μελλοντική επικοινωνία απο το FutureGeneration και τους συνεργάτες του.
                    </label>
                </div>

                <Button
                    @click="submitForm"
                    :disabled="consentValue.length === 0"
                    label="Αποθήκευση Αιτήματος" 
                    raised 
                    class="mb-2 mr-2"
                />
            </form>
        </template>
    </AppPublicPageWrapper>
</template>