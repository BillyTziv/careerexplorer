<script setup>
    import { computed, ref } from 'vue';
   
    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    import { useForm } from '@inertiajs/vue3';

    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseNumberInput from '@/Components/Base/BaseNumberInput.vue';

    let props = defineProps({
        user: Object,
        volunteerRole: Object,
        errors: Object,
        response: Object
    });

    const volunteerRoleForm = useForm({
        id: props.volunteerRole.id ? props.volunteerRole.id : null,
        name: props.volunteerRole.name ? props.volunteerRole.name : "",
        volunteers_needed: props.volunteerRole.volunteers_needed ? parseInt(props.volunteerRole.volunteers_needed) : 0,
        description: props.volunteerRole.description ? props.volunteerRole.description : "",
    })

    const isEditMode = computed(() => { return volunteerRoleForm.id > 0 } );

    function saveVolunteerRole() {
        if( volunteerRoleForm.id > 0 ) {
            volunteerRoleForm.put('/volunteer-roles/' + volunteerRoleForm.id, {
                preserveScroll: true,
                onSuccess: () => volunteerRoleForm.reset('role'),
            })
        }else {
            volunteerRoleForm.post('/volunteer-roles', {
                preserveScroll: true,
                onSuccess: () => volunteerRoleForm.reset('role'),
            })
        }
    }
</script>

<template>
   <AppPageWrapper>
        <template #page-title>
            <!-- <BasePageTitle></BasePageTitle> -->
            <span v-if="!isEditMode">Δημιουργία Εθελοντικού Ρόλου</span>
            <span v-if="isEditMode">Επεξεργασία Εθελοντικού Ρόλου</span>
        </template>

        <template #page-content>  
            <form @submit.prevent="saveVolunteerRole" autocomplete="off">
                <div class="col-12 lg:col-10">
                    <div class="grid formgrid p-fluid">

                        <!-- Volunteer Role Name -->
                        <BaseTextInput
                            v-model="volunteerRoleForm.name"
                            label="Όνομα Ρόλου"
                            :errors="errors.name"
                        />

                        <!-- Volunteer Role Description -->
                        <BaseTextInput
                            v-model="volunteerRoleForm.description"
                            label="Περιγραφή Ρόλου"
                            :errors="errors.description"
                        />

                        <!-- Volunteers Needed -->
                        <BaseNumberInput
                            v-model="volunteerRoleForm.volunteers_needed"
                            label="Πλήθος εθελοντών που χρειάζονται"
                            :errors="errors.volunteers_needed"
                        />
                    </div>
                </div>                                  

                <Button 
                    @click="saveVolunteerRole"
                    label="Αποθήκευση Εθελοντικού Ρόλου" 
                    raised 
                    class="mb-2 mt-4"
                />
            </form>
        </template>
    </AppPageWrapper>
</template>