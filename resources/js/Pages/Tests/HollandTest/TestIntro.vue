<script setup>
    import { computed } from 'vue';
    import { useHollandTestStore } from '@/Stores/useHollandTest.store';

    const hollandTestStore = useHollandTestStore();

    function startTest () {
        hollandTestStore.startTest();
    }

    const isPreparingTest = computed(() => hollandTestStore.isPreparingTest);
</script>

<template>
    <div class="flex justify-content-center align-items-center h-screen flex-column p-5">
        <div class="flex flex-column gap-2">
 
            <h1 class="text-4xl sm:text-4xl md:text-5xl lg:text-7xl font-extrabold text-white">
                Test Επαγγελματικού Προσανατολισμού
            </h1>

            <span class="mb-8 text-xl font-normal text-gray-200 lg:text-2xl sm:px-16 xl:px-48">
                supported by 
                <strong>
                    <a
                        href="https://futuregeneration.gr/"
                        target="_blank"
                        class="highlight-text"
                    >
                        FutureGeneration
                    </a>
                </strong><br/>
                powered by <strong class="highlight-text">AI</strong>.
            </span>

            <Button 
                @click="startTest"
                label="Ξεκίνα το test"  
                type="button" 
                class="mr-2 mb-2 highlight-btn w-12rem" 
                icon="pi pi-arrow-right" 
                iconPos="right"
                :loading="isPreparingTest"
            />
        </div>
    </div>
</template>

<style scoped>
    .highlight-text {
        background-image: linear-gradient(to bottom right, #ff8a05, #ff5478, #ff00c6); 
        -webkit-background-clip: text; 
        color: transparent; 
        text-decoration: none;
    }

    .highlight-btn {
        background-image: linear-gradient(to bottom right, #ff8a05, #ff5478, #ff00c6); 
        border: none;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        transition: background-image 0.5s ease, transform 0.3s ease;
    }

    .highlight-btn:hover {
        background-image: linear-gradient(to bottom right, #ff00c6, #ff5478, #ff8a05);
        transform: scale(1.05);
    }
</style>