<script setup>
    import CenterScreen from '@/Components/Layouts/CenterScreen.vue';
    import BaseRatingComponent from '@/Components/BaseRatingComponent.vue';
</script>

<template>
    <CenterScreen>
        <section class="bg-white dark:bg-gray-900">
            <div class="px-4 mx-auto max-w-screen-xl text-center lg:px-12">

                <!-- <div class="dark:bg-lime-300 dark:bg-opacity-20 inline-flex justify-between items-center py-1 px-3 pr-4 text-sm rounded-full">

                    <span class="text-sm font-medium text-lime-800 dark:text-lime-300">
                        <strong>no1</strong> test καριέρας στην ελλάδα                 
                        <BaseRatingComponent :rating="5" />
                    </span> 
                </div> -->

                <h1 class="mb-4 text-3xl sm:text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-slate-100">
                    Test Επαγγελματικού Προσανατολισμού
                </h1>
                <p class="mb-8 text-xl font-normal text-gray-500 lg:text-2xl sm:px-16 xl:px-48 dark:text-gray-400">
                    powered by <strong class="highlight-text">AI</strong>, supported by 
                    <strong>
                        <a
                            href="https://futuregeneration.gr/"
                            target="_blank"
                            class="highlight-text"
                        >
                            FutureGeneration
                        </a>
                    </strong>
                  
                </p>

                <div class="flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                    <!-- <a href="#" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                        Learn more
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </a> -->
                   
                    <a @click="$emit('start-test')" href="#_" class="relative p-0.5 inline-flex items-center justify-center font-bold overflow-hidden group rounded-md">
                        <span class="w-full h-full bg-gradient-to-br from-[#ff00c6] via-[#ff5478] to-[#ff8a05] group-hover:from-[#ff8a05] group-hover:via-[#ff5478] group-hover:to-[#ff00c6] absolute"></span>
                        <span class="relative px-6 py-3 transition-all ease-out bg-opacity-0 rounded-md group-hover:bg-gray-900 duration-400">
                            <span class="relative text-white">Ξεκίνα το test</span>
                        </span>
                    </a>
                </div>
            </div>
        </section>
    </CenterScreen>
</template>

<style scoped>
    .highlight-text {
        background-image: linear-gradient(to bottom right, #ff8a05, #ff5478, #ff00c6); 
        -webkit-background-clip: text; 
        color: transparent; 
        text-decoration: none;
    }
</style>