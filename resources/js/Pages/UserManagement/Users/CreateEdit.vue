<script setup>
    import { computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    
    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

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
            <span v-if="!isEditMode">Δημιουργία</span>
            <span v-else>Επεξεργασία </span>
            Χρήστη
        </template>

        <template #page-actions> </template>

        <template #page-content>
            <form @submit.prevent="submitForm" autocomplete="off">
                <AppFormLayout>
                    <div class="p-fluid formgrid grid">
                        <BaseTextInput
                            v-model="form.firstname"
                            label="Όνομα"
                            placeholder="Όνομα.."
                            :errors="errors.firstname"
                            class="col-12 md:col-6"
                        />

                        <BaseTextInput
                            v-model="form.lastname" 
                            label="Επίθετο"
                            placeholder="Επίθετο.."
                            :errors="errors.lastname"
                            class="col-12 md:col-6"
                        />
                    </div>

                    <div class="p-fluid formgrid grid">
                        <BaseNumberInput
                            v-model="form.phone" 
                            label="Τηλέφωνο"
                            placeholder="Τηλέφωνο.."
                            :errors="errors.phone"
                            class="col-12 md:col-6"
                        />

                        <BaseEmailInput
                            v-model="form.email" 
                            label="Email"
                            placeholder="Email.."
                            :errors="errors.email"
                            class="col-12 md:col-6"
                        />
                    </div>

                    <div class="p-fluid formgrid grid">
                        <BaseTextInput
                            v-model="form.username" 
                            label="Όνομα Χρήστη"
                            :errors="errors.username"
                            class="col-12 md:col-6"
                        />

                        <BaseDropdownInput
                            :options="roleOptions" 
                            v-model="form.role" 
                            placeholder="Επιλέξτε ρόλο"
                            label="Ρόλος"
                            :errors="errors.role"
                            class="col-12 md:col-6"
                        ></BaseDropdownInput>
                    </div>

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
                </AppFormLayout>
                
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