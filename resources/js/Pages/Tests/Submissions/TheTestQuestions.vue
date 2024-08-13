<script setup>
    import { ref, onMounted, watch } from 'vue';
    import TheTestQuestionsRobot from './TheTestQuestionsRobot.vue';
    import TheTestProgressBar from './TheTestProgressBar.vue';

    const emit = defineEmits([
        'submit-answers'
    ]);

    const props = defineProps({
        testQuestions: {
            type: Array,
            required: true,
            default: []
        }
    });

    const isReady = ref( false );
    const QUESTION_CHARACTER_TIMEOUT = 15;
    const testAnswers = ref( [] );
    const availableAnswers = ref([]);
    const totalQuestions = props.testQuestions.length;
    const currentQuestionIndex = ref( 0 );
    const showAnswerButtons = ref( false );
    const answers = [
        { label: 'Πάρα πολύ', value: 5 },
        { label: 'Αρκετά', value: 4 },
        { label: 'Δεν ξέρω', value: 3 },
        { label: 'Λίγο', value: 2 },
        { label: 'Καθόλου', value: 1 }
    ];

    function selectAnswer( currentAnswerValue ) {
        showAnswerButtons.value = false;
        isReady.value = false;

        testAnswers.value.push( {
            id: props.testQuestions[currentQuestionIndex.value].id,
            answer: currentAnswerValue
        });

        currentQuestionIndex.value++;

        if( currentQuestionIndex.value === totalQuestions ) {
            emit('submit-answers', testAnswers.value);
        }

        setTimeout(() => {
            initTyped();
        }, 500);
    }

    const initTyped = () => {
        // Inject a hidden question.
        injectHiddenQuestion( props.testQuestions[currentQuestionIndex.value].title );
      
        // Start typing the question
        highlightQuestionCharacters();
    };

    function injectHiddenQuestion( rawQuestion ) {
        const hiddenQuestionText = Array.from( rawQuestion ).map(letter => `<span class="dark:text-slate-900">${letter}</span>`).join('');
        document.querySelector('#typing-effect').innerHTML = hiddenQuestionText;
    }

    const highlightQuestionCharacters = () => {
        // Timeout for each character to show up.
        const spans = document.querySelectorAll('#typing-effect span');

        // Change the color of each span with a delay
        spans.forEach((span, i) => {
            setTimeout(() => {
                span.className = 'dark:text-white';
            }, i * QUESTION_CHARACTER_TIMEOUT);
        });

        // Show the answer buttons after the question is fully typed out.
        setTimeout(() => {
            showAnswerButtons.value = true;
        }, spans.length * QUESTION_CHARACTER_TIMEOUT);
    };

    onMounted(() => {
        initTyped();
    })

    watch(() => showAnswerButtons.value, (newValue, oldValue) => {
        if( !showAnswerButtons.value ) {
            if( availableAnswers.value.length === 0 ) return;

            for (let index = availableAnswers.value.length - 1; index >= 0; index--) {
                setTimeout(() => {
                    availableAnswers.value.splice(index, 1);
                }, (availableAnswers.value.length - 1 - index) * 100);
            }
        }else {
            answers.forEach((answer, index) => {
                setTimeout(() => {
                    availableAnswers.value.splice(index, 0);
                    availableAnswers.value.push(answer);
                }, index * QUESTION_CHARACTER_TIMEOUT);
            });

            // Show the answer buttons after the question is fully typed out.
            setTimeout(() => {
                isReady.value = true;
            }, answers.length * QUESTION_CHARACTER_TIMEOUT);
        }
    });
</script>

<template>
        <div class="p-4 flex flex-col items-center justify-center h-screen">
            <TheTestProgressBar
                :current-value="currentQuestionIndex"
                :total-value="totalQuestions"
            />
            
            <!-----------------------------------------------------------
            | The question is typed out using the Typed.js library.    |
            | The answer buttons are displayed after the question is   |
            | fully typed out.                                         |
            ----------------------------------------------------------->
            <div class="text-2xl font-light text-center dark:text-white text-slate-800 my-5">
                <div id="typing-effect"></div>
            </div>

            <!-----------------------------------------------------------
            | The answer buttons are displayed after the question is   |
            | fully typed out.                                         |
        ----------------------------------------------------------->
            <TransitionGroup name="question-select-btn" tag="div" class="min-h-[400px] flex flex-col gap-2 justify-start items-center">
                <button
                    v-for="(answer, index) in availableAnswers"
                    :key="answer"
                    @click="selectAnswer(answer.value)"
                    :disabled="!isReady"
                    class="relative p-0.5 sm:p-0.2 inline-flex items-center justify-center font-bold overflow-hidden group rounded-md w-48 sm:w-64 whitespace-nowrap"
                >
                    <span class="w-full h-full bg-gradient-to-br from-[#ff8a05] via-[#ff5478] to-[#ff00c6] group-hover:from-[#ff00c6] group-hover:via-[#ff5478] group-hover:to-[#ff8a05] absolute"></span>
                    <span class="dark:text-white text-slate-800 relative px-1 py-2 sm:py-3 w-full transition-all ease-out bg-slate-900 rounded-md group-hover:bg-opacity-0 duration-400">
                        {{ answer.label }}
                    </span>
                </button>
            </TransitionGroup>
        </div>
    <TheTestQuestionsRobot />
</template>

<style scoped>
    /* Question Typing effect */
    #typing-effect .typing {
        animation: typing 2s steps(1, end) forwards;
        animation-delay: calc(0.05s * var(--i)); /* Delay based on index */
    }

    @keyframes typing {
        0% { color: transparent; }
        100% { color: inherit; }
    }

    /* Animation for the question answer buttons */
    .question-select-btn-enter-active,
    .question-select-btn-leave-active {
        transition: all 0.5s ease;
    }
    .question-select-btn-enter-from,
    .question-select-btn-leave-to {
        opacity: 0;
        transform: translateX(30px);
    }


    /* .slide-fade-enter-active, .slide-fade-leave-active {
        transition: transform 0.5s ease, opacity 0.5s ease;
    }
    .slide-fade-enter, .slide-fade-leave-to {
        transform: translateX(10%);
        opacity: 0;
    } */
</style>