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
    import BaseDynamicFileInput from '@/Components/Base/Dynamic/BaseDynamicFileInput.vue';

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

        // Personal Information
        firstname: props.volunteer.firstname || "",
        lastname: props.volunteer.lastname || "",
        age: props.volunteer.age ? parseInt(props.volunteer.age) : null,
        gender: props.volunteer.gender || null,
        city: props.volunteer.city || "",
        address: props.volunteer.address || "",

        // Personal Information
        role: props.volunteer.role || null,

        date_of_birth: props.volunteer.date_of_birth || "",
        cv: props.volunteer.cv || "",
        hasGivenConsent: props.volunteer.cvConsent || true,
        phone: props.volunteer.phone ? props.volunteer.phone : null,
        email: props.volunteer.email ? props.volunteer.email : "",
        start_date: props.volunteer.start_date || "",
        end_date: props.volunteer.end_date || "",
        hours_contributed: props.volunteer.hours_contributed || null,
        onboarding_completed: props.volunteer.onboarding_completed || false,
        previous_volunteer_experience: parseInt(props.volunteer.previous_volunteer_experience) || null,
        disapproved_reason: props.volunteer.disapproved_reason || "",
        current_company: props.volunteer.current_company || "", // removed
        work_type: props.work_type || null,
        current_role: props.volunteer.current_role || "",
        years_experience: parseInt(props.volunteer.years_experience) || null,
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

    const workTypeDropdownList = computed( () => {
        return [
            { id: 1, label: 'Δημόσιος Τομέας' },
            { id: 2, label: 'Ιδιωτικός Τομέας' },
            { id: 3, label: 'Ελευθερος Επαγγελματίας' },
            { id: 4, label: 'Άλλο' }
        ];
    });

    function submit() {
        console.log( volunteerForm );

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
            <form @submit.prevent="submit" autocomplete="off">
                <AppFormLayout>
                    <div class="p-fluid formgrid grid">
                        <!-- FirstName -->
                        <BaseTextInput
                            v-model="volunteerForm.firstname"
                            label="Όνομα"
                            placeholder="Όνομα.."
                            :required="true"
                            :errors="errors['firstname']"
                            class="col-12 md:col-6"
                        />

                        <!-- LastName -->
                        <BaseTextInput
                            v-model="volunteerForm.lastname"
                            label="Επώνυμο"
                            placeholder="Επώνυμο.."
                            :required="true"
                            :errors="errors['lastname']"
                            class="col-12 md:col-6"
                        />
                    </div>

                    <div class="p-fluid formgrid grid">
                        <!-- Phone -->
                        <BaseTextInput
                            v-model="volunteerForm.phone"
                            label="Τηλέφωνο"
                            placeholder="Τηλέφωνο.."
                            :required="true"
                            :errors="errors['phone']"
                            class="col-12 md:col-6"
                        />

                        <!-- Email -->
                        <BaseEmailInput
                            v-model="volunteerForm.email"
                            label="Email"
                            placeholder="Email.."
                            :required="true"
                            :errors="errors['email']"
                            class="col-12 md:col-6"
                        />
                    </div>

                    <div class="p-fluid formgrid grid">
                        <BaseDropdownInput
                            v-model="volunteerForm.role"
                            :options="roleDropdownList"
                            label="Ρόλος"
                            :required=true
                            :errors="errors.role"
                            class="col-12 md:col-6"
                        />
                    </div>

                    <div class="p-fluid formgrid grid">
                        <BaseTextareaInput
                            v-model="volunteerForm.notes"
                            label="Σχόλια"
                            placeholder="Αφήστε ένα σχόλιο για τον εθελοντή"
                            :errors="errors.notes"
                            class="col-12 md:col-12"
                        />
                    </div>

                    <div class="p-fluid formgrid grid">
                        <BaseDynamicFileInput
                            v-model="volunteerForm.cv"
                            :properties="{ label: 'Βιογραφικό', required: true }"
                            :errors="errors['cv']"
                        />
                    </div>

                    <Accordion :activeIndex="0">
                        <AccordionTab header="Εθελοντική Συμμετοχή">
                            <div class="p-fluid formgrid grid">
                                <!-- Start Date -->
                                <BaseCalendarInput
                                    v-model="volunteerForm.start_date"
                                    label="Ημ/νία Αίτησης"
                                    :errors="errors['start_date']"
                                />

                                <!-- End Date -->
                                <BaseCalendarInput
                                    v-model="volunteerForm.end_date"
                                    label="Ημ/νία Ολοκλήρωσης"
                                    :errors="errors['end_date']"
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
                                    placeholder="Αλλάζω την κατάσταση γιατί..."
                                    :errors="''"
                                />
                            </div>
                        </AccordionTab>
                        <AccordionTab header="Επιπλέον Προσωπικά Στοιχεία">
                            <!-----------------------------------------------------------------------------------------
                                | PERSONAL INFORMATION
                            ------------------------------------------------------------------------------------------>
                            <div class="p-fluid formgrid grid">
                                <!-- City -->
                                <BaseTextInput
                                    v-model="volunteerForm.city"
                                    label="Πόλη"
                                    placeholder="Πόλη.."
                                    :errors="errors['city']"
                                    class="col-12 md:col-6"
                                />

                                <!-- Address -->
                                <BaseTextInput
                                    v-model="volunteerForm.address"
                                    label="Διεύθυνση"
                                    placeholder="Διεύθυνση.."
                                    :errors="errors['address']"
                                    class="col-12 md:col-6"
                                />

                                 <!-- Gender -->
                                 <BaseDropdownInput
                                    v-model="volunteerForm.gender"
                                    :options="genderDropdownList"
                                    label="Φύλο"
                                    placeholder="Επιλέξτε Φύλο"
                                    :errors="errors.gender"
                                    class="col-12 md:col-6"
                                />

                                <!-- Date of Birth -->
                                <BaseCalendarInput
                                    v-model="volunteerForm.date_of_birth"
                                    label="Ημ/νία Γέννησης"
                                    :errors="errors['date_of_birth']"
                                    class="col-12 md:col-6"
                                />
                            </div>
                        </AccordionTab>
                        <AccordionTab header="Σπουδές">
                            <!------------------------------------------------------------------------------------------>
                            <!-- STUDIES -->
                            <!------------------------------------------------------------------------------------------>
                            <div class="p-fluid formgrid grid">
                                <!-- University -->
                                <BaseTextInput
                                    v-model="volunteerForm.university"
                                    label="Πανεπιστήμο"
                                    placeholder="πχ. ΕΚΠΑ"
                                    :errors="errors['university']"
                                    class="col-12 md:col-6"
                                />

                                <!-- Department -->
                                <BaseTextInput
                                    v-model="volunteerForm.department"
                                    label="Σχολή"
                                    placeholder="πχ. Τμήμα Φιλολογίας"
                                    :errors="errors['department']"
                                    class="col-12 md:col-6"
                                />

                                <!-- Other Studies -->
                                <BaseTextInput
                                    v-model="volunteerForm.otherstudies"
                                    label="Επιπλέον Σπουδές"
                                    placeholder="πχ. Project Management Certification"
                                    :errors="errors['other_studies']"
                                    class="col-12 md:col-6"
                                />
                            </div>
                        </AccordionTab>
                        <AccordionTab header="Ενδιαφέροντα">
                            <!------------------------------------------------------------------------------------------>
                            <!-- PERSONALITY -->
                            <!------------------------------------------------------------------------------------------>
                            <div class="p-fluid formgrid grid">
                                <!-- Expectations -->
                                <BaseTextareaInput
                                    v-model="volunteerForm.expectations"
                                    label="Ποιες είναι οι προσδοκίες σου από το FutureGeneration;"
                                    placeholder="..."
                                    :errors="errors['personality.expectations']"
                                />

                                <!-- Reasons -->
                                <BaseTextareaInput
                                    v-model="volunteerForm.reason"
                                    label="Με τι θα σε ενδιέφερε να ασχοληθείς στην ομάδα του FutureGeneration και γιατί;"
                                    placeholder="..."
                                    :errors="errors['personality.reason']"
                                />

                                <!-- Interests -->
                                <BaseTextareaInput
                                    v-model="volunteerForm.interests"
                                    label="Ποια είναι τα ενδιαφέροντά σου στην προσωπική σου ζωή"
                                    placeholder="..."
                                    :errors="errors['personality.interests']"
                                />

                                <!-- Personal Description -->
                                <BaseTextareaInput
                                    v-model="volunteerForm.description"
                                    label="Πως θα περιέγραφες τον εαυτό σου σε μια παράγραφο;"
                                    placeholder="..."
                                    :errors="errors['personality.description']"
                                />
                            </div>
                        </AccordionTab>
                        <AccordionTab header="Social Media">
                            <!------------------------------------------------------------------------------------------>
                            <!-- SOCIAL MEDIA -->
                            <!------------------------------------------------------------------------------------------>
                            <div class="p-fluid formgrid grid">
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

                                 <!-- Tiktok -->
                                 <BaseTextInput
                                    v-model="volunteerForm.tiktok"
                                    label="Tiktok Profile URL"
                                    :required=false
                                    :errors="errors['tiktok']"
                                />
                            </div>
                        </AccordionTab>
                        <AccordionTab header="Επαγγελματική Εργασία">
                            <!------------------------------------------------------------------------------------------>
                            <!-- Rrofessional Work -->
                            <!------------------------------------------------------------------------------------------>
                            <div class="p-fluid formgrid grid">
                                <!-- Type of Work -->
                                <BaseDropdownInput
                                    v-model="volunteerForm.work_type"
                                    :options="workTypeDropdownList"
                                    label="Τύπος Εργασίας"
                                    :required=true
                                    :errors="errors.role"
                                    class="col-12 md:col-6"
                                />

                                <!-- Current Company Role -->
                                <BaseTextInput
                                    v-model="volunteerForm.current_role"
                                    label="Επάγγελμα/Ιδιότητα"
                                    placeholder="Επάγγελμα/Ιδιότητα.."
                                    :errors="errors['work.current_role']"
                                    class="col-12 md:col-6"
                                />

                                <!-- Total Years of Professional Experience -->
                                <BaseTextInput
                                    v-model="volunteerForm.years_experience"
                                    label="Προϋπηρεσία"
                                    placeholder=""
                                    :errors="errors['work.years_experience']"
                                />

                                <!-- Career Status -->
                                <BaseTextInput
                                    v-model="volunteerForm.career_status"
                                    label="Κατάσταση Καριέρας"
                                    placeholder="Κατάσταση Καριέρας.."
                                    :errors="errors['work.career_status']"
                                />

                                <!------------------------------------------------------------------------------------------>
                                <!-- CV -->
                                <!------------------------------------------------------------------------------------------>


                                <!-- <FileUpload
                                    mode="basic"
                                    name="demo[]"
                                    accept="image/*"
                                    :maxFileSize="1000000"
                                    @uploader="onUpload"
                                    customUpload
                                /> -->


                                <!-- <div class="flex items-center justify-center w-full">
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
                                </div> -->
                            </div>
                        </AccordionTab>
                    </Accordion>
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
