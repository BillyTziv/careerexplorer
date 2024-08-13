<script setup>
    import { reactive } from 'vue';

    import BaseInput from '@/Pages/Common/UI/Form/BaseInput.vue';
    import BaseAlertBox from '@/Components/Base/BaseAlertBox.vue';

    const props = defineProps({
        test: Object
    });
    
    const emit = defineEmits(['submit-form']);

    const userData = reactive({
        firstname: "",
        lastname: "",
        phone: null,
        email: "",
        age: null,
        gender: null,
    });

    function submitAnswers() {
        //let isFormValid = true;

        // if( userData.firstname.length <= 3 ) isFormValid = false;
        // if( userData.lastname.length <= 3 ) isFormValid = false;
        // if( userData.email.length <= 5 ) isFormValid = false;
        // if( userData.phone.length <= 10 ) isFormValid = false;

        // if( !isFormValid ) return;
        
        emit('submit-form', userData);
    }
</script>

<template>
    <div class="p-4 flex flex-col items-center justify-center h-screen">
        <!-- <form 
            @submit.prevent="submitAnswers"
            class="flex flex-col items-center"
        > -->

            <div class="text-2xl font-light text-center dark:text-white text-slate-800 my-3">
                Συμπλήρωσε τα στοιχεία σου για να λάβεις τα αποτελέσματα του test
            </div>
        
            <BaseInput
                v-model="userData.firstname"
                label="Όνομα"
                placeholder="Γράψε το όνομά σου.."
                required
            ></BaseInput>

            <BaseInput
                v-model="userData.lastname"
                label="Επώνυμο"
                placeholder="Γράψε το επώνυμό σου.."
                required
            ></BaseInput>

            <BaseInput
                v-model="userData.phone"
                label="Τηλέφωνο"
                placeholder="Γράψε το κινητό σου.."
                required
            ></BaseInput>

            <BaseInput
                v-model="userData.email"
                label="Email"
                placeholder="Γράψε το κύριο email σου.."
                required
            ></BaseInput>

            <div class="flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                <button @click="submitAnswers" type="submit" class="relative p-0.5 inline-flex items-center justify-center font-bold overflow-hidden group rounded-md">
                    <span class="w-full h-full bg-gradient-to-br from-[#ff00c6] via-[#ff5478] to-[#ff8a05] group-hover:from-[#ff8a05] group-hover:via-[#ff5478] group-hover:to-[#ff00c6] absolute"></span>
                    <span class="relative px-6 py-3 transition-all ease-out bg-opacity-0 rounded-md group-hover:bg-gray-900 duration-400">
                        <span class="relative text-white">Συνέχεια</span>
                    </span>
                </button>
            </div>
        <!-- </form> -->
    </div>
</template>