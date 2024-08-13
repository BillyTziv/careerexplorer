<template>
    <PublicPageLayout>
        <div class="w-full p-4 text-center bg-white rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-lime-300">
                Share your experience and change the world.
            </h5>

            <p class="mb-5 text-2xl text-gray-500  dark:text-slate-300">
                A story can live forever, Inspire and encourage others to follow their dream.
                <!-- Μια αυθεντική εργασιακή εμπειρία, μπορεί να εμπνεύσει, να παρακινήσει, να λύσει απορίες να δημιουργήσει τους challegers of tommorow. Άφησε την δικιά σου εμπειρία με όποιον τρόπο σε βολεύει περισσότερο. -->
            </p>

            <template v-if="!selectedSubmitType">
                <div class="cursor-pointer items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">
                    <!-- Form Option -->
                    <div @click="selectedSubmitType = 'form'" class="w-full sm:w-auto bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 text-white rounded-lg inline-flex items-center justify-center px-4 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                        <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3" stroke="#bef264" class="w-7 h-7 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>

                        <div class="text-left rtl:text-right">
                            <div class="mb-1 text-xs">Συμπλήρωσε</div>
                            <div class="-mt-1 font-sans text-sm font-semibold">ερωτηματολόγιο</div>
                        </div>
                    </div>

                    <!-- Record Option -->
                    <div @click="selectedSubmitType = 'record'" class="cursor-pointer w-full sm:w-auto bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 text-white rounded-lg inline-flex items-center justify-center px-4 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                        <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3" stroke="#bef264" class="w-7 h-7 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                        </svg>
                        
                        <div class="text-left rtl:text-right">
                            <div class="mb-1 text-xs">Καταχώρησε</div>
                            <div class="-mt-1 font-sans text-sm font-semibold">ηχογράφησή</div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <template v-if="selectedSubmitType === 'form'">
           <TheFormCareerExperience
                errors: errors
           />
        </template>
        <template v-if="selectedSubmitType === 'record'">
            <TheRecordCareerExperience
                errors: errors
            />    
        </template>
    </PublicPageLayout>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { ref } from 'vue';

    /* Layout */
    import PublicPageLayout from '@/Components/Layouts/PublicPageLayout.vue';
    import TheRecordCareerExperience from '@/Components/CareerExperience/TheRecordCareerExperience';
    import TheFormCareerExperience from '@/Components/CareerExperience/TheFormCareerExperience';

    let props = defineProps({
        response: Object,
        user: Object,
        errors: Object
    });

    const selectedSubmitType = ref("");

    function submit() {
        Inertia.post('/career-experience/', careerExperience);
    }
</script>

