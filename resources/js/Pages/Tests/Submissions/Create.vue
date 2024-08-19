<template>
        <transition name="fade">
            <TheHollandTestHeroSection
                v-if="testStatus === 'not-started'"
                @start-test="testStatus = 'in-progress'"
            />
        </transition>

        <TheTestQuestions
            v-if="testStatus === 'in-progress'"
            :test-questions="randomizedQuestions"
            @submit-answers="submitAnswers"
        />
        
        <transition name="fade">
            <TheTestSubmitForm
                v-if="testStatus === 'submit-form'"
                :test="testPayload.test"
                @submit-form="submitForm"
            />
        </transition>
</template>
  
<script setup>
    import { ref, reactive, computed } from 'vue';
    import TheTestSubmitForm from './TheTestSubmitForm.vue';
    import TheTestQuestions from './TheTestQuestions.vue';
    import TheHollandTestHeroSection from './TheHollandTestHeroSection.vue';
    import { router } from '@inertiajs/vue3';

    const props = defineProps({
        response: Object,
        test: Object,
        content: Object
    });

    const randomizedQuestions = computed(() => {
        let arr = [...props.test.questions];

        for(let i = arr.length - 1; i > 0; i--) {
            console.log( arr[i])
            const j = Math.floor(Math.random() * i)
            const temp = arr[i]
            arr[i] = arr[j]
            arr[j] = temp
        }
        return arr;
    });

    // Used to keep track of the current test status.
    // [ not-started > in-progress > submit-form ]
    const testStatus = ref('not-started');

    const testPayload = reactive({
        user: null,
        test: props.test,
        answers: []
    });

    function submitAnswers( answers ) {
        testPayload.answers = answers;
        testStatus.value = 'submit-form';
    }

    function submitForm( userForm ) {
        testPayload.user = userForm;
        testStatus.value = 'completed';

        Inertia.post('/tests/holland/submit', testPayload)
    }

    // Used as an indicator to keep the current question index.
    // const testLength = props.test.questions.length;
    // const questionIndex = ref(0)

    // const showQuestionsSection = computed(() => {
    //     return questionIndex < testLength;
    // });

    // const showFormSection = computed(() => {
    //     return questionIndex === testLength;
    // });
</script>
  
<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}


@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap');

/**************************************************************
* GLOBAL
**************************************************************/
:root {
    --main-color: #1e067f;
    --main-color-hover: rgba(30, 6, 127, 0.85);

    --main-color-light: rgba(30, 6, 127, 0.15);
    --main-color-light-hover: rgba(30, 6, 127, 0.20);

    --main-super-light-color: rgba(30, 6, 127, 0.1);
    --secondary-color: #C22356;
    --white-color: #f0f0f0;
}


body {
    background: #f1f1f1;
}

.d-none { display: none; }

.wrapper {
    max-width: 150px;
    margin: auto;
}

.sectionBlock {
    padding: 50px;
    max-width: 800px;
}

/**************************************************************
* BUTTONS
**************************************************************/

/* Styles for all the buttons */
.fg-btn {
    transition: all 0.5s ease;
    border-radius: 7px;
    padding: 10px 20px;
    font-size: 1rem;
}

.fg-btn:hover {
    transition: all 0.5s ease;
}

/* Primary Purple/Blue Button */
.bg-fg-primary { background: var(--main-color); }

/* Secondary Roz Button */
.bg-fg-secondary { background: var(--secondary-color); }

.fg-btn-primary {
    background-color: var(--main-color) !important;
    transition: all 0.5s ease;
    border-radius: 7px;
    padding: 10px 20px;
    font-size: 1rem;
    color: var(--white-color) !important;
}

.fg-btn-primary:focus, .fg-btn-primary:hover {
    background-color: var(--main-color-hover) !important;
    transition: all 0.5s ease;
}

.fg-btn-bordered {
    background-color: white;
    transition: all 0.5s ease;
    border-radius: 7px;
    padding: 7px 10px;
    font-size: 1rem;
    color: var(--main-color);

    border: solid 1px rgba(30, 6, 127, 0.3);
}


.fg-btn-light {
    background-color:var(--main-color-light);
    transition: all 0.5s ease;
    color: var(--main-color);
    border-radius: 7px;
    padding: 10px 15px;
    font-size: 1rem;
    border: none;
  
}

.fg-btn-light:focus, .fg-btn-light:hover {
    background-color:var(--main-color-light-hover);
    transition: all 0.5s ease;
}


.fg-btn-back {
    background-color: rgb(245, 245, 245);
    transition: all 0.5s ease;
    color: rgb(100, 100, 100);
    border-radius: 7px;
    padding: 5px 12px;
    font-size: 1rem;
    border: solid 1px transparent;
}

.fg-btn-back:focus, .fg-btn-back:hover {
    background-color: rgb(250, 250, 250);
    transition: all 0.5s ease;
    border: solid 1px rgb(225, 225, 225);
}


.form-label, .form-control, .form-check-label {
    font-size: 1.1rem !important;
    color: rgb(70, 70, 70) !important;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
}

.alert  {
    font-size: 1.1rem !important;
}

p {
    font-size: 1.2rem;
    color:#323232;
    font-family: 'Roboto', sans-serif;
    font-weight: 300;
}

/* Social Media Images */
.social-link img {
    animation: beat .42s infinite alternate;
}

@keyframes beat {
    to { transform: scale(1.05); }
}

/**************************************************************
* QUESTIONS
**************************************************************/
.question_header {
    color: rgba(30, 6, 127, 0.7);
    font-weight: bold;
}

.question {
    color:#323232;
    font-family: 'Roboto', sans-serif;
    font-weight: 300 !important;
}

.test-benefits {
    list-style-type: none;
    padding-left: 0px;
}

.test-benefits li {
    font-size: 1.2rem;

}

.test-benefits li:before {
    content: '✓';
    font-size: 1.8rem;
    color: green;
}




.progress {
    background: rgba(30, 6, 127, 0.1);
    justify-content: flex-start;
    border-radius: 100px;
    align-items: center;
    position: relative;
    padding: 0px;
    display: flex;
    height: 12px;
  }
  
  .progress-value {
    /* animation: load 3s normal forwards; */
    box-shadow: 0 10px 40px -10px rgba(30, 6, 127, 1);
    border-radius: 100px;
    background: rgba(30, 6, 127, 1);
    height: 100%;
    width: 0%;
  }
  
  /* @keyframes load {
    0% { width: 0; }
    100% { width: 68%; }
  } */



.gridBoxWrapper {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    grid-gap: 25px;
}
  
.gridBoxWrapper .gridBoxContainer {
    position:relative;
    background-color: #fff;
    box-shadow: 0px 0px 7px 1px  rgba(30, 6, 127, 0.15);
    color: rgba(30, 6, 127, 1);
    border-radius: 12px;
    padding: 20px;
    font-size: 120%;
}

.gridBoxWrapper .gridBoxContainer .svgContainer {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translate(-50%, -50%);
    background:  rgba(30, 6, 127, 0.1);
    border-radius: 100px;
    padding: 18px;
}

.gridBoxWrapper .gridBoxContainer .titleHeader {
    text-align: center;
    color:  #1e067f;
    padding: 0px;

    margin: auto;
    margin-top: 10px;
}

.gridBoxWrapper .gridBoxContainer .gridBoxValue {
    text-align: center;
  
    font-size: 26px;
    font-weight: bold;
}

/* Soon Badge */
.menu-badge {
	font-size: 10px;
	margin-left: 4px;
	position: relative;
	/* top: -10px; */
	margin-bottom: 13px;
	color: #ffffff;
	background-color: red;
	padding: 5px 7px;
	border-radius: 12px;
}

.badge-bounce {
	/* animation: bouncing .8s cubic-bezier(0.1,0.05,0.05,1) 0s infinite alternate both; */
}

/* @keyframes bouncing{
	0%{top:-5px}
	100%{top:-8px}
} */

.fg-table-data td {
    white-space: nowrap;
}

/* Rotate Icon 90 degrees (paper-plane) */
.rot-90 {
    -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
}

/* Rotate Icon 45 degrees (paper-plane) */
.rot-45 {
    -webkit-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
    -o-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
</style>