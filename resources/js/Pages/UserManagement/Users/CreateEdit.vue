<script setup>
    import { computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    
    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseNumberInput from '@/Components/Base/BaseNumberInput.vue';
    import BaseEmailInput from '@/Components/Base/BaseEmailInput.vue';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';

    let props = defineProps({
        user: Object,
        userData: Object,
        roleOptions: Object,
        errors: Object,
        response: Object
    });

    const form = useForm({
        id: props.userData.id ?? null,
        firstname: props.userData.firstname ?? null,
        lastname: props.userData.lastname ?? null,
        phone: props.userData.phone ?? null,
        email: props.userData.email ?? null,
        username: props.userData.username ?? null,
        role: props.userData.role ?? null,
        password: null,
        password_confirmation: null,
    })
    
    const isEditMode = computed(() => {
        return form.id > 0
    });

    function saveUser() {
        if( form.id > 0 ) {
            form.put('/users/' + form.id, {
                preserveScroll: true,
                onSuccess: () => form.reset('firstname'),
            })
        }else {
            form.post('/users', {
                preserveScroll: true,
                onSuccess: () => form.reset('firstname'),
            })
        }
    }
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία Χρήστη</span>
            <span v-else>Επεξεργασία Χρήστη</span>
        </template>

        <template #page-actions> </template>

        <template #page-content>
            <form @submit.prevent="submitForm" autocomplete="off">
                <div class="col-12 lg:col-12">
                    <div class="grid formgrid p-fluid">
                        <BaseTextInput
                            v-model="form.firstname"
                            label="Όνομα"
                            placeholder="Όνομα.."
                            :errors="errors.firstname"
                        />

                        <BaseTextInput
                            v-model="form.lastname" 
                            label="Επίθετο"
                            placeholder="Επίθετο.."
                            :errors="errors.lastname"
                        />

                        <BaseNumberInput
                            v-model="form.phone" 
                            label="Τηλέφωνο"
                            placeholder="Τηλέφωνο.."
                            :errors="errors.phone"
                        />

                        <InputGroup>
                            <InputGroupAddon>
                                <i class="pi pi-email"></i>
                            </InputGroupAddon>
                            <BaseEmailInput
                                v-model="form.email" 
                                label="Email"
                                placeholder="Email.."
                                :errors="errors.email"
                            />
                        </InputGroup>

                        

                        <IconField>
                            <InputIcon class="pi pi-user" />
                            <BaseTextInput
                                v-model="form.username" 
                                label="Όνομα Χρήστη"
                                :errors="errors.username"
                            />
                        </IconField>

                        <template v-if="!isEditMode">
                            <BaseTextInput
                                v-model="form.password" 
                                label="Κωδικός Χρήστη"
                                :errors="errors.password"
                            />

                            <BaseTextInput
                                v-model="form.password_confirmation" 
                                label="Επιβεβαίωση Κωδικού"
                                :errors="errors.password"
                            />
                        </template>

                        <BaseDropdownInput
                            :options="roleOptions" 
                            v-model="form.role" 
                            placeholder="Επιλέξτε ρόλο"
                            label="Ρόλος"
                            :errors="errors.role"
                        ></BaseDropdownInput>

                    </div>
                </div>

                <Button 
                    @click="saveUser"
                    label="Αποθήκευση Χρήστη" 
                    raised 
                    class="mb-2 mr-2"
                />
            </form>
        </template>
    </AppPageWrapper>
</template>