<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #page-title>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Update Test
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <label for="name">Title</label>
                                <input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus  />
                            </div>
                
                            <div class="mt-4">
                                <label for="description">Description</label>
                                <textarea id="description" class="mt-1 block w-full" v-model="form.description"> </textarea>
                            </div>

                            <div class="mt-4">
                                <label for="type">Type</label>
                                <select :disabled="true" v-model="form.type" name="type" id="type" required class="mt-1 block w-full">
                                    <option value="">-- Select a type --</option>
                                    <option :value="0">Holland Test</option>
                                </select>
                            </div>

                            <div class="mt-4" v-if="form.type === 0">
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-1 mt-4">
                                   Questions
                                </h2>

                                <ul>
                                    <li v-for="question in form.questions" class="flex space-x-4 mt-1">

                                        <!-- Question Holland Type -->
                                        <select :disabled="true" v-model="question.type" required class="flex-none">
                                            <option disabled value="0">-- Select a type --</option>
                                            <option :value="1">Realistic (Doers)</option>
                                            <option :value="2">Investigative (Thinkers)</option>
                                            <option :value="3">Artistic (Creators)</option>
                                            <option :value="4">Social (Helpers)</option>
                                            <option :value="5">Enterprising (Persuaders)</option>
                                            <option :value="6">Conventional (Organizers)</option>
                                        </select>

                                        <!-- Question -->
                                        <input :disabled="true" v-model="question.title" placeholder="Type a question.." type="text" class="grow"/>

                                    </li>
                                    <!-- <li>
                                        <button type="button" class="mt-2 fg-btn-bordered flex items-center justify-start mb-2" @click="addQuestion()">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            <span>Add Question</span>
                                        </button>
                                    </li> -->
                                </ul>
                            </div>
                
                            <div class="flex items-center justify-start mt-4">                
                                <button class="fg-btn-primary" type="submit">Update Test</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
    // import { useForm } from "@Inertia";
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import { Inertia } from '@inertiajs/inertia'

    export default {
        setup ( props ) {
            const question = {
                title: "Are you happy?",
                type: 1,
                description: "Some descipriotn for the question"
            }
            let myTest = props.test;
            myTest.questions = props.questions;

            const form = myTest

            function addQuestion() {
                form.questions.push( JSON.parse(JSON.stringify( question )))
            }

            function submit() {
                Inertia.post('/tests/update', form)
            }

            return { form, submit, addQuestion }
        },
        components: {
            Head,
            Link,
            PreviousPageButton,
            BreezeAuthenticatedLayout,
        },
        props:  {
            test: Object,
            questions: Array
        }
    }
    
</script>
