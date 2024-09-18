
  
<script setup>
    import { computed, ref, onMounted } from 'vue';

    import TestIntro from './TestIntro.vue';
    import TestQuestion from './TestQuestion.vue';
    import TestContactForm from './TestContactForm.vue';
    import TheTestProgressBar from './TheTestProgressBar.vue';
    
    import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
    import AppFormLayout from   '@/Layouts/AppFormLayout.vue';
    import { router } from '@inertiajs/vue3';

    import { useHollandTestStore } from '@/Stores/useHollandTest.store';
	import { useToastNotification } from '@/Composables/useToastNotification';
    import demoHollandTestPayload from './demoHollandTestPayload.js';

    const props = defineProps({
        response: Object,
        test: Object,
        content: Object,
        errors: {
            type: Object,
            required: false,
            default: () => ({}),
        }
    });

	const { notify } = useToastNotification();
    const hollandTestStore = useHollandTestStore();
    const sessionRequest = ref(true);

    onMounted(() => {
        hollandTestStore.setQuestions(props.test);
    });
 
    // Used to keep track of the current test status.
    // [ not-started > in-progress > submit-form ]
    const status = computed(() => hollandTestStore.getTestStatus);

    function submitTest() {
        hollandTestStore.setSessionRequest( sessionRequest.value );

        router.post('/tests/holland/submit', {
            test: hollandTestStore.getSubmitPayload
        }, { 
            preserveState: true, 
            replace: true, 
            onSuccess: () => {
                notify('success', 'Ολοκληρώθηκε', `Μπράβο! Το test επαγγελματικού προσανατολισμού ολοκληρώθηκε με επιτυχία!`);
            }
        });
    }


    function submitDemoTest () {
        router.post('/tests/holland/submit', {
            test: demoHollandTestPayload
        }, { 
            preserveState: true, 
            replace: true, 
            onSuccess: () => {
                notify('success', 'Ολοκληρώθηκε', `Μπράβο! Το test επαγγελματικού προσανατολισμού ολοκληρώθηκε με επιτυχία!`);
            }
        });   
    }
</script>
<template>
    <div class="page-wrapper">
        
        <TestIntro 
            v-if="status === 'not-started'"
        /> 
        <Button @click="submitDemoTest">Submit Demo Test</Button>


        <template v-if="status === 'in-progress'">
            <TestQuestion
                :question="hollandTestStore.getNextQuestion"
            />
        
            <TheTestProgressBar
                :progress="hollandTestStore.getProgress"
                :total="hollandTestStore.totalQuestions"
                class="px-4"
            />
        </template>

      <TestContactForm
            v-if="status === 'completed'"
            class="px-4  pt-6"
        />
       
        <!--  -->
        <div v-if="status === 'ready-to-submit'" class="px-4  pt-6">
            <!-- Loading Spinner 3 se -->
            <div class="card ">
                                       
                <Button
                    @click="submitTest"
                    label='Ολοκλήρωση του test'
                />

                <!-- <form @submit.prevent="submitForm" autocomplete="off"> -->
                    <!-- <AppFormLayout>
                            <Message
                                :severity="'info'"
                                :key="'faf'"
                            >
                                Βάση των απαντήσεων, βρέθηκαν 100+ πιθανές καριέρες. <strong>76% από όσους ολοκλήρωσαν το τεστ</strong>, δήλωσαν πως, ακόμη και μια δωρεάν συνεδρία 30 λεπτών, τους βοήθησε στο να προσανατολιστούν καλύτερα.
                            </Message>

                            <BaseDropdownInput
                                v-model="sessionRequest"
                                :options="[{ label: 'Ναι θέλω να κλείσω ραντεβού', id: 1 }, { label: 'Όχι δεν με ενδιαφέρει', id: 0 }]"
                                label="Θέλεις να επικοινωνήσει μαζί σου ένας σύμβουλος επαγγελματικού προσανατολισμού;"
                                placeholder="Επίλεξτε απάντηση"
                                :required=true
                                :errors="errors['sessionRequest']"
                            />
                    </AppFormLayout> -->

                 
       
                <!-- </form> -->


            
            </div>
        </div>



    </div>
</template>
<style scoped>
    .page-wrapper {
        height: 100vh;
        margin: 0;
        background: linear-gradient(to bottom right, #0F172A,  #0F172A);
    }

    .main {
        position: fixed;
        top: 50%;
        left: 50%;
        height: 1px;
        width: 1px;
        background-color: #f3f3f3;
        border-radius: 50%;
        box-shadow: -42vw -4vh 0px 0px #f3f3f3,25vw -41vh 0px 0px #f3f3f3,-20vw 49vh 0px 1px #f3f3f3,5vw 40vh 1px 1px #f3f3f3,29vw 19vh 1px 0px #f3f3f3,-44vw -13vh 0px 0px #f3f3f3,46vw 41vh 0px 1px #f3f3f3,-3vw -45vh 0px 1px #f3f3f3,47vw 35vh 1px 0px #f3f3f3,12vw -8vh 1px 0px #f3f3f3,-34vw 48vh 1px 1px #f3f3f3,32vw 26vh 1px 1px #f3f3f3,32vw -41vh 1px 1px #f3f3f3,0vw 37vh 1px 1px #f3f3f3,34vw -26vh 1px 0px #f3f3f3,-14vw -49vh 1px 0px #f3f3f3,-12vw 45vh 0px 1px #f3f3f3,-44vw -33vh 0px 1px #f3f3f3,-13vw 41vh 0px 0px #f3f3f3,-36vw -11vh 0px 1px #f3f3f3,-23vw -24vh 1px 0px #f3f3f3,-38vw -27vh 0px 1px #f3f3f3,16vw -19vh 0px 0px #f3f3f3,28vw 33vh 1px 0px #f3f3f3,-49vw -4vh 0px 0px #f3f3f3,16vw 32vh 0px 1px #f3f3f3,36vw -18vh 1px 0px #f3f3f3,-25vw -30vh 1px 0px #f3f3f3,-23vw 24vh 0px 1px #f3f3f3,-2vw -35vh 1px 1px #f3f3f3,-25vw 9vh 0px 0px #f3f3f3,-15vw -34vh 0px 0px #f3f3f3,-8vw -19vh 1px 0px #f3f3f3,-20vw -20vh 1px 1px #f3f3f3,42vw 50vh 0px 1px #f3f3f3,-32vw 10vh 1px 0px #f3f3f3,-23vw -17vh 0px 0px #f3f3f3,44vw 15vh 1px 0px #f3f3f3,-40vw 33vh 1px 1px #f3f3f3,-43vw 8vh 0px 0px #f3f3f3,-48vw -15vh 1px 1px #f3f3f3,-24vw 17vh 0px 0px #f3f3f3,-31vw 50vh 1px 0px #f3f3f3,36vw -38vh 0px 1px #f3f3f3,-7vw 48vh 0px 0px #f3f3f3,15vw -32vh 0px 0px #f3f3f3,29vw -41vh 0px 0px #f3f3f3,2vw 37vh 1px 0px #f3f3f3,7vw -40vh 1px 1px #f3f3f3,15vw 18vh 0px 0px #f3f3f3,25vw -13vh 1px 1px #f3f3f3,-46vw -12vh 1px 1px #f3f3f3,-18vw 22vh 0px 0px #f3f3f3,23vw -9vh 1px 0px #f3f3f3,50vw 12vh 0px 1px #f3f3f3,45vw 2vh 0px 0px #f3f3f3,14vw -48vh 1px 0px #f3f3f3,23vw 43vh 0px 1px #f3f3f3,-40vw 16vh 1px 1px #f3f3f3,20vw -31vh 0px 1px #f3f3f3,-17vw 44vh 1px 1px #f3f3f3,18vw -45vh 0px 0px #f3f3f3,33vw -6vh 0px 0px #f3f3f3,0vw 7vh 0px 1px #f3f3f3,-10vw -18vh 0px 1px #f3f3f3,-19vw 5vh 1px 0px #f3f3f3,1vw 42vh 0px 0px #f3f3f3,22vw 48vh 0px 1px #f3f3f3,39vw -8vh 1px 1px #f3f3f3,-6vw -42vh 1px 0px #f3f3f3,-47vw 34vh 0px 0px #f3f3f3,-46vw 19vh 0px 1px #f3f3f3,-12vw -32vh 0px 0px #f3f3f3,-45vw -38vh 0px 1px #f3f3f3,-28vw 18vh 1px 0px #f3f3f3,-38vw -46vh 1px 1px #f3f3f3,49vw -6vh 1px 1px #f3f3f3,-28vw 18vh 1px 1px #f3f3f3,10vw -24vh 0px 1px #f3f3f3,-5vw -11vh 1px 1px #f3f3f3,33vw -8vh 1px 0px #f3f3f3,-16vw 17vh 0px 0px #f3f3f3,18vw 27vh 0px 1px #f3f3f3,-8vw -10vh 1px 1px #f3f3f3;
    
    
        box-shadow: 24vw 9vh 1px 0px #f3f3f3,12vw -24vh 0px 1px #f3f3f3,-45vw -22vh 0px 0px #f3f3f3,-37vw -40vh 0px 1px #f3f3f3,29vw 19vh 0px 1px #f3f3f3,4vw -8vh 0px 1px #f3f3f3,-5vw 21vh 1px 1px #f3f3f3,-27vw 26vh 1px 1px #f3f3f3,-47vw -3vh 1px 1px #f3f3f3,-28vw -30vh 0px 1px #f3f3f3,-43vw -27vh 0px 1px #f3f3f3,4vw 22vh 1px 1px #f3f3f3,36vw 23vh 0px 0px #f3f3f3,-21vw 24vh 1px 1px #f3f3f3,-16vw 2vh 1px 0px #f3f3f3,-16vw -6vh 0px 0px #f3f3f3,5vw 26vh 0px 0px #f3f3f3,-34vw 41vh 0px 0px #f3f3f3,1vw 42vh 1px 1px #f3f3f3,11vw -13vh 1px 1px #f3f3f3,48vw -8vh 1px 0px #f3f3f3,22vw -15vh 0px 0px #f3f3f3,45vw 49vh 0px 0px #f3f3f3,43vw -27vh 1px 1px #f3f3f3,20vw -2vh 0px 0px #f3f3f3,8vw 22vh 0px 1px #f3f3f3,39vw 48vh 1px 1px #f3f3f3,-21vw -11vh 0px 1px #f3f3f3,-40vw 45vh 0px 1px #f3f3f3,11vw -30vh 1px 0px #f3f3f3,26vw 30vh 1px 0px #f3f3f3,45vw -29vh 0px 1px #f3f3f3,-2vw 18vh 0px 0px #f3f3f3,-29vw -45vh 1px 0px #f3f3f3,-7vw -27vh 1px 1px #f3f3f3,42vw 24vh 0px 0px #f3f3f3,45vw -48vh 1px 0px #f3f3f3,-36vw -18vh 0px 0px #f3f3f3,-44vw 13vh 0px 1px #f3f3f3,36vw 16vh 0px 1px #f3f3f3,40vw 24vh 0px 0px #f3f3f3,18vw 11vh 0px 0px #f3f3f3,-15vw -23vh 1px 0px #f3f3f3,-24vw 48vh 0px 1px #f3f3f3,27vw -45vh 1px 0px #f3f3f3,-2vw -24vh 0px 1px #f3f3f3,-15vw -28vh 0px 0px #f3f3f3,-43vw 13vh 1px 0px #f3f3f3,7vw 27vh 1px 0px #f3f3f3,47vw 5vh 0px 0px #f3f3f3,-45vw 15vh 1px 1px #f3f3f3,-5vw -28vh 0px 1px #f3f3f3,38vw 25vh 1px 1px #f3f3f3,-39vw -1vh 1px 0px #f3f3f3,5vw 0vh 1px 0px #f3f3f3,49vw 13vh 0px 0px #f3f3f3,48vw 10vh 0px 1px #f3f3f3,19vw -28vh 0px 0px #f3f3f3,4vw 7vh 0px 0px #f3f3f3,21vw 21vh 1px 1px #f3f3f3,-15vw -15vh 0px 1px #f3f3f3,-6vw -42vh 1px 0px #f3f3f3,-15vw 48vh 1px 1px #f3f3f3,-23vw 25vh 1px 1px #f3f3f3,-48vw 25vh 0px 1px #f3f3f3,-31vw -19vh 0px 1px #f3f3f3,4vw 37vh 1px 1px #f3f3f3,-43vw 28vh 0px 0px #f3f3f3,3vw -25vh 0px 1px #f3f3f3,-39vw 14vh 0px 1px #f3f3f3,-40vw 31vh 0px 1px #f3f3f3,35vw -36vh 1px 1px #f3f3f3,16vw 49vh 0px 0px #f3f3f3,6vw 39vh 0px 0px #f3f3f3,3vw -35vh 0px 1px #f3f3f3,-44vw -2vh 1px 0px #f3f3f3,-6vw 21vh 1px 0px #f3f3f3,48vw 9vh 1px 1px #f3f3f3,-43vw 30vh 1px 1px #f3f3f3,29vw -12vh 1px 1px #f3f3f3,-48vw 13vh 1px 0px #f3f3f3,-42vw 32vh 1px 1px #f3f3f3,34vw 15vh 1px 1px #f3f3f3,29vw -37vh 1px 1px #f3f3f3,28vw 2vh 0px 0px #f3f3f3;
        animation: zoom 25s alternate infinite; 
    }

    @keyframes zoom {
        0%{
            transform: scale(1);
        }
        100%{
            transform: scale(1.2);
        }
    }

</style>