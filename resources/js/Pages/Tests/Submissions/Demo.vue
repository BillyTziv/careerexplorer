<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { ref } from 'vue';
    import PublicPage from '@/Pages/Common/PublicPage.vue';

    import BasePileCheckbox from '@/Pages/Common/UI/Form/BasePileCheckbox.vue';
    import BaseBarLine from '@/Pages/Common/UI/Form/BaseBarLine.vue';

    let props = defineProps({
        careers: Object,
        skills: Array,
        interests: Array,
        values: Array,
        hollandCodes: Array,
        response: Object,
        userId: Number,
        testSubmissionId: String,
        hollandCodeId: Array
    });

    const step = ref(1);
    const requestSessionFromCoach = ref( false );

    function requestCareerSession( withSessionRequest ) {
        requestSessionFromCoach.value = withSessionRequest;
        step.value = 4;
    }

    function createCareerPlan() {
        Inertia.post('/tests/holland/submitMetaData', {
            user: props.userId,
            test: props.testSubmissionId,
            hollandCodeId: props.hollandCodeId,
            acceptSession: requestSessionFromCoach,
            testMetaData: {
                skills: props.skills,
                interests: props.interests,
                values: props.values
            }
        })
    }
</script>

<template>
    <PublicPage :is-dark="true">

        <transition name="fade">
            <div v-if="step == 1" class="flex items-center justify-center min-h-screen">
                <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.8" stroke="#84cc16" class="w-24 h-24  inline-block mb-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                    </div>

                    <h1 class="mb-4 text-3xl sm:text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-slate-100">
                        Μπράβο, ολοκλήρωσες το test με επιτυχία!
                    </h1>

                    <!-- <h1 class="mb-4 text-2xl sm:text-4xl font-extrabold tracking-tight leading-none text-lime-800 md:text-5xl lg:text-2xl dark:text-lime-400">
                        Μπράβο, ολοκλήρωσες το test με επιτυχία!
                    </h1> -->

                    <p class="dark:text-slate-300 text-center text-md my-5">
                        Βρίσκεσαι ένα βήμα πριν την απόκτηση του <strong>προσωπικού σου πλάνου, που θα σε βοηθήσει να εξέλιξεις την καριέρα σου</strong>.
                    </p>

                    <p class="dark:text-slate-300 text-center text-md">
                        Τα επόμενα βήματα, θα σε βοηθήσουν να δημιουργήσεις το επαγγελματικό μονοπάτι καριέρας που ταιριάζει στην προσωπικότητά σου.
                    </p>

                    <div class="mt-5 flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                        <button @click="step = 2" type="submit" class="relative p-0.5 inline-flex items-center justify-center font-bold overflow-hidden group rounded-md">
                            <span class="w-full h-full bg-gradient-to-br from-[#ff00c6] via-[#ff5478] to-[#ff8a05] group-hover:from-[#ff8a05] group-hover:via-[#ff5478] group-hover:to-[#ff00c6] absolute"></span>
                            <span class="relative px-6 py-3 transition-all ease-out bg-opacity-0 rounded-md group-hover:bg-gray-900 duration-400">
                                <span class="relative text-white">Ας ξεκινήσουμε!</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <transition name="fade">
            <div v-if="step == 2" class="flex items-center justify-center min-h-screen">
                <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12"> <br/>
                    <h2 class="dark:text-white text-3xl text-center font-bold my-5">
                        Η προσωπικότητά σου
                    </h2>

                    <p class="dark:text-slate-300 text-center text-sm my-5">
                        Σύμφωνα με τις απαντήσεις που έδωσες, η προσωπικότητά σου αναλύεται στις παρακάτω 3 επικρατέστερες κατηγορίες. Αυτό δεν πρέπει να το λάβεις ως περιορισμό, γιατί μόνο η φαντασία και θέληση ορίζει τους περιορισμους. Ωστόσο, ενδεχομένως να δείχνει μια κλίση, ένα ενδιαφέρον ή ίσως κάτι που καλλιεργείς χρόνια και δεν ετυχε να το αντιληφθείς.
                    </p>

                    <ul class="flex flex-col">
                        <li v-for="code in hollandCodes" :key=code.id class="my-2">
                            <base-bar-line
                                :value="code.value"
                                :name="code.name"
                                :description="code.description"
                                :color="code.color"
                            ></base-bar-line>
                        </li>
                    </ul>

                    <div class="mt-5 flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                        <button @click="step = 3" type="submit" class="relative p-0.5 inline-flex items-center justify-center font-bold overflow-hidden group rounded-md">
                            <span class="w-full h-full bg-gradient-to-br from-[#ff00c6] via-[#ff5478] to-[#ff8a05] group-hover:from-[#ff8a05] group-hover:via-[#ff5478] group-hover:to-[#ff00c6] absolute"></span>
                            <span class="relative px-6 py-3 transition-all ease-out bg-opacity-0 rounded-md group-hover:bg-gray-900 duration-400">
                                <span class="relative text-white">Συνέχεια</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <transition name="fade">
            <div v-if="step == 3" class="flex items-center justify-center min-h-screen">
                <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12"> <br/>
                    <h2 class="dark:text-white text-3xl text-center font-bold my-5">
                        Δωρεάν Ραντεβού Συμβουλευτικής
                    </h2>

                    <p class="dark:text-slate-300 text-center text-sm my-5">
                        Θέλεις να κλείσεις ένα πρώτο δωρεάν ραντεβού συμβουλευτικής και επαγγελματικού προσανατολισμού με τα μέλη της ομάδας μας; Δεν έχεις να χάσεις τίποτα!
                    </p>

                    <div class="mt-5 flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                        <button @click="requestCareerSession( true )" type="submit" class="relative p-0.5 inline-flex items-center justify-center font-bold overflow-hidden group rounded-md">
                            <span class="w-full h-full bg-gradient-to-br from-[#ff00c6] via-[#ff5478] to-[#ff8a05] group-hover:from-[#ff8a05] group-hover:via-[#ff5478] group-hover:to-[#ff00c6] absolute"></span>
                            <span class="relative px-6 py-3 transition-all ease-out bg-opacity-0 rounded-md group-hover:bg-gray-900 duration-400">
                                <span class="relative text-white">Ναι, θα το ήθελα</span>
                            </span>
                        </button>

                        <button @click="requestCareerSession( false )" type="submit" class="relative p-0.5 inline-flex items-center justify-center font-bold overflow-hidden group rounded-md">
                            <span class="w-full h-full bg-gradient-to-br from-[#ff00c6] via-[#ff5478] to-[#ff8a05] group-hover:from-[#ff8a05] group-hover:via-[#ff5478] group-hover:to-[#ff00c6] absolute"></span>
                            <span class="relative px-6 py-3 transition-all ease-out bg-opacity-0 rounded-md group-hover:bg-gray-900 duration-400">
                                <span class="relative text-white">Όχι, ευχαριστώ</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <transition name="fade">
            <section v-if="step == 4" class="flex items-center justify-center min-h-screen">
                <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12"> <br/>
                    <h2 class="dark:text-white text-3xl text-center font-bold my-5">
                        Τα ενδιαφέροντά σου
                    </h2>

                    <p class="dark:text-slate-300 text-center text-sm my-5">
                        Μπορείς να αφαιρέσεις τα ενδιαφέροντά που πιστεύεις οτι δεν σου ταιριάζουν. Αυτό θα βοηθήσει το σύστημα να σου προτείνει τον κατάλληλο τομέα για την καριέρα σου.
                    </p>

                    <ul class="grid w-full gap-2 md:grid-cols-3 justify-start tile-list">
                        <li v-for="interest in interests" :key=interest.id>
                            <base-pile-checkbox
                                v-model="interest.isChecked"
                                :label="interest.name"
                                :description="interest.description"
                                @update:modelValue="interest.isChecked = $event"
                            ></base-pile-checkbox>
                        </li>
                    </ul>

                    <div class="mt-5 flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                        <button @click="step = 5" type="submit" class="relative p-0.5 inline-flex items-center justify-center font-bold overflow-hidden group rounded-md">
                            <span class="w-full h-full bg-gradient-to-br from-[#ff00c6] via-[#ff5478] to-[#ff8a05] group-hover:from-[#ff8a05] group-hover:via-[#ff5478] group-hover:to-[#ff00c6] absolute"></span>
                            <span class="relative px-6 py-3 transition-all ease-out bg-opacity-0 rounded-md group-hover:bg-gray-900 duration-400">
                                <span class="relative text-white">Συνέχεια</span>
                            </span>
                        </button>
                    </div>
                </div>
            </section>
        </transition>

        <transition name="fade">
            <section v-if="step == 5" class="flex items-center justify-center min-h-screen">
                <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12"> <br/>
                    <h2 class="dark:text-white text-3xl text-center font-bold my-5">
                        Τα προσόντα σου
                    </h2>

                    <p class="dark:text-slate-300 text-center text-sm my-5">
                        Μπορείς να αφαιρέσεις τα προσόντα που πιστεύεις οτι δεν σου ταιριάζουν. Αυτό θα βοηθήσει το σύστημα να σου προτείνει τον κατάλληλο τομέα για την καριέρα σου.
                    </p>

                    <ul class="grid w-full gap-2 md:grid-cols-3 justify-start tile-list">
                        <li v-for="skill in skills" :key=skill.id>
                            <base-pile-checkbox
                                v-model="skill.isChecked"
                                :label="skill.name"
                                :description="skill.description"
                                @update:modelValue="skill.isChecked = $event"
                            ></base-pile-checkbox>
                        </li>
                    </ul>

                    <div class="mt-5 flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                        <button @click="step = 6" type="submit" class="relative p-0.5 inline-flex items-center justify-center font-bold overflow-hidden group rounded-md">
                            <span class="w-full h-full bg-gradient-to-br from-[#ff00c6] via-[#ff5478] to-[#ff8a05] group-hover:from-[#ff8a05] group-hover:via-[#ff5478] group-hover:to-[#ff00c6] absolute"></span>
                            <span class="relative px-6 py-3 transition-all ease-out bg-opacity-0 rounded-md group-hover:bg-gray-900 duration-400">
                                <span class="relative text-white">Συνέχεια</span>
                            </span>
                        </button>
                    </div>
                </div>
            </section>
        </transition>

        <transition name="fade">
            <section v-if="step == 6" class="flex items-center justify-center min-h-screen">
                <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12"> <br/>
                    <h2 class="dark:text-white text-3xl text-center font-bold my-5">
                        Οι αξίες μου
                    </h2>

                    <p class="dark:text-slate-300 text-center text-sm my-5">
                        Μπορείς να αφαιρέσεις τις αξίες που πιστεύεις οτι δεν σου ταιριάζουν. Αυτό θα βοηθήσει το σύστημα να σου προτείνει τον κατάλληλο τομέα για την καριέρα σου.
                    </p>

                    <ul class="grid w-full gap-2 md:grid-cols-3 justify-start tile-list">
                        <li v-for="value in values" :key=value.id>
                            <base-pile-checkbox
                                v-model="value.isChecked"
                                :label="value.name"
                                :description="value.description"
                                @update:modelValue="value.isChecked = $event"
                            ></base-pile-checkbox>
                        </li>
                    </ul>

                    <div class="mt-5 flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                        <button @click="createCareerPlan" type="submit" class="relative p-0.5 inline-flex items-center justify-center font-bold overflow-hidden group rounded-md">
                            <span class="w-full h-full bg-gradient-to-br from-[#ff00c6] via-[#ff5478] to-[#ff8a05] group-hover:from-[#ff8a05] group-hover:via-[#ff5478] group-hover:to-[#ff00c6] absolute"></span>
                            <span class="relative px-6 py-3 transition-all ease-out bg-opacity-0 rounded-md group-hover:bg-gray-900 duration-400">
                                <span class="relative text-white">Δημιουργία Πλάνου Καριέρας</span>
                            </span>
                        </button>
                    </div>
                </div>
            </section>
        </transition>
    </PublicPage>
</template>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.5s;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    .tile-list li {
        text-align: left;
    }
</style>