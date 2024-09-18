<script setup>
    import { ref, computed, reactive, onMounted } from 'vue';

    // import { useForm } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    import BaseTabHeader from '@/Layouts/Tab/BaseTabHeader.vue';
    import BaseTabContent from '@/Layouts/Tab/BaseTabContent.vue';
	import { useToastNotification } from '@/Composables/useToastNotification';

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

	const { notify } = useToastNotification();

    const completionProgress = computed(() => {
        let progress = 0;

        if( careerData.title.length > 0 ) progress += 10;
        if( careerData.keywords.length > 0 ) progress += 10;
        if( careerData.description.length > 0 ) progress += 10;
        if( careerData.connections.skills.length > 0 ) progress += 10;
        if( careerData.connections.interests.length > 0 ) progress += 10;
        if( careerData.connections.courses.length > 0 ) progress += 10;
        if( careerData.connections.universities.length > 0 ) progress += 10;
        if( careerData.responsibilities.length > 0 ) progress += 10;
        if( careerData.hollandCodes.length > 0 ) progress += 10;
        if( careerData.link.length > 0 ) progress += 10;

        return progress;
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
        isPopular: props.career.is_popular ? true : false,
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
        // In case careerData.responsibilities has empty values, remove the key from the object
        careerData.responsibilities = careerData.responsibilities.filter((resp) => resp.text.trim().length > 0);

        if( careerData.id && careerData.id > 0 ) {
            router.put( '/careers/'+ careerData.id, careerData, { 
                preserveState: true, 
                replace: true, 
                onSuccess: () => {
                    notify('success', 'Ολοκληρώθηκε', ` Η επεξεργασία του επαγγέλματος ολοκληρώθηκε με επιτυχία!`);
                }
            });

        }else {
            router.post( '/careers/', careerData, { 
                preserveState: true, 
                replace: true, 
                onSuccess: () => {
                    notify('success', 'Ολοκληρώθηκε', `Η δημιουργία του επαγγέλματος ολοκληρώθηκε με επιτυχία!`);
                }
            });
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
            Επαγγέλματος
        </template>

        <template #page-content>
            <ProgressBar
                :value="completionProgress"
                class="mb-4"
            />

            <form @submit.prevent="submit" autocomplete="off">
                <!-- Title -->
                <BaseTextInput
                    v-model="careerData.title"
                    label="Τίτλος Επαγγέλματος"
                    placeholder="Προγραμματιστής Λογισμικού"
                    :errors="errors.title"
                />

                <!-- Related Keywords -->
                <BaseTextInput
                    v-model="careerData.keywords"
                    label="Σχετικές Λέξεις - Κλειδιά"
                    placeholder="Προγραμματιστής, Developer, Μηχανικός Λογισμικού"
                    hint="Πως αλλιώς θα μπορούσε να αναζητήσει κανείς το επάγγελμα (συμπλήρωσε λέξεις χωρισμένες με κόμμα);"
                    :errors="errors.keywords"
                />

                <!-- Popular -->
                <BaseToggleSwitch
                    v-model="careerData.isPopular"
                    label="Ορισμός ως Δημοφιλές Επάγγελμα"
                />

                <!-- Description -->
                <BaseTextareaInput
                    v-model="careerData.description"
                    label="Συνοπτική Περιγραφή"
                    :rows="10"
                    :errors="errors.description"
                />

                <!-- LINK -->
                <BaseTextInput
                    v-model="careerData.link"
                    label="Σύνδεσμος προς το FutureGeneration (αν υπάρχει)"
                    type="text"
                    :errors="errors.keywords"
                />
                <div class="my-4">
                    <small class="ml-2">
                        Κάνε click <a :href="`https://www.google.com/search?q=site:futuregeneration.gr+επάγγελμα+${careerData.title}`" target="_blank"><strong>εδώ</strong></a> και να δεις αν υπάρχει σχετικό δημοσιευμένο επάγγελμα.
                    </small>
                </div>

                <div class="ml-2">
                    <label class="block mb-2 text-md">
                        Σχετικές Αρμοδιότητες (σε τυχαία σειρά)
                    </label>
            
                    <template v-for="(resp, index) in careerData.responsibilities" :key="index">
                        <div class="flex">
                        
                            <Button
                                @click.prevent="removeResponsibility(index)"
                                icon="pi pi-times"
                                severity="danger"
                                text
                                class="mb-3"
                                v-tooltip.bottom="'Αφαίρεση αρμοδιότητας'"
                            />

                            <div class="w-full">
                                <BaseTextInput
                                    v-model="resp.text"
                                    :errors="errors.responsibility"
                                    :placeholder="`Με τι άλλο μπορεί να ασχολείται ένας ${ careerData.title };`"
                                />
                            </div>
                        </div>
                    </template>

                     <!-- Career Responsibilities -->
                    <Button
                        @click.prevent="addCareerResposibility"
                        label="Επιπλέον Αρμοδιότητας"
                        icon="pi pi-plus"
                        class="mt-2"
                        outlined
                    />
                </div>

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
                <div class="ml-2">
                    <label class="block mb-2 text-md ">
                        Σχετικοί Holland Codes
                    </label>

                    <div class="formgrid grid">
                        <div class="field">
                            <template v-for="code in careerData.hollandCodes" :key="code.id">
                                <BaseToggleSwitch 
                                    v-model="code.value"
                                    :label="code.name"
                                    class="w-full"
                                />
                            </template>
                        </div>
                    </div>
                </div>
                <Button
                    @click="submit"
                    label="Αποθήκευση Επαγγέλματος" 
                    raised 
                    class="mb-2 mr-2"
                />
            </form>
        </template>
    </AppPageWrapper>
</template>