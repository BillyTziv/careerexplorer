<script setup>
    import { computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    
    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    /* Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';
    import BaseInfoText from '@/Components/Base/BaseInfoText.vue';

    let props = defineProps({
        user: Object,
        emailTemplate: {
            type: Object,
            default: () => null
        },
        errors: {
            type: Object,
            default: () => ({}),
        }
    });

    const emailTemplateForm = useForm({
        id: props.emailTemplate?.id ?? null,
        name: props.emailTemplate?.name ?? "",
        subject: props.emailTemplate?.subject ?? "",
        body: props.emailTemplate?.body ?? "",
        placeholders: props.emailTemplate?.placeholders ?? "",
        hook_id: props.emailTemplate?.hook_id ?? "",
    });

    const isEditMode = computed(() => {
        return emailTemplateForm.id > 0
    });

    function submit() {
        if( emailTemplateForm.id && emailTemplateForm.id > 0 ) {
            emailTemplateForm.put('/email-templates/'+ emailTemplateForm.id, emailTemplateForm);
        }else {
            emailTemplateForm.post('/email-templates/', emailTemplateForm);
        }
    }
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία </span>
            <span v-if="isEditMode">Επεξεργασία </span>
            Προτύπου Email
        </template>

        <template #page-content>
            <form @submit.prevent="submit" autocomplete="off">
                <AppFormLayout>
                    <BaseTextInput
                        v-model="emailTemplateForm.name"
                        label="Τίτλος"
                        type="text"
                        accessKey="name"
                        placeholder=""
                        :errors="errors.name"
                    />
    
                    <BaseInfoText
                        type="info"
                    >
                        Ο τίτλος θα χρησιμοποιηθεί μόνο εσωτερικά στην εφαρμογή, δεν θα εμφανίζεται στο email.
                    </BaseInfoText>

                    <BaseTextInput
                        v-model="emailTemplateForm.subject"
                        label="Θέμα"
                        type="text"
                        accessKey="subject"
                        placeholder=""
                        :errors="errors.subject"
                    />

                    <BaseTextareaInput
                        v-model="emailTemplateForm.body"
                        label="Μήνυμα"
                        type="text"
                        accessKey="body"
                        :rows="15"
                        placeholder=""
                        :errors="errors.body"
                    />

                    <BaseTextInput
                        v-model="emailTemplateForm.placeholders"
                        label="Placeholders"
                        type="text"
                        accessKey="placeholders"
                        placeholder=""
                        :errors="errors.placeholders"
                    />

                    <BaseInfoText
                        type="info"
                    >
                        Τα Placeholders, είναι μια λίστα με τις κωδικές λέξεις που θα αντικατασταθούν από τις σχετικές τιμές τους. Διαχωρίστε τις λέξεις με κόμμα (χωρίς κενά). Χρησιμοποιείστε τες στο μήνυμα με {} πχ. {firstname}<br />
                        Διαθέσιμα Placeholders: <strong>{firstname}, {lastname}, {email}, {userrole}</strong>.
                    </BaseInfoText>

                    <BaseTextInput
                        v-model="emailTemplateForm.hook_id"
                        label="Hook ID"
                        type="text"
                        accessKey="hook_id"
                        placeholder=""
                        :errors="errors.hook_id"
                    />

                    <BaseInfoText
                        type="info"
                    >
                        Το Hook ID, αφορά μια κωδική λέξη που δεν πρέπει να αλλάξει. Είναι ένα ID για κάθε πρότυπο email πχ. invite_volunteer ή accept_volunteer_invitation
                    </BaseInfoText>

                 

                  

                    <!-- 
                    <BaseTextareaInput
                        v-model="emailTemplateForm.description"
                        label="Περιγραφή"
                        placeholder="Περιέγραψε το ενδιαφέρον.."
                        :errors="errors.description"
                    />

                    <BaseMultiselectInput
                        v-model="emailTemplateForm.hollandCodes"
                        :options="hollandCodes"
                        label="Επίλεξε τους κωδικούς Holland"
                        :required=true
                        :errors="errors.hollandCodes"
                    /> -->
                </AppFormLayout>
                
                <Button 
                    @click="submit"
                    label="Αποθήκευση" 
                    raised 
                    class="mb-2 mr-2"
                />
            </form>
        </template>
    </AppPageWrapper>
</template>