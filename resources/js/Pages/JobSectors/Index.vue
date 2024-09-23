<script setup>
    /* Core */
    import { ref, onMounted } from 'vue';
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
        jobSectors: Object, 
        filters: Object,
        flash: Object
    });

    /* Component Reactive Variables */
    const selectedEmailTemplate         = ref( null );              // Used for delete confirmation
    const deleteEmailTemplateDialog     = ref( false );             // Used for delete confirmation
    const jobSectorsTableRef        = ref( null );              // Used for exportCSV
    const filterJobSectorsTable     = ref( props.filters );     // Used for filtering the table
    const sendTestEmailDialog           = ref(false);
    const testEmailReceiverEmail        = ref("");

    const { formatDate } = useFormatDate( );
	const { notify } = useToastNotification();

    const redirectToCreate = () => {    
        router.visit(`/job-sectors/create`);
    };

    // Export the email template table to CSV
    const exportTableDataAsCSV = () => {
        jobSectorsTableRef.value.exportCSV();
    };

    // Navigate to the edit page of a email template
    const editEntity = ( emailTemplate ) => {    
        router.visit(`/job-sectors/${emailTemplate.id}/edit`);
    };

    // Popup a dialog to confirm the deletion of a email template
    const confirmdeleteEmailTemplate = ( emailTemplate ) => {
        selectedEmailTemplate.value = emailTemplate;
        deleteEmailTemplateDialog.value = true;
    };

    // Delete the email template
    const deleteEmailTemplate = () => {
        if( !selectedEmailTemplate.value.id || selectedEmailTemplate.value.id <= 0 ) return;

        router.delete(`/job-sectors/${selectedEmailTemplate.value.id}`);

        deleteEmailTemplateDialog.value = false;
        selectedEmailTemplate.value = null;
    };

    onMounted(() => {
        if( !props.flash.success ) return; 
        
        setTimeout(() => {
            notify('success', 'Ολοκληρώθηκε', props.flash.success);
        }, 250);
    });
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Κλάδοι Επαγγελμάτων
        </template>

        <template #page-content>
            <div class="flex flex-column align-items-center md:flex-row md:align-items-start md:justify-content-between mb-3">
                <IconField iconPosition="left">
                   
                </IconField>
                
                <div class="flex">
                    <Button type="button" rounded icon="pi pi-plus" @click="redirectToCreate" />
                </div>
            </div>

            <DataTable ref="jobSectorsTableRef" :value="jobSectors" dataKey="id" paginator :rows="5" responsiveLayout="scroll" v-model:filters="filterJobSectorsTable">
                <template #empty>Δεν βρέθηκαν κλάδοι εργασίας</template>
                
                <Column field="name" header="Τίτλος Κλάδου" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Τίτλος Κλάδου</span>
                        {{ data.title }}
                    </template>
                </Column>

                <Column field="description" header="Περιγραφή Κλάδου" sortable :headerStyle="{ minWidth: '12rem' }">
                    <template #body="{ data }">
                        <span class="p-column-title">Περιγραφή Κλάδου</span>
                        {{ data.description }}
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
                        <Button icon="pi pi-pencil" class="mr-2" rounded outlined @click="editEntity(slotProps.data)" />
                        <!-- <Button icon="pi pi-trash" class="mt-2" rounded severity="danger" @click="confirmdeleteEmailTemplate(slotProps.data)" /> -->
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

