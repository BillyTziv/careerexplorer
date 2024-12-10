<script setup>
    import { computed } from 'vue';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    import { useForm } from '@inertiajs/vue3';

    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';
    import BaseInfoText from '@/Components/Base/BaseInfoText.vue';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
    import BaseColorPickerInput from '@/Components/Base/BaseColorPickerInput.vue';
    import BaseToggleSwitchInput from '@/Components/Base/BaseToggleSwitchInput.vue';

    let props = defineProps({
        user: Object,
        sessionRequestStatus: Object,
        emailTemplates: Array,
        errors: Object,
        response: Object
    });

    const sessionRequestStatusForm = useForm({
        id: props.sessionRequestStatus.id ? props.sessionRequestStatus.id : null,
        name: props.sessionRequestStatus.name ? props.sessionRequestStatus.name : "",
        description: props.sessionRequestStatus.description ? props.sessionRequestStatus.description : "",
        hexColor: props.sessionRequestStatus.hex_color ? props.sessionRequestStatus.hex_color : "",
        isDefault: props.sessionRequestStatus.is_default ? true : false,
        isActive: props.sessionRequestStatus.is_active ? true : false,
        emailTemplateId: props.sessionRequestStatus.email_template_id ? parseInt(props.sessionRequestStatus.email_template_id) : null,
    })

    const isEditMode = computed(() => { return sessionRequestStatusForm.id > 0 } );
    const submitButtonLabel = computed(() => { return isEditMode.value ? 'Ενημέρωση' : 'Δημιουργία' });

    function savesessionRequestStatus() {
        if( sessionRequestStatusForm.id > 0 ) {
            sessionRequestStatusForm.put('/session-request-statuses/' + sessionRequestStatusForm.id, {
                preserveScroll: true,
                onSuccess: () => sessionRequestStatusForm.reset('role'),
            })
        }else {
            sessionRequestStatusForm.post('/session-request-statuses', {
                preserveScroll: true,
                onSuccess: () => sessionRequestStatusForm.reset('role'),
            })
        }
    }
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία Κατάστασης Συνεδρίας</span>
            <span v-if="isEditMode">Επεξεργασία Κατάστασης Συνεδρίας</span>
        </template>

        <template #page-content>
            <form @submit.prevent="savesessionRequestStatus" autocomplete="off">
                <AppFormLayout>

                    <!-- Session Request Status Name -->
                    <BaseTextInput
                        v-model="sessionRequestStatusForm.name"
                        label="Όνομα Κατάστασης"
                        :errors="errors.name"
                    />

                    <!-- Session Request Status Description -->
                    <BaseTextareaInput
                        v-model="sessionRequestStatusForm.description"
                        label="Περιγραφή Κατάστασης"
                        :rows="10"
                        :errors="errors.description"
                    />

                    <!-- Associated Email Template -->
                    <BaseDropdownInput
                        :options="props.emailTemplates"
                        v-model="sessionRequestStatusForm.emailTemplateId"
                        placeholder="Επιλέξτε πρότυπο email"
                        label="Πρότυπο Email"
                        :errors="errors.role"
                    ></BaseDropdownInput>

                    <BaseInfoText
                        type="info"
                    >
                        Το πρότυπο email που θα σταλεί στον εθελοντή όταν η κατάσταση του αλλάξει σε αυτή.
                    </BaseInfoText>

                    <!-- Session Request Status HEX Color -->
                    <BaseColorPickerInput
                        v-model="sessionRequestStatusForm.hexColor"
                        label="Χρώμα Κατάστασης"
                        :errors="errors.hexColor"
                    />

                    <!-- Is Session Request Status Active? -->
                    <BaseToggleSwitchInput
                        v-model="sessionRequestStatusForm.isActive"
                        label="Ορισμός ως Ενεργή Κατάσταση"
                    />

                    <BaseInfoText
                        type="info"
                    >
                        Η ενεργή κατάσταση είναι πάντα μόνο μια και υποδεικνύει την κατάσταση της συνεδρίας όταν γίνεται η αίτηση.
                    </BaseInfoText>

                    <!-- Is Session Request STatus Default? -->
                    <BaseToggleSwitchInput
                        v-model="sessionRequestStatusForm.isDefault"
                        label="Ορισμός ως Προεπιλεγμένη Κατάσταση"
                    />

                    <BaseInfoText
                        type="info"
                    >
                        Η προεπιλεγμένη κατάσταση είναι πάντα μόνο μια, και ορίζεται αυτόματα σε κάθε συνεδρία, όταν ολοκληρώνεται μια αίτηση.
                    </BaseInfoText>
                </AppFormLayout>

                <Button
                    @click="savesessionRequestStatus"
                    :label="submitButtonLabel"
                    raised
                    class="mb-2 mt-4"
                />
            </form>
        </template>
    </AppPageWrapper>
</template>
