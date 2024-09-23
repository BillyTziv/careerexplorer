<script setup>
    import { computed } from 'vue';
    import { useHollandTestStore } from '@/Stores/useHollandTest.store';

    const props = defineProps({
        question: {
            type: Object,
            required: true
        }
    });

    const hollandTestStore = useHollandTestStore();

    function selectAnswer( answer ) {
        hollandTestStore.submitQuestion( answer.value );
    }

    const question = computed(() => props.question);
</script>

<template>
    <div class="px-4 pt-6">

        <div class="flex flex-column gap-2 justify-content-center align-items-center">
            <h3 class="text-center text-3xl sm:text-3xl md:text-4xl lg:text-5xl font-light text-white pt-6 sm:pt-10 md:pt-10 lg:mt-10">
                {{ question.title }}
            </h3>
        </div>

        <div class="absolute bottom-0 left-0 w-full mb-8 flex justify-content-center">
            <div class="flex flex-column gap-3">
                <template
                    v-for="answer in question.answers"
                    :key="answer.id"
                >
                    <Button 
                        @click="selectAnswer(answer)"
                        :label="answer.label"  
                        type="button" 
                        class="mr-2 mb-2 outlined-btn w-12rem text-xl sm:text-2xl md:text-2xl" 
                    />

                    <!-- <Button
                        
                        type="button"
                        outlined
                        class="no-outline flex flex-row text-center justify-content-center w-16rem text-white hover:bg-white-300 mx-2 text-xl sm:text-2xl "
                    >
                        {{  }}
                    </Button> -->
                </template>
            </div>
        </div>
        <!-- <TheTestQuestionsRobot /> -->
    </div>    
</template>

<style scoped>
    .no-outline {
        outline: none;
    }

    .no-outline:focus {
        outline: none;
    }

.outlined-btn {
    background-color: transparent;
    border: 2px solid;
    border-image: linear-gradient(to bottom right, #ff8a05, #ff5478, #ff00c6) 1;
    color: white; /* You can adjust the text color as needed */
    cursor: pointer;
    transition: border-image 0.5s ease, color 0.5s ease, transform 0.3s ease;
}

.outlined-btn:hover {
    border-image: linear-gradient(to bottom right, #ff00c6, #ff5478, #ff8a05) 1;
    color: white; /* Adjust text color on hover if you like */
    transform: scale(1.05);
}
</style>