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

    const currentTab = ref('tab_career');
    const isEditMode = computed(() => {
        return career.id > 0
    });
    function submitForm() {
        const transformedForm = Object.fromEntries(
            Object.entries(form).map(([key, value]) => [key, value.value])
        );

        router.post('/session-requests/', transformedForm);
    }

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
            skills: props.career.skills ?? [],
            universities: props.career.universities ?? [],
            interests: props.career.interests ?? [],
            courses: props.career.courses ?? []
        }
    });

    function submit() {
        if( careerData.id && careerData.id > 0 ) {
            Inertia.put('/careers/'+ careerData.id, careerData);
        }else {
            Inertia.post('/careers/', careerData);
            careerData.id = null;
            careerData.title = "";
            careerData.keywords = "";
            careerData.status = 1;
            careerData.responsibilities = [];
            careerData.description = "";
            careerData.link = "";
            careerData.hollandCodes = [];
            careerData.connections.interests =  [];
            careerData.connections.skills =  [];
            careerData.connections.universities =  [];
            careerData.connections.courses =  [];
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
        <template #page-header-title>
            <div class="flex flex-column md:flex-row md:align-items-start md:justify-content-between mb-3">
                <div class="text-900 text-xl font-semibold mb-3 md:mb-0">
                    <span v-if="!isEditMode">Δημιουργία Χρήστη</span>
                    <span v-if="isEditMode">Επεξεργασία Χρήστη</span>
                </div>
            </div>
        </template>

        <template #page-header-subtitle>

        </template>

        <template #page-header-actions>
        
        </template>

        <template #page-header-disclaimer>

        </template>
        
        <template #page-content>
            <form @submit.prevent="submitForm" autocomplete="off">
                <div class="col-12 lg:col-10">
                    <div class="grid formgrid p-fluid">
                        <!-- Title -->
                        <BaseTextInput
                            v-model="careerData.title"
                            label="Τίτλος Επαγγέλματος / Θέσης Εργασίας"
                            :errors="errors.title"
                        />

                        <!-- Related Keywords -->
                        <BaseTextInput
                            v-model="careerData.keywords"
                            label="Σχετικές λέξεις-κλειδιά"
                            type="text"
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

                        <div class="relative">
                            <div class="relative mt-5">
                                <label class="block mb-2 text-md text-gray-300 dark:text-gray-100">
                                    Συμπλήρωσε μια λίστα (τυχαία σειρά) με όλες τις αρμοδιότητες του επαγγέλματος.
                                </label>
                            </div>

                            <template v-for="(resp, index) in careerData.responsibilities" :key="index">
                                <div class="flex items-center space-x-2">
                                    <button @click.prevent="removeResponsibility(index)" class="inline-flex items-center mt-3 p-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 ">
                                        <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                                        </svg>
                                    </button>

                                    <BaseTextInput
                                        v-model="resp.text"
                                        :label="'Αρμοδιότητα #' + index"
                                        accessKey="responsibility"
                                        :errors="errors.responsibility"
                                        class="flex-grow" 
                                    />
                                </div>
                            </template>

                            <!-- Career Responsibilities -->
                            <button
                                @click.prevent="addCareerResposibility"
                                class="mt-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
                                ΠΡΟΣΘΗΚΗ
                            </button>
                        </div>    

                        <!-- Δεξιότητες -->
                        <BaseMultiselectInput
                            :options="skillDropdownOptions" 
                            v-model="careerData.connections.skills" 
                            label="Δεξιότητες"
                            :errors="errors.role"
                        ></BaseMultiselectInput>

                        <!-- Ενδιαφέροντα -->
                        <BaseMultiselectInput
                            :options="interestDropdownOptions" 
                            v-model="careerData.connections.interests" 
                            label="Ενδιαφέροντα"
                            :errors="errors.interests"
                        ></BaseMultiselectInput>

                        <!-- Μαθήματα -->
                        <BaseMultiselectInput
                            :options="courseDropdownOptions" 
                            v-model="careerData.connections.courses" 
                            label="Μαθήματα"
                            :errors="errors.courses"
                        ></BaseMultiselectInput>

                        <!-- Holland Codes -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <template v-for="code in careerData.hollandCodes" :key="code.id">
                                <BaseToggleSwitch 
                                    v-model="code.value"
                                    :label="code.code"
                                    class="mt-1"
                                />
                            </template>
                        </div>
                    </div>
                </div>
                

                <Button
                    @click="submitForm"
                    label="Αποθήκευση" 
                    raised 
                    class="mb-2 mr-2"
                />
            </form>
        </template>
    </AppPageWrapper>
</template>