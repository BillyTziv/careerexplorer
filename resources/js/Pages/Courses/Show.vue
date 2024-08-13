<template>
    <CustomNavbar :user="user">

        <template #page-header>
            Μάθημα
        </template>
   
        <template #main>
            <PageHeaderActionButton   >
                <!-- Edit Course -->
                <BaseClickButton
                    @click="editCourse( course.id )"
                    :svg-path="['M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75']"
                    :label="'Επεξεργασία'"
                />

                <!-- Delete Course -->
                <BaseClickButton
                    @click="deleteCourse( course.id )"
                    :svg-path="['M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75']"
                    :label="'Διαγραφή'"
                />
            </PageHeaderActionButton>

            <form @submit.prevent="submit" autocomplete="off">
                <BaseInput
                    v-model="courseForm.title"
                    label="Τίτλος"
                    type="text"
                    accessKey="title"
                    placeholder=""
                    :readonly="true"
                    :errors="errors.title"
                />

                <BaseInput
                    v-model="courseForm.description"
                    label="Περιγραφή"
                    type="text"
                    accessKey="description"
                    placeholder=""
                    :readonly="true"
                    :errors="errors.description"
                />

                <BaseSingleSelect
                    v-model="courseForm.company_id"
                    :options="companyDropdownOptions"
                    label="Φορέας"
                    accessKey="company"
                    placeholder=""
                    class="mb-4"
                    :is-disabled="true"
                    :errors="errors.company"
                />

                <BaseInput
                    v-model="courseForm.link"
                    label="Σύνδεσμος"
                    type="text"
                    accessKey="link"
                    placeholder=""
                    :readonly="true"
                    :errors="errors.link"
                />
            </form>
        </template>

    </CustomNavbar>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { reactive, computed } from 'vue';

    /* Layouts */
    import PageHeaderActionButton from '../../Components/Layouts/PageHeaderActionButton.vue';
    import CustomNavbar from '@/Pages/Common/CustomNavbar.vue';

    /* Custom Components */
    import BaseClickButton from '@/Pages/Common/UI/Buttons/BaseClickButton.vue';
    import BaseInput from '@/Pages/Common/UI/Form/BaseInput.vue';
    import BaseSingleSelect from '@/Pages/Common/UI/Form/BaseSingleSelect.vue';

    let props = defineProps({
        response: Object,
        user: Object,
        errors: Object,
        course: Object,
        companyDropdownOptions: Array,
    });

    const courseForm = reactive( props.course );

    function editCourse( courseId ) {
        Inertia.get('/courses/' + courseId + '/edit');
    }

    function deleteCourse( courseId ) {
        Inertia.delete('/courses/' + courseId, {
            onBefore: () => confirm('Επιβεβαίωση διαγραφής?'),
        } );
    }
</script>