<template>
    <CustomNavbar :user="user">

        <template #page-title>
            Δημιουργία Προτύπου
        </template>
   
        <template #page-content>
            <form @submit.prevent="submit" autocomplete="off">

                <div class="relative mt-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        Όνομα Προτύπου
                    </label>
                    <input v-model="testTemplate.name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-500 focus:border-lime-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-lime-500 dark:focus:border-lime-500" placeholder="Test template name.." required>
                </div>

                <div class="relative mt-5">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        Περιγραφή Προτύπου
                    </label>
                    <textarea v-model="testTemplate.description" placeholder="Test template description.." id="description" required class="mt-1 block w-full" autocomplete="off" autocorrect="off">

                    </textarea>
                </div>

                <div class="relative mt-5">
                    <label for="type">Τύπος</label>
                    <select v-model="testTemplate.type" name="type" class="mt-1 block w-full">
                        <option :value="''">-- Select type --</option>
                        <option :value="1">Custom Test</option>
                        <option :value="2">Holland Code Test</option>
                        <option :value="3">Personallity Test</option>
                        <option :value="4">Career Values Test</option>
                    </select>
                </div>
                
                <!--------------------------------------------------------
                    CAREER VALUES
                --------------------------------------------------------->
                <div class="mt-4" v-if="testTemplate.type === 1">
                    <label for="description">Ερωτήσεις</label>

                    <ul>
                        <li v-for="question in questions" class="flex mt-1 pb-1">
                            <input v-model="question.text" placeholder="Type a question.." type="text" class="grow"/>

                            <input v-model="question.description" placeholder="Describe the question.." type="text" class="grow d-none"/>
                            

                            <select v-model="question.type" required class="flex-none d-none">
                                <option value="1">Yes/No</option>
                            </select>

                        </li>
                        <li>
                            <button type="button" class="mt-2 fg-btn-bordered flex items-center justify-start mb-2" @click="addQuestion()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                <span>Προσθήκη</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="flex items-center justify-start mt-4">                
                    <button @click="createTestTemplate()" class="fg-btn-primary" type="button">
                        Δημιουργία Προτύπου
                    </button>
                </div>
            </form>
        </template>
        
    </CustomNavbar>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { ref, reactive } from 'vue';
    import CustomNavbar from '../../Common/CustomNavbar.vue';
    
    let props = defineProps({
        user: Object,
        response: Object,
        hollandQuestions: Object,
        testTemplate: Object,
    });

    let testTemplate = reactive( props.testTemplate );
    let questions = ref([]);

    const question = {
        text: "",
        description: "",
        type: -1,
    }

    function addQuestion() {
        questions.value.push( JSON.parse(JSON.stringify( question )))
    }

    function createTestTemplate() {
        testTemplate.questions = questions.value;
        
        Inertia.post('/tests', { testTemplate: testTemplate })
    }
</script>
