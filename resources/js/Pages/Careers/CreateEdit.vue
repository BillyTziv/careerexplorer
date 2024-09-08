<script setup>
    import { ref, computed, reactive, onMounted } from 'vue';

    // import { useForm } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    import BaseTabHeader from '@/Layouts/Tab/BaseTabHeader.vue';
    import BaseTabContent from '@/Layouts/Tab/BaseTabContent.vue';

    /* Field Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
    import BaseFileInput from '@/Components/Base/BaseFileInput.vue';
    import BaseCheckboxInput from '@/Components/Base/BaseCheckboxInput.vue';
    import BaseToggleSwitch from '@/Components/Base/BaseToggleSwitchInput.vue';
    import BaseMultiselectInput from '@/Components/Base/BaseMultiselectInput.vue';

    let props = defineProps({
        user: Object,
        errors: Object,
        response: Object,
        career: Object,
        riasecCodesDropdown: Object,
        courseDropdownOptions: Array,
        universityDropdownOptions: Array,
        interestDropdownOptions: Array,
        skillDropdownOptions: Array
    });

    const skillDropdownOptions = computed(() => {
        return props.skillDropdownOptions?.map((skill) => {
            return {
                label: skill.name,
                id: skill.id
            }
        });
    });

    const interestDropdownOptions = computed(() => {
        return props.interestDropdownOptions?.map((interest) => {
            return {
                label: interest.name,
                id: interest.id
            }
        });
    });

    const courseDropdownOptions = computed(() => {
        return props.courseDropdownOptions?.map((course) => {
            return {
                label: course.title,
                id: course.id
            }
        });
    });

    const isEditMode = computed(() => {
        return props.career.id > 0
    });

    const careerData = reactive({
        id: props.career.id ?? null,
        title: props.career.title ?? "",
        keywords: props.career.keywords ?? "",
        description: props.career.description ?? "",
        status: props.career.status ?? 1,
        responsibilities: props.career.responsibilities ?? [],
        link: props.career.link ?? "",
        hollandCodes: props.career.hollandCodes ?? [],
        connections: {
            skills: props.career.skills?.map((skill) => skill.id) ?? [],
            universities: props.career.universities ?? [],
            interests: props.career.interests?.map((interest) => interest.id) ?? [],
            courses: props.career.courses?.map((course) => course.id) ?? []
        }
    });

    function submit() {
        if( careerData.id && careerData.id > 0 ) {
            router.put('/careers/'+ careerData.id, careerData);
        }else {
            router.post('/careers/', careerData);
        }
    }

    function addCareerResposibility() {
        careerData.responsibilities.push({ text: "" })
    }

    function removeResponsibility( index ) {
        careerData.responsibilities.splice(index, 1);
    }

    onMounted(() => {
        careerData.hollandCodes = props.riasecCodesDropdown;
    });

</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span v-if="!isEditMode">Δημιουργία</span>
            <span v-if="isEditMode">Επεξεργασία</span>
            Καριέρας
        </template>

        <template #page-content>
            <form @submit.prevent="submit" autocomplete="off">
                <!-- Title -->
                <BaseTextInput
                    v-model="careerData.title"
                    label="Τίτλος Επαγγέλματος / Θέσης Εργασίας"
                    placeholder="Προγραμματιστής Λογισμικού"
                    :errors="errors.title"
                />

                <!-- Related Keywords -->
                <BaseTextInput
                    v-model="careerData.keywords"
                    label="Σχετικές λέξεις-κλειδιά"
                    placeholder="Προγραμματιστής, Developer, Μηχανικός Λογισμικού"
                    hint="Πως αλλιώς θα μπορούσε να αναζητήσει κανείς το επάγγελμα (συμπλήρωσε λέξεις χωρισμένες με κόμμα);"
                    :errors="errors.keywords"
                />

                <!-- Description -->
                <BaseTextareaInput
                    v-model="careerData.description"
                    label="Περιγραφή Επαγγέλματος / Θέσης Εργασίας"
                    :rows="10"
                    :errors="errors.description"
                />

                <!-- LINK -->
                <BaseTextInput
                    v-model="careerData.link"
                    label="Σύνδεσμος προς FG"
                    type="text"
                    :errors="errors.keywords"
                />


                <label class="block mb-2 text-md ">
                    Συμπλήρωσε μια λίστα (τυχαία σειρά) με όλες τις αρμοδιότητες του επαγγέλματος.
                </label>

                <template v-for="(resp, index) in careerData.responsibilities" :key="index">
                    <div class="flex">
                       
                        <Button
                            @click.prevent="removeResponsibility(index)"
                            icon="pi pi-times"
                            
                           text
                          
                        />

                        <div class="row">
                            <BaseTextInput
                                v-model="resp.text"
                                :errors="errors.responsibility"
                                placeholder="Π.χ. Σχεδιάζει και αναπτύσσει λογισμικό"
                            />
                        </div>
                    </div>
                </template>

                <!-- Career Responsibilities -->
                <Button
                    @click.prevent="addCareerResposibility"
                    label="Προσθήκη Αρμοδιότητας"
                    icon="pi pi-plus"
                    class="mt-2"
                    outlined
                />
        

                <!-- Δεξιότητες -->
                <BaseMultiselectInput
                    :options="skillDropdownOptions" 
                    v-model="careerData.connections.skills" 
                    label="Σχετικές Δεξιότητες"
                    :errors="errors.role"
                ></BaseMultiselectInput>

                <!-- Ενδιαφέροντα -->
                <BaseMultiselectInput
                    :options="interestDropdownOptions" 
                    v-model="careerData.connections.interests" 
                    label="Σχετικά Ενδιαφέροντα"
                    :errors="errors.interests"
                ></BaseMultiselectInput>

                <!-- Μαθήματα -->
                <BaseMultiselectInput
                    :options="courseDropdownOptions" 
                    v-model="careerData.connections.courses" 
                    label="Σχετικά Μαθήματα"
                    :errors="errors.courses"
                ></BaseMultiselectInput>

                <!-- Holland Codes -->
                <label class="block mb-2 text-md ">
                    Σχετικοί Holland Codes
                </label>

                <div class="formgrid grid">
                    <div class="field col">
                        <template v-for="code in careerData.hollandCodes" :key="code.id">
                            <BaseToggleSwitch 
                                v-model="code.value"
                                :label="code.name"
                                class="m-2"
                            />
                        </template>
                    </div>
                </div>
                
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