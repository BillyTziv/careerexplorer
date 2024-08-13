<template>
    <PublicPage :is-dark="false"> <br />
        <section class="p-2 mt-10 dark:bg-slate-150">
            <PreviousPageButton />

            <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-800 mt-4">
                {{ career.title }}
            </h1>

            <BasePublicCareerDescription
                :text="career.description"
            />

            <BasePublicCareerInterests
                v-if="hasInterests" 
                :interests="career.interests"
            />

            <BasePublicCareerSkills
                v-if="hasSkills" 
                :skills="career.skills"
            />

            <BasePublicCareerResponsibilities
                v-if="hasResponsibilities" 
                :responsibilities="career.responsibilities"
            />

            <BasePublicCareerCourses
                v-if="hasCourses" 
                :courses="career.courses"
            />

            <div v-show="career.link" class="relative w-full mb-2 py-3">
                <a class="font-medium text-sky-600 dark:text-sky-500 hover:underline" target="_blank" :href="career.link">
                    Μάθε περισσότερα
                </a>
            </div>
        </section>
    </PublicPage>
</template>

<script setup>
    import { computed } from 'vue';

    import PreviousPageButton from '@/Pages/Common/PreviousPageButton.vue';
    import PublicPage from '@/Pages/Common/PublicPage.vue';
    import BasePublicCareerDescription from '@/Components/Career/BasePublicCareerDescription.vue';
    import BasePublicCareerCourses from '@/Components/Career/BasePublicCareerCourses.vue';
    import BasePublicCareerResponsibilities from '@/Components/Career/BasePublicCareerResponsibilities.vue';
    import BasePublicCareerInterests from '@/Components/Career/BasePublicCareerInterests.vue';
    import BasePublicCareerSkills from '@/Components/Career/BasePublicCareerSkills.vue';
    
    let props = defineProps({
        career: Object,
        response: Object
    });

    const hasCourses = computed(() => props.career.courses?.length > 0);
    const hasResponsibilities = computed(() => props.career.responsibilities?.length > 0);
    const hasInterests = computed(() => props.career.interests?.length > 0);
    const hasSkills = computed(() => props.career.skills?.length > 0);

</script>