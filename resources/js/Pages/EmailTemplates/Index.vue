<script setup>
    /* Core */
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';

    /* Composables */
    import { useFormatDate } from '@/Composables/useFormatDate';
	import { useToastNotification } from '@/Composables/useToastNotification';

    /* Component Properties */
    let props = defineProps({
        user: Object,
        emailTemplates: Object, 
        filters: Object,
        response: Object,
    });

    /* Component Reactive Variables */
    const selectedEmailTemplate         = ref( null );              // Used for delete confirmation
    const deleteEmailTemplateDialog     = ref( false );             // Used for delete confirmation
    const emailTemplatesTableRef        = ref( null );              // Used for exportCSV
    const filterEmailTemplatesTable     = ref( props.filters );     // Used for filtering the table
    const sendTestEmailDialog           = ref(false);
    const testEmailReceiverEmail        = ref("");

    const { formatDate } = useFormatDate( );
	const { notify } = useToastNotification();

    const redirectToCreate = () => {    
        router.visit(`/email-templates/create`);
    };

    // Export the email template table to CSV
    const exportTableDataAsCSV = () => {
        emailTemplatesTableRef.value.exportCSV();
    };

    // Navigate to the edit page of a email template
    const editEntity = ( emailTemplate ) => {    
        router.visit(`/email-templates/${emailTemplate.id}/edit`);
    };

    // Popup a dialog to confirm the deletion of a email template
    const confirmdeleteEmailTemplate = ( emailTemplate ) => {
        selectedEmailTemplate.value = emailTemplate;
        deleteEmailTemplateDialog.value = true;
    };

    // Delete the email template
    const deleteEmailTemplate = () => {
        if( !selectedEmailTemplate.value.id || selectedEmailTemplate.value.id <= 0 ) return;

        router.delete(`/email-templates/${selectedEmailTemplate.value.id}`);

        deleteEmailTemplateDialog.value = false;
        selectedEmailTemplate.value = null;
    };

    const openSendTestEmailDialog = ( emailTemplate ) => {
        selectedEmailTemplate.value = emailTemplate;
        sendTestEmailDialog.value = true;
    };

    const sendTestEmail = () => {
        try {
            router.post(`/email-templates/${selectedEmailTemplate.value.id}/send-test-email`, {email: testEmailReceiverEmail.value});
        } catch (error) {
            console.log(error);
        }

        const sucessDeleteMsg = `Το δοκιμαστικό email στάλθηκε επιτυχώς στον παραλήπτη ${testEmailReceiverEmail.value}`;
        notify('success', 'Ολοκληρώθηκε', sucessDeleteMsg);
    };

    const searchFilter = ref("");

    watch(() => searchFilter, () => {
        router.get('/email-templates/', { search: searchFilter.value }, { preserveState: true, replace: true });
    }, { deep: true });
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Πρότυπα Email
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
               <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText type="text" v-model="searchFilter" placeholder="Αναζήτηση.." :style="{ borderRadius: '2rem' }" class="w-full" />
                </IconField>
                
                <div class="flex">
                    <Button type="button" icon="pi pi-download" rounded v-tooltip="'Export Data'" text @click="exportTableDataAsCSV"></Button>
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

            <DataTable ref="emailTemplatesTableRef" :value="emailTemplates" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterEmailTemplatesTable">
                <template #empty>Δεν βρέθηκαν πρότυπα email.</template>
                
                <Column field="name" header="Όνομα" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Όνομα</span>
                        {{ data.name }}
                    </template>
                </Column>

                <Column field="hook_id" header="Hook ID" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Hook Id</span>
                        {{ data.hook_id }}
                    </template>
                </Column>

                <Column field="updated_at" header="Τελευταία Ενημέρωση" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Τελευταία Ενημέρωση</span>
                        {{ formatDate(data.updated_at) }}
                    </template>
                </Column>

                <Column headerStyle="min-width:10rem;">
                    <template #body="slotProps">
                        <Button icon="pi pi-send" class="mr-2" rounded outlined severity="warning" @click="openSendTestEmailDialog(slotProps.data)" />
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editEntity(slotProps.data)" />
                        <Button icon="pi pi-trash" class="mt-2" rounded severity="danger" @click="confirmdeleteEmailTemplate(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </template>
    </AppPageWrapper>

    <!------------------------------------------------------------
        MODAL DIALOGS DEFINITION
    ------------------------------------------------------------->
    <Dialog v-model:visible="sendTestEmailDialog" :style="{ width: '450px' }" header="Αποστολή Δοκιμαστικού Email" :modal="true">
        <div class="flex align-items-center justify-content-center">
            <!-- <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" /> -->
            <!-- <span v-if="selectedEmailTemplate">
                Είστε σίγουροι οτι θέλετε να διαγράψετε το πρότυπα <b>{{ selectedEmailTemplate.name }}</b>?
            </span -->
            <BaseTextInput v-model="testEmailReceiverEmail" label="Email Παραλήπτη"/>
        </div>
        <template #footer>
            <Button label="Ακύρωση" icon="pi pi-times" text @click="sendTestEmailDialog = false" />
            <Button label="Αποστολή Δοκιμαστικού Email" icon="pi pi-send"  @click="sendTestEmail" />
        </template>
    </Dialog>
</template>

