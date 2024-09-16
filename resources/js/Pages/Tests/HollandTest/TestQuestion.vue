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
     <div class="px-4  pt-6">

          
    <div class="flex flex-column gap-2 justify-content-center align-items-center">
        <h3 class="text-center text-3xl sm:text-4xl md:text-5xl lg:text-2xl font-light text-white pt-6">
            {{ question.title }}
        </h3>
    </div>

    <div class="fixed bottom-0 left-0 w-full mb-8 flex justify-content-center">
        <div class="flex flex-column gap-3">
            <template
                v-for="answer in question.answers"
                :key="answer.id"
            >
                <Button
                    @click="selectAnswer(answer)"
                    type="button"
                    outlined
                    class=" no-outline flex flex-row text-center justify-content-center w-12rem text-white hover:bg-white-100 mx-2"
                >
                    {{ answer.label }}
                </Button>
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
</style>