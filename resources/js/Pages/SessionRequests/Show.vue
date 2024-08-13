<template>
    <CustomNavbar :user="user">

        <template #page-title>
            Αίτηση Συνεδρίας
        </template>

        <template #page-content>
            <template v-if="user.isSuperAdmin">
                <PageHeaderActionButton>
                    <BaseSingleSelect
                        v-model="selectedCareerCoach"
                        :options="careerCoachDropdownOptions"
                        label="Ανάθεση σε ΣΕΠ"
                        accessKey="role"
                        placeholder=""
                        @update:option-changed="assignSessionRequestToCareerCoach"
                        :errors="errors.careerCoach"
                    />

                    <BaseClickButton
                        v-if=" sessionRequestData.status === 1"
                        @click="acceptSR( sessionRequestData.id )"
                        :label="'Αποδοχή Συνεδρίας'"
                    />

                    <BaseClickButton
                        @click="completeSR( sessionRequestData.id )"
                        :label="'Ολοκλήρωση Συνεδρίας'"
                    />
                </PageHeaderActionButton>
            </template>

            <form @submit.prevent="submit">
                <div class="flex flex-col md:flex-row">
                    <div
                        :class="{
                            'w-full': true, 
                            'md:w-1/2': sessionRequestData.status > 1,
                            'mx-2': true
                        }">
                        <div class="grid gap-6 mb-6 md:grid-cols-2" v-if="user.isSuperAdmin">
                            <div>
                                <div class="text-md mt-2 py-2 px-3 rounded" :class="{ 
                                    'font-bold bg-orange-100 dartext-orange-800 dark:bg-orange-200 dark:text-orange-800': sessionRequestData.status === 1, 
                                    'font-bold  bg-lime-100 text-lime-800 dark:bg-lime-200 dark:text-lime-800': sessionRequestData.status === 2,
                                    'font-bold  bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-800': sessionRequestData.status === 3,
                                    'font-bold  bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-800': sessionRequestData.status === 4,
                                }">
                                    <span>
                                        {{ status(sessionRequestData.status) }}
                                    </span>
                                </div>
                            </div>
                            <div>                                
                                <div class="text-md mt-2 py-2 px-3 rounded  text-slate-800  dark:text-slate-300">
                                    από <span class="font-bold" v-if="sessionRequestData.assignee">{{ sessionRequestData.assignee.firstname }} {{ sessionRequestData.assignee.lastname }}</span>
                                </div>
                            </div>
                        </div>

                        <VSectionHeading>Στοιχεία Επικοινωνίας</VSectionHeading>
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <BaseInput
                                v-model="sessionRequestData.firstname"
                                label="Όνομα"
                                type="firstname"
                                accessKey="firstname"
                                :errors="errors.firstname"
                            />

                            <BaseInput
                                v-model="sessionRequestData.lastname"
                                label="Επώνυμο"
                                type="lastname"
                                accessKey="lastname"
                                :errors="errors.lastname"
                            />

                            <BaseInput
                                v-model="sessionRequestData.email"
                                label="Email"
                                type="email"
                                accessKey="email"
                                :errors="errors.email"
                            />

                            <BaseInput
                                v-model="sessionRequestData.phone"
                                label="Τηλέφωνο"
                                type="phone"
                                accessKey="phone"
                                :errors="errors.phone"
                            />
                        </div>

                        <VSectionHeading>Προσωπικά Στοιχεία</VSectionHeading>
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <BaseInput
                                v-model="sessionRequestData.age"
                                label="Ηλικία"
                                type="age"
                                accessKey="age"
                                :errors="errors.age"
                            />

                            <BaseInput
                                v-model="sessionRequestData.gender"
                                label="Φύλο"
                                type="gender"
                                accessKey="gender"
                                :errors="errors.gender"
                            />
                        </div>

                        <VSectionHeading>Εκπαίδευση & Σπουδές</VSectionHeading>
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <BaseInput
                                v-model="sessionRequestData.career_status"
                                label="Κατάσταση Καριέρας"
                                type="career_status"
                                accessKey="career_status"
                                :errors="errors.career_status"
                            />

                            <BaseInput
                                v-model="sessionRequestData.department"
                                label="Τμήμα"
                                type="department"
                                accessKey="department"
                                :errors="errors.department"
                            />

                            <BaseInput
                                v-model="sessionRequestData.university"
                                label="Πανεπιστήμιο"
                                type="university"
                                accessKey="university"
                                :errors="errors.university"
                            />

                            <BaseInput
                                v-model="sessionRequestData.other_studies"
                                label="Επιπλέον Σπουδές"
                                type="other_studies"
                                accessKey="other_studies"
                                :errors="errors.other_studies"
                            />
                        </div>
                    </div>
                    <div 
                        v-show="sessionRequestData.status > 1" 
                        class="w-full md:w-1/2 mx-2"
                    >
                        <BaseTextarea
                            v-model="sessionRequestData.notes"
                            label="Σημειώσεις"
                            accessKey="notes"
                            :rows="20"
                            :required="true"
                            :errors="errors.notes"
                        />
                    </div>

                </div>

                <BaseFormSubmitButton
                    :label="'Ενημέρωση'" 
                    class="mt-4"
                />
            </form>
        
        </template>

    </CustomNavbar>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { reactive, ref } from 'vue';

    /* Layout */
    import CustomNavbar from '../Common/CustomNavbar.vue';
	import VSectionHeading from '@/Pages/Volunteers/ShowVolunteer/VSectionHeading.vue';
    import PageHeaderActionButton from '../../Components/Layouts/PageHeaderActionButton.vue';

    /* Form Fields */
    import BaseInput from '@/Pages/Common/UI/Form/BaseInput.vue';
    import BaseTextarea from '@/Pages/Common/UI/Form/BaseTextarea.vue';
    import BaseFormSubmitButton from '@/Pages/Common/UI/Form/BaseFormSubmitButton.vue';
    import BaseSingleSelect from '@/Pages/Common/UI/Form/BaseSingleSelect.vue'
    import BaseClickButton from '@/Pages/Common/UI/Buttons/BaseClickButton.vue';

    let props = defineProps({
        user: Object,
        sessionRequest: Object,
        response: Object,
        errors: Object,
        careerCoachDropdownOptions: Array
    });

    const sessionRequestData = reactive(props.sessionRequest);
    const selectedCareerCoach = ref( props.sessionRequest.assignee?.id );

    function submit() {
        Inertia.put('/session-requests/'+ sessionRequestData.id, sessionRequestData);
    }

    function assignSessionRequestToCareerCoach( careerCoachId ) {
        Inertia.put('/session-requests/'+ sessionRequestData.id + '/transfer-ownership', {ownerid: careerCoachId} );
    }

    function status( statusId ) {
        if( statusId === 1 ) return "Σε αναμονή";
        if( statusId === 2 ) return "Σε εκτέλεση";
        if( statusId === 3 ) return "Aπορρίφθηκε";
        if( statusId === 4 ) return "Ολοκληρωμένη";
    }

    function acceptSR( id ) {
        Inertia.put('/session-requests/accept/'+ id )
    }

    function completeSR( id ) {
        Inertia.put('/session-requests/complete/'+ id )
    }
</script>
