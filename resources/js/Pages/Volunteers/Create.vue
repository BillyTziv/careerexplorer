<script setup>
    import { ref, reactive, computed } from 'vue'
    import { Inertia } from '@inertiajs/inertia'

    /* Layout */
    import PublicPageLayout from '@/Components/Layouts/PublicPageLayout.vue';

    /* Form Components */
    import BaseInput from '@/Pages/Common/UI/Form/BaseInput.vue';
	import BaseTextarea from '@/Pages/Common/UI/Form/BaseTextarea.vue';
    import BaseFormSectionHeader from '@/Pages/Common/BaseFormSectionHeader.vue';
    import BaseSingleSelect from '../Common/UI/Form/BaseSingleSelect.vue';
    import BaseFormSubmitButton from '../Common/UI/Form/BaseFormSubmitButton.vue';
    import BaseInfoText from '../Common/UI/Form/BaseInfoText.vue';
    import BaseFormTitle from '../Common/UI/Form/BaseFormTitle.vue';
    import Notification2 from '../Common/Notification2.vue';

    let props = defineProps({
        roles: Object,
        response: Object,
        errors: Object
    });

    const form = reactive({
            expectations: "",
            reason: "",
            interests: "",
            description: "",
      
      
            role: '',
      
   
            phone: "",
            email: "",
    
     
            firstname: "",
            lastname: "",
            cv: "",
     
     
            university: "",
            department: "",
            otherstudies: "",
      
    
            linkedin: "",
            facebook: "",
            instagram: "",
      
    })

    const fileName = ref('No file selected');

    const roleDropdownList = computed( () => {
        return props.roles.map(role => ({
            id: role.id,
            label: role.name
        }));
    })

    function uploadCV( event ) {
        const file = event.target.files[0];
        const reader = new FileReader();
        fileName.value = file ? `Selected file: ${file.name}` : 'No file selected';

        reader.readAsDataURL(file);
        reader.onload = () => {
            form.cv = reader.result;
        };
    }
    
    function submit() {
        Inertia.post('/volunteers/submit-public-application', form);
    }

    function setVolunteerRole( role ) {
        form.role = role;
    };
</script>

<template>
    <PublicPageLayout>
        <Notification2 v-if="$page.props.flash.status" :response="$page.props.flash" />
       
        <BaseFormTitle>
            <template v-slot:title>
                Αίτηση Εγγραφής Εθελοντή
            </template>
            <template v-slot:subtitle>
                Συμπλήρωσε τα στοιχεία σου, για να δηλώσεις το ενδιαφέρον σου για εθελοντική συμμετοχή.
            </template>
        </BaseFormTitle>
        
        <form @submit.prevent="submit">

            <!------------------------------------------------------------------------------------------>
            <!-- PERSONALITY -->
            <!------------------------------------------------------------------------------------------>
            <section id="personality" class="mt-5">
                <BaseFormSectionHeader>
                    Βήμα #1 - Προσωπικότητα
                </BaseFormSectionHeader>

                <BaseTextarea
                    v-model="form.expectations"
                    label=""
                    accessKey="expectations"
                    :required="true"
                    :errors="errors['expectations']"
                />

                <BaseTextarea
                    v-model="form.reason"
                    label="Με τι θα σε ενδιέφερε να ασχοληθείς στην ομάδα του FutureGeneration και γιατί;"
                    accessKey="expectations"
                    :required="true"
                    :errors="errors['.reason']"
                />

                <BaseTextarea
                    v-model="form.interests"
                    label="Ποια είναι τα ενδιαφέροντά σου στην προσωπική σου ζωή;"
                    accessKey="expectations"
                    :required="true"
                    :errors="errors['interests']"
                />

                <BaseTextarea
                    v-model="form.description"
                    label="Πως θα περιέγραφες τον εαυτό σου σε μια παράγραφο;"
                    accessKey="expectations"
                    :required="true"
                    :errors="errors['description']"
                />
            </section>

            <!------------------------------------------------------------------------------------------>
            <!-- PERSONAL INFORMATION -->
            <!------------------------------------------------------------------------------------------>
            <section id="personal_information" class="mt-5">
                <BaseFormSectionHeader>
                    Βήμα #2 - Επικοινωνία
                </BaseFormSectionHeader>

                <BaseInput
                    v-model="form.firstname"
                    label="Όνομα"
                    access-key="firstname"
                    type="text"
                    :required="true"
                    :errors="errors['firstname']"
                />

                <BaseInput
                    v-model="form.lastname"
                    label="Επώνυμο"
                    access-key="lastname"
                    type="text"
                    :required="true"
                    :errors="errors['lastname']"
                />

                <BaseInput
                    v-model="form.phone"
                    label="Τηλέφωνο"
                    access-key="phone"
                    type="number"
                    :required="true"
                    :errors="errors['phone']"
                />

                <BaseInput
                    v-model="form.email"
                    label="Email"
                    access-key="email"
                    type="email"
                    :required="true"
                    :errors="errors['email']"
                />
            </section>

            <!------------------------------------------------------------------------------------------>
            <!-- STUDIES -->
            <!------------------------------------------------------------------------------------------>
            <section id="studies" class="mt-5">
                <BaseFormSectionHeader>
                    Βήμα #3 - Σπουδές & Εκπαίδευση
                </BaseFormSectionHeader>

                <BaseTextarea
                    v-model="form.university"
                    label="Σε ποιο εκπαιδευτικό ίδρυμα/πανεπιστήμιο φοιτάς ή φοίτησες;"
                    accessKey="university"
                    :errors="errors['studies.university']"
                />

                <BaseTextarea
                    v-model="form.department"
                    label="Σε ποιο τμήμα/σχολή φοιτάς ή φοίτησες;"
                    accessKey="department"
                    :errors="errors['personality.department']"
                />

                <BaseTextarea
                    v-model="form.otherstudies"
                    label="Επιπλέον μεταπτυχιακά, πιστοποιήσεις, σεμινάρια;"
                    accessKey="otherstudies"
                    :errors="errors['personality.otherstudies']"
                />
            </section>

            <!------------------------------------------------------------------------------------------>
            <!-- SOCIAL NETWORKS -->
            <!------------------------------------------------------------------------------------------>
            <section id="social-networks" class="mt-5">
                <BaseFormSectionHeader>
                    Βήμα #4 - Social Media
                </BaseFormSectionHeader>

                <BaseInput
                    v-model="form.linkedin"
                    label="Linkedin"
                    access-key="linkedin"
                    type="text"
                    :errors="errors.linkedin"
                />

                <BaseInput
                    v-model="form.facebook"
                    label="Facebook"
                    access-key="facebook"
                    type="text"
                    :errors="errors.facebook"
                />

                <BaseInput
                    v-model="form.instagram"
                    label="Instagram"
                    access-key="instagram"
                    type="text"
                    :errors="errors.instagram"
                />
            </section>

            <!------------------------------------------------------------------------------------------>
            <!-- CV -->
            <!------------------------------------------------------------------------------------------>
            <label class="block mb-2 text-sm font-medium text-sm text-gray-500 dark:text-slate-300" for="file_input">
                Επίσύναψε το βιογραφικό σου
            </label>
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                    </div>

                    <input @change="uploadCV" id="dropzone-file" type="file" class="hidden" />
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ fileName }}</p>
                </label>
            </div> 

            <!------------------------------------------------------------------------------------------>
            <!-- ROLE -->
            <!------------------------------------------------------------------------------------------>
            <BaseSingleSelect
                v-model="form.role"
                :options="roleDropdownList"
                label="Τι ρόλο θα ήθελες να έχεις; *"
                accessKey="role"
                placeholder="Επίλεξε έναν ρόλο"
                :errors="errors['role']"
                @update:option-changed="setVolunteerRole"
                class="my-5"
            />

            <BaseInfoText
                :type="'info'"
                class="my-5"
            >
                Με την αποστολή της αίτησης συναινείς, στην αποθήκευση των στοιχείων σου, ώστε να επικοινωνήσουμε μαζί σου.
            </BaseInfoText>
           
            <BaseFormSubmitButton
                label="Αποστολή Αίτησης"
            />
        </form>
    </PublicPageLayout>
</template>

