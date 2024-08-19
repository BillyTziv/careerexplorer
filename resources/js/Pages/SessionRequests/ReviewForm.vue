<template>
    <div class="p-3 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-8 bg-white shadow">

            <section >
                <h2 class="font-semibold text-xl text-gray-800">
                    Αξιολόγησε την εμπειρία
                </h2>
                <p>
                    Βοήθησέ μας να βελτιωθούμε, αφιερώνοντας κάποια λεπτά στην αξιολόγηση της συνολικής εμπειρίας 
                </p>

                <form @submit.prevent="submit" autocomplete="off">

                    <div class="relative mt-5">
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Ποιοι ήταν οι κύριοι λόγοι που θέλησες να κάνεις συνεδρία;
                        </label>
                        <input v-model="reviewform.reason" type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-500 focus:border-lime-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-lime-500 dark:focus:border-lime-500" placeholder="Γράψε το όνομά σου.." required>
                    </div>



                    <!-- <div class="relative mt-5">
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Όνομα *
                        </label>
                        <input v-model="sessionRequest.firstName" type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-500 focus:border-lime-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-lime-500 dark:focus:border-lime-500" placeholder="Γράψε το όνομά σου.." required>
                    </div>

                    <div class="relative mt-5">
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Επίθετο *
                        </label>
                        <input v-model="sessionRequest.lastName" type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-500 focus:border-lime-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-lime-500 dark:focus:border-lime-500" placeholder="Γράψε το επίθετό σου.." required>
                    </div> -->


                    <!-- <div class="relative mt-5 mb-3">
                        <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                            Description *
                        </label>
                        <textarea v-model="careerValueData.description" id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-lime-500 focus:border-lime-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-lime-500 dark:focus:border-lime-500" placeholder="Short description fo the career value.." required></textarea>
                    </div> -->

                    <!-- <button type="submit" class="mt-2 flex items-center justify-start text-white bg-gradient-to-r from-lime-500 via-lime-600 to-lime-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-3 py-1.5 text-center">
                        <svg xmlns="https://www.w3.org/2000/svg" class="rot-90 h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Save Career Value
                    </button> -->

                    <!-- <div class="mt-5 bg-cyan-100 border-t-4 border-cyan-500 rounded-b text-cyan-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div>
                                <p class="font-light">Είσαι έτοιμ@ να κάνεις το <strong>επόμενο βήμα</strong>;</p>
                                <p class="text-sm">
                                    Το ήξερες οτι, μια απλή πράξη αυτοσεβασμού είναι να κάνεις οτι περνά από το χέρι σου για να μην αργήσεις στο επόμενο ραντεβού; Λοιπόν, τι λες; Θα τα καταφέρεις;
                                </p>
                            </div>
                        </div>
                    </div>
                     -->
                    <button class="mt-5 flex items-center justify-start text-white bg-gradient-to-r from-lime-500 via-lime-600 to-lime-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-3 py-2.5 text-center" type="submit">
                        <span class="px-2 py-1">Αποστολή Αξιολόγησης</span>
                    </button>

                </form>
            </section>

        </div>          
    </div>  
</template>

<script setup>
    import { router } from '@inertiajs/vue3';
    import { reactive } from 'vue';

    let props = defineProps({
        userId: Number
    });


    const form = reactive({
        id: props.userId,
        data: {

        }
    })

    function submit() {
        Inertia.post('/session-requests/', sessionRequest);
    }    
</script>

<style>
    .resultMessage {
        color: #155724;
    }

    .resultMessage .title {
        font-size: 1.4rem;
    }
</style>