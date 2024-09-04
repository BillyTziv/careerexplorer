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
        volunteerStatus: Object,
        emailTemplates: Array,
        errors: Object,
        response: Object
    });

    const volunteerStatusForm = useForm({
        id: props.volunteerStatus.id ? props.volunteerStatus.id : null,
        name: props.volunteerStatus.name ? props.volunteerStatus.name : "",
        description: props.volunteerStatus.description ? props.volunteerStatus.description : "",
        hexColor: props.volunteerStatus.hex_color ? props.volunteerStatus.hex_color : "",
        isDefault: props.volunteerStatus.is_default ? true : false,
        isActive: props.volunteerStatus.is_active ? true : false,
        emailTemplateId: props.volunteerStatus.email_template_id ? parseInt(props.volunteerStatus.email_template_id) : null,
    })

    const isEditMode = computed(() => { return volunteerStatusForm.id > 0 } );
    
    function saveVolunteerStatus() {
        if( volunteerStatusForm.id > 0 ) {
            volunteerStatusForm.put('/volunteer-statuses/' + volunteerStatusForm.id, {
                preserveScroll: true,
                onSuccess: () => volunteerStatusForm.reset('role'),
            })
        }else {
            volunteerStatusForm.post('/volunteer-statuses', {
                preserveScroll: true,
                onSuccess: () => volunteerStatusForm.reset('role'),
            })
        }
    }
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία Εθελοντικής Κατάστασης</span>
            <span v-if="isEditMode">Επεξεργασία Εθελοντικής Κατάστασης</span>
        </template>

        <template #page-content>
            <form @submit.prevent="saveVolunteerStatus" autocomplete="off">
                <AppFormLayout>

                    <!-- Volunteer Status Name -->
                    <BaseTextInput
                        v-model="volunteerStatusForm.name"
                        label="Όνομα Κατάστασης"
                        :errors="errors.name"
                    />

                    <!-- Volunteer Status Description -->
                    <BaseTextareaInput
                        v-model="volunteerStatusForm.description"
                        label="Περιγραφή Κατάστασης"
                        :rows="10"
                        :errors="errors.description"
                    />

                    <!-- Associated Email Template -->
                    <BaseDropdownInput
                        :options="props.emailTemplates" 
                        v-model="volunteerStatusForm.emailTemplateId" 
                        placeholder="Επιλέξτε πρότυπο email"
                        label="Πρότυπο Email"
                        :errors="errors.role"
                    ></BaseDropdownInput>

                    <BaseInfoText
                        type="info"
                    >
                        Το πρότυπο email που θα σταλεί στον εθελοντή όταν η κατάσταση του αλλάξει σε αυτή.
                    </BaseInfoText>

                    <!-- Volunteer Status HEX Color -->
                    <BaseColorPickerInput
                        v-model="volunteerStatusForm.hexColor"
                        label="Χρώμα Κατάστασης"
                        :errors="errors.hexColor"
                    />

                    <!-- Is Volunteer Status Active? -->
                    <BaseToggleSwitchInput
                        v-model="volunteerStatusForm.isActive"
                        label="Ορισμός ως Ενεργή Κατάσταση"
                    />

                    <BaseInfoText
                        type="info"
                    >
                        Η ενεργή κατάσταση είναι πάντα μόνο μια και υποδεικνύει την κατάσταση του εθελοντή που έχει αρχίσει να εργάζεται στον οργανισμό.
                    </BaseInfoText>

                    <!-- Is Volunteer STatus Default? -->
                    <BaseToggleSwitchInput
                        v-model="volunteerStatusForm.isDefault"
                        label="Ορισμός ως Προεπιλεγμένη Κατάσταση"
                    />

                    <BaseInfoText
                        type="info"
                    >
                        Η προεπιλεγμένη κατάσταση είναι πάντα μόνο μια, και ορίζεται αυτόματα σε κάθε εθελοντή, όταν ολοκληρώνεται μια αίτηση.
                    </BaseInfoText>
                </AppFormLayout>

                <Button 
                    @click="saveVolunteerStatus"
                    label="Δημιουργία Ρόλου" 
                    raised 
                    class="mb-2 mt-4"
                />
            </form>
        </template>
    </AppPageWrapper>
</template>