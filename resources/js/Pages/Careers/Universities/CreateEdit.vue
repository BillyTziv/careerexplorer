<template>
    <CustomNavbar :user="user">

        <template #page-title>
            <span v-if="!uniData.id">Δημιουργία Πανεπιστημίου</span>
            <span v-if="uniData.id">Ενημέρωση Πανεπιστημίου</span>
        </template>

        <template #page-content>
            <form @submit.prevent="submit">
                <BaseInput
                    v-model="uniData.name"
                    label="Όνομα"
                    type="text"
                    accessKey="name"
                    placeholder=""
                    :errors="errors.name"
                />

                <BaseFormSubmitButton
                    :label="!uniData.id ? 'Δημιουργία' : 'Ενημέρωση'" 
                    class="mt-4"
                />
            </form>
        </template>

    </CustomNavbar>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { reactive } from 'vue';
    import CustomNavbar from '../../Common/CustomNavbar.vue';

    import BaseInput from '../../Common/UI/Form/BaseInput.vue';
    import BaseFormSubmitButton from '../../Common/UI/Form/BaseFormSubmitButton.vue';


    let props = defineProps({
        user: Object,
        university: Object,
        response: Object,
        errors: Object
    });

    const uniData = reactive({
        id: props.university.id ? props.university.id : null,
        name: props.university.title ? props.university.title : "",
    })

    function submit() {
        if( uniData.id && uniData.id > 0 ) {
            Inertia.put('/universities/'+ uniData.id, uniData);
        }else {
            Inertia.post('/universities/', uniData);
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
