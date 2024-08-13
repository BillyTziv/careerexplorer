<script setup>
    import { computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    
    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    /* Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseEmailInput from '@/Components/Base/BaseEmailInput.vue';
    import BaseNumberInput from '@/Components/Base/BaseNumberInput.vue';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';
    import BaseCalendarInput from '@/Components/Base/BaseCalendarInput.vue';

    let props = defineProps({
        user: Object,
        response: Object,
        errors: Object,
        volunteer: Object,
        roles: Object,
    });

    const volunteerForm = useForm({
        id: props.volunteer.id ? props.volunteer.id : null,
        assigned_to: props.volunteer.assigned_to ? props.volunteer.assigned_to : null,
        notes: props.volunteer.notes ? props.volunteer.notes : null,
        expectations: props.volunteer.expectations ? props.volunteer.expectations : "",
        reason: props.volunteer.reason ? props.volunteer.reason : "",
        interests: props.volunteer.interests ? props.volunteer.interests : "",
        description: props.volunteer.description ? props.volunteer.description : "",
        firstname: props.volunteer.firstname || "",
        lastname: props.volunteer.lastname || "",
        city: props.volunteer.city || "",
        address: props.volunteer.address || "",
        date_of_birth: props.volunteer.date_of_birth || "",
        cv: props.volunteer.cv || "",
        hasGivenConsent: props.volunteer.cvConsent || true,
        gender: props.volunteer.gender || "",
        age: props.volunteer.age || "",
        phone: props.volunteer.phone ? props.volunteer.phone : "",
        email: props.volunteer.email ? props.volunteer.email : "",
        role: props.volunteer.role || "",
        start_date: props.volunteer.start_date || "",
        end_date: props.volunteer.end_date || "",
        hours_contributed: props.volunteer.hours_contributed || "",
        onboarding_completed: props.volunteer.onboarding_completed || false,
        previous_volunteer_experience: props.volunteer.previous_volunteer_experience || "",
        disapproved_reason: props.volunteer.disapproved_reason || "",
        current_company: props.volunteer.current_company || "",
        current_role: props.volunteer.current_role || "",
        years_experience: props.volunteer.years_experience || "",
        career_status: props.volunteer.career_status || "",
        university: props.volunteer.university ? props.volunteer.university : "",
        department: props.volunteer.department ? props.volunteer.department : "",
        otherstudies: props.volunteer.otherstudies ? props.volunteer.otherstudies : "",
        linkedin: props.volunteer.linkedin || "",
        facebook: props.volunteer.facebook || "",
        instagram: props.volunteer.instagram || "",
        googleDrive:props.volunteer.google_drive ? props.volunteer.google_drive : "",
        asana: props.volunteer.asana ? props.volunteer.asana : "",
    });

    const isEditMode = computed(() => {
        return volunteerForm.id > 0
    });

    function uploadCV( event ) {
        const file = event.target.files[0];
        const reader = new FileReader();
        fileName.value = file ? `Selected file: ${file.name}` : 'No file selected';

        reader.readAsDataURL(file);
        reader.onload = () => {
            form.cv = reader.result;
        };
    }

    const roleDropdownList = computed( () => {
        return props.roles.map(role => ({
            id: role.id,
            label: role.name
        }));
    })

    const genderDropdownList = computed( () => {
        return [
            { id: 1, label: 'Άνδρας' },
            { id: 2, label: 'Γυναίκα' },
            { id: 3, label: 'Άλλο' }
        ];
    });

    function submit() {
        if( volunteerForm.id && volunteerForm.id > 0 ) {
            volunteerForm.put('/volunteers/'+ volunteerForm.id, volunteerForm);
        }else {
            volunteerForm.post('/volunteers/', volunteerForm);
        }
    }    
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία Εθελοντή</span>
            <span v-if="isEditMode">Επεξεργασία Εθελοντή</span>
        </template>

        <template #page-actions> </template>

        <template #page-content>
            <AppFormLayout>
                <form @submit.prevent="submit" autocomplete="off">
                    <!-----------------------------------------------------------------------------------------
                        | PERSONAL INFORMATION
                    ------------------------------------------------------------------------------------------>
                    <!-- FirstName -->
                    <BaseTextInput
                        v-model="volunteerForm.firstname"
                        label="Όνομα" 
                        placeholder="Όνομα.."
                        :required="true"
                        :errors="errors['firstname']"
                    />

                    <!-- LastName -->
                    <BaseTextInput
                        v-model="volunteerForm.lastname"
                        label="Επώνυμο"
                        placeholder="Επώνυμο.."
                        :required="true"
                        :errors="errors['lastname']"
                    />

                    <!-- Date of Birth -->
                    <BaseCalendarInput
                        v-model="volunteerForm.date_of_birth"
                        label="Ημ/νία Γέννησης"
                        :errors="errors['date_of_birth']"
                    />

                    <!-- Age -->
                    <BaseNumberInput
                        v-model="volunteerForm.age"
                        label="Ηλικία"
                        placeholder="Ηλικία.."
                        :errors="errors['age']"
                    />

                    <!-- Gender -->
                    <BaseDropdownInput
                        v-model="volunteerForm.gender"
                        :options="genderDropdownList"
                        label="Φύλο"
                        placeholder="Επιλέξτε Φύλο"
                        :required=true
                        :errors="errors.gender"
                    />

                    <!-----------------------------------------------------------------------------------------
                        | CONTACT INFORMATION
                    ------------------------------------------------------------------------------------------>

                    <!-- Phone -->
                    <BaseNumberInput
                        v-model="volunteerForm.phone"
                        label="Τηλέφωνο"
                        placeholder="Τηλέφωνο.."
                        :required="true"
                        :errors="errors['phone']"
                    />

                    <!-- Email -->
                    <BaseEmailInput
                        v-model="volunteerForm.email"
                        label="Email"
                        placeholder="Email.."
                        :required="true"
                        :errors="errors['email']"
                    />

                    <!-- City -->
                    <BaseTextInput
                        v-model="volunteerForm.city"
                        label="Πόλη"
                        placeholder="Πόλη.."
                        :errors="errors['city']"
                    />

                    <!-- Address -->
                    <BaseTextInput
                        v-model="volunteerForm.address"
                        label="Διεύθυνση"
                        placeholder="Διεύθυνση.."
                        :errors="errors['address']"
                    />
                
                    <!-----------------------------------------------------------------------------------------
                        | VOLUNTEERING
                    ------------------------------------------------------------------------------------------>

                    <!-- Onboarding Contributed -->
                    <BaseToggleSwitch 
                        v-model="volunteerForm.onboarding_completed"
                        label="Ολοκλήρωση του Onboarding"
                        class="my-4"
                    />

                    <!-- <span class="font-light text-xl text-gray-800 leading-tight py-3">
                        <span class="text-sm font-semibold px-5 py-2 rounded" v-bind:class="{ 
                            'border-l-2 dark:text-slate-300 dark:border-orange-600 dark:bg-opacity-30 dark:bg-orange-500 text-orange-600 border-orange-600 bg-orange-100 px-4 py-2 rounded-md shadow-md': volunteer.status === 1, 
                            'border-l-2 dark:text-slate-300 dark:border-green-600 dark:bg-opacity-30 dark:bg-green-500 text-green-600 border-green-600 bg-green-100 px-4 py-2 rounded-md shadow-md': volunteer.status === 2,
                            'border-l-2 dark:text-slate-300 dark:border-red-600 dark:bg-opacity-30 dark:bg-red-500 text-red-600 border-red-600 bg-red-100 px-4 py-2 rounded-md shadow-md': volunteer.status === 3 }">
                            {{ formatStatus(volunteer.status) }}
                        </span>
                    </span> -->

                    <!-- <BaseSingleSelect
                        v-model="volunteerForm.assigned_to"
                        :options="assigneeDropdownList"
                        label="Υπεύθυνος Recruiter *"
                        placeholder="Επίλεξε έναν recruiter"
                        :errors="errors['volunteering.assignee']"
                        @update:optionChanged="handleAssigneeOptionChange"
                    /> -->

                    <BaseDropdownInput
                        v-model="volunteerForm.role"
                        :options="roleDropdownList"
                        label="Ρόλος"
                        :required=true
                        :errors="errors.role"
                    />

                    <!-- Start Date -->
                    <BaseCalendarInput
                        v-model="volunteerForm.start_date"
                        label="Ημ/νία Έναρξης"
                        :errors="errors['date_of_birth']"
                    />

                    <!-- End Date -->
                    <BaseCalendarInput
                        v-model="volunteerForm.end_date"
                        label="Ημ/νία Ολοκλήρωσης"
                        :errors="errors['date_of_birth']"
                    />

                    <!-- Hours Contributed -->
                    <BaseNumberInput
                        v-model="volunteerForm.hours_contributed"
                        label="Ώρες Συνεισφοράς"
                        placeholder="Ώρες Συνεισφοράς.."
                        :errors="''"
                    />

                    <!-- Previous Volunteer Experience -->
                    <BaseNumberInput
                        v-model="volunteerForm.previous_volunteer_experience"
                        label="Προυπηρεσία σε Εθελοντισμό"
                        placeholder="Προυπηρεσία σε Εθελοντισμό.."
                        :errors="''"
                    />

                    <!-- Reject Reason -->
                    <BaseTextInput
                        v-model="volunteerForm.disapproved_reason"
                        label="Λόγος Απόρριψης"
                        placeholder="Λόγος Απόρριψης.."
                        :errors="''"
                    />

                    <!-----------------------------------------------------------------------------------------
                        | PROFESSIONAL WORK
                    ------------------------------------------------------------------------------------------>
                
                    <!-- Current Company Name -->
                    <BaseTextInput
                        v-model="volunteerForm.current_company"
                        label="Εταιρεία Εργασίας"
                        placeholder="Εταιρεία Εργασίας.."
                        :errors="errors['work.current_company']"
                    />

                    <!-- Current Company Role -->
                    <BaseTextInput
                        v-model="volunteerForm.current_role"
                        label="Ρόλος Εργασίας"
                        placeholder="Ρόλος Εργασίας.."
                        :errors="errors['work.current_role']"
                    />

                    <!-- Total Years of Professional Experience -->
                    <BaseTextInput
                        v-model="volunteerForm.years_experience"
                        label="Συνολικά Χρόνια Εργασίας"
                        placeholder="Συνολικά Χρόνια Εργασίας.."
                        :errors="errors['work.years_experience']"
                    />

                    <!-- Career Status -->
                    <BaseTextInput
                        v-model="volunteerForm.career_status"
                        label="Κατάσταση Καριέρας"
                        placeholder="Κατάσταση Καριέρας.."
                        :errors="errors['work.career_status']"
                    />
                        

                    <!-----------------------------------------------------------------------------------------
                        | CONNECTIONS
                    ------------------------------------------------------------------------------------------>
              
                    <!-- Google Link -->
                    <BaseTextInput
                        v-model="volunteerForm.googleDrive"
                        label="Google Drive Link"
                        placeholder="Google link.."
                        :errors="errors['links.googleDrive']"
                    />

                    <!-- Asana Link -->
                    <BaseTextInput
                        v-model="volunteerForm.asana"
                        label="Asana Link"
                        placeholder="Asana link.."
                        :errors="errors['links.asana']"
                    />
                
                    <!-----------------------------------------------------------------------------------------
                        | PERSONALITY
                    ------------------------------------------------------------------------------------------>
                
                    <!-- Expectations -->
                    <BaseTextarea
                        v-model="volunteerForm.expectations"
                        label="Ποιες είναι οι προσδοκίες σου από το FutureGeneration;"
                        placeholder="..."
                        :errors="errors['personality.expectations']"
                    />

                    <!-- Reasons -->
                    <BaseTextarea
                        v-model="volunteerForm.reason"
                        label="Με τι θα σε ενδιέφερε να ασχοληθείς στην ομάδα του FutureGeneration και γιατί;"
                        placeholder="..."
                        :errors="errors['personality.expectations']"
                    />

                    <!-- Interests -->
                    <BaseTextarea
                        v-model="volunteerForm.interests"
                        label="Ποια είναι τα ενδιαφέροντά σου στην προσωπική σου ζωή"
                        placeholder="..."
                        :errors="errors['personality.expectations']"
                    />

                    <!-- Personal Description -->
                    <BaseTextarea
                        v-model="volunteerForm.description"
                        label="Πως θα περιέγραφες τον εαυτό σου σε μια παράγραφο;"
                        placeholder="..."
                        :errors="errors['personality.expectations']"
                    />

                    <!------------------------------------------------------------------------------------------>
                    <!-- STUDIES -->
                    <!------------------------------------------------------------------------------------------>
                
                    <!-- University -->
                    <BaseTextInput
                        v-model="volunteerForm.university"
                        label="Πανεπιστήμο"
                        placeholder="πχ. ΕΚΠΑ"
                        :errors="''"
                    />

                    <!-- Department -->
                    <BaseTextInput
                        v-model="volunteerForm.department"
                        label="Σχολή"
                        placeholder="πχ. Τμήμα Φιλολογίας"
                        :errors="''"
                    />

                    <!-- Other Studies -->
                    <BaseTextInput
                        v-model="volunteerForm.otherstudies"
                        label="Επιπλέον Σπουδές"
                        
                        placeholder="πχ. Project Management Certification"
                        :errors="errors['other_studies']"
                    />

                    <!------------------------------------------------------------------------------------------>
                    <!-- SOCIAL NETWORKS -->
                    <!------------------------------------------------------------------------------------------>
                
                    <!-- Linkedin -->
                    <BaseTextInput
                        v-model="volunteerForm.linkedin"
                        label="Linkedin Profile URL"
                        :required=false
                        :errors="errors['linkedin']"
                    />

                    <!-- Facebook -->
                    <BaseTextInput
                        v-model="volunteerForm.facebook"
                        label="Facebook Profile URL"
                        :required=false
                        :errors="errors['facebook']"
                    />

                    <!-- Instagram -->
                    <BaseTextInput
                        v-model="volunteerForm.instagram"
                        label="Instagram Profile URL"
                        :required=false
                        :errors="errors['instagram']"
                    />

                    <!-- Notes -->
                    <BaseTextareaInput
                        v-model="volunteerForm.notes"
                        label="Σχόλια"
                        placeholder="Αφήστε ένα σχόλιο για τον εθελοντή"
                        :errors="errors.notes"
                    />

                    <!------------------------------------------------------------------------------------------>
                    <!-- CV -->
                    <!------------------------------------------------------------------------------------------>
                    <label class="block mb-2 text-sm font-medium text-sm text-gray-500 dark:text-slate-300" for="file_input">
                        Βιογραφικό
                    </label>

                    <!-- <FileUpload 
                        mode="basic" 
                        name="demo[]" 
                        accept="image/*" 
                        :maxFileSize="1000000" 
                        @uploader="onUpload" 
                        customUpload
                    /> -->


                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center p-6">
                                <svg class="w-1 h-1  text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                            </div>

                            <input @change="uploadCV" id="dropzone-file" type="file" class="hidden" />
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ fileName }}</p>
                        </label>
                    </div>

                    <Button 
                        @click="submit"
                        label="Αποθήκευση Εθελοντή"
                        raised
                        class="mb-2 mr-2"
                    />
                </form>
            </AppFormLayout>
        </template>
    </AppPageWrapper>
</template>