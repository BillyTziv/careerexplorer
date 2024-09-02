<template>
	<CustomNavbar :user="user">

		<template #page-header>
			<div class="flex flex-column">
				<div class="px-2 flex text-center justify-center">Προβολή Αίτησης</div>
			</div>
		</template>

		<template #page-actions>
			<!-- Edit application -->
			<BaseClickButton
				@click="editapplication( application.id )"
				:svg-path="['M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125']"
				:label="'Επεξεργασία'"
			/>
			
			<!-- Delete application -->
			<BaseClickButton
				@click="deleteapplication( application.id )"
				:svg-path="['M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0']"
				:label="'Διαγραφή'"
			/>
		</template>

		<template #main>			
			<div class="flex flex-col md:flex-row lg:flex-row xl:flex-row">
				<div class="flex-1 md:w-2/3 lg:w-2/3 xl:w-2/3 border-r border-slate-600 mx-2">
					<!-----------------------------------------------------------------------------------------
					| applicationING EXPERIENCE
					------------------------------------------------------------------------------------------>
					<!-- <pre>
						{{ application.submission }}
					</pre> -->
					<VSectionInfoGrid>
						<template
							v-for="(fieldData, fieldName) in application.submission"
							:key="fieldName"
						>
							<!-- ----------------<pre>{{ fieldData }}</pre>---------------------- -->
							
							<VSectionInfoGridItem
								v-if="fieldData.type !== 'file'"
								:label="fieldData.label ?? ''"
								:value="fieldData.value ?? ''"
							/>

							<template
								v-else
							>
								<label>{{ fieldData.label }}</label>
								<div class="flex-1 font-bold">
									<a :href="'data:application/pdf;base64,' + fieldData.value" download="file.pdf" class="flex dark:bg-lime-500 dark:text-slate-900 dark:bg-opacity-90 hover:dark:bg-opacity-70 inline-flex items-center px-2 py-1 text-sm font-medium leading-5 text-gray-900 focus:outline-none border border-lime-800 focus:border-lime-700 transition  duration-150 ease-in-out hover:dark:bg-lime-500 rounded-md"
>
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.1" stroke="black" class="w-6 h-6">
											<path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
										</svg>

										<span class="pl-2">Λήψη</span>
									</a>
								</div>
								
							</template>

							<!-- <div v-for="(propertyValue, propertyName) in fieldData" :key="propertyName">
								<p>{{ propertyName }}: {{ propertyValue }}</p>
							</div> -->
						</template>
					</VSectionInfoGrid>
					<!-- <ApplicationSection
						:sectionId="'applicationing-experience'"
					>
						<template #header>
							<VSectionHeading>Εθελοντική Εμπειρία</VSectionHeading>
						</template>

						<VSectionInfoGrid>
							<VSectionInfoGridItem v-if="application.university" label="Κατάσταση" :value="calculatedapplicationstatus"/>
							<VSectionInfoGridItem v-if="application.disapproved_reason" label="Σχόλια Αλλαγής Κατάστασης" :value="application.disapproved_reason" />
							<VSectionInfoGridItem v-if="application.onboarding_completed" label="Ολοκλήρωση του Onboarding" :value="application.onboarding_completed ? 'Ναι' : 'Όχι'" />
							<VSectionInfoGridItem v-if="application" label="Ρόλος Εθελοντή" :value="JSON.parse(application.application_role).name" />
							<VSectionInfoGridItem v-if="application.start_date" label="Ημ/νία Έναρξης" :value="application.start_date" />
							<VSectionInfoGridItem v-if="application.end_date" label="Ημ/νία Ολοκληρωσης" :value="application.end_date" />
							<VSectionInfoGridItem v-if="application.hours_contributed" label="Ώρες Συνεισφοράς" :value="application.hours_contributed" />
							<VSectionInfoGridItem v-if="application.previous_application_experience" label="Προυπηρεσία στον εθελοντισμό" :value="application.previous_application_experience" />
						</VSectionInfoGrid>
					</ApplicationSection>
					 -->
				
				</div>
			</div>
		</template>
	</CustomNavbar>
</template>

<script setup>
	import { ref, reactive } from 'vue';
	import { Inertia } from '@inertiajs/inertia';
	import CustomNavbar from '../Common/CustomNavbar.vue';
	import VSectionInfoGrid from '@/Pages/Volunteers/ShowVolunteer/VSectionInfoGrid.vue';
	import VSectionInfoGridItem from '@/Pages/Volunteers/ShowVolunteer/VSectionInfoGridItem.vue';
    import BaseClickButton  from '@/Pages/Common/UI/Buttons/BaseClickButton.vue';

	let props = defineProps({
		user: Object,
		application: Object,
		response: Object,
		errors: Object,
       
	});

	const selectedapplicationstatus = ref(props.application.status);
	const showapplicationstatusChangeModal = ref( false );

	const form = reactive({
        id: props.application.id ? props.application.id : null,
        role: props.application.role ? props.application.role : null,
        notes: props.application.notes ? props.application.notes : "",
        personality: {
            expectations: props.application.expectations ? props.application.expectations : "",
            reason: props.application.reason ? props.application.reason : "",
            interests: props.application.interests ? props.application.interests : "",
            description: props.application.description ? props.application.description : "",
        },
        personalInfo: {
            firstname: props.application.firstname ? props.application.firstname : "",
            lastname: props.application.lastname ? props.application.lastname : "",
            phone: props.application.phone ? props.application.phone : "",
            email: props.application.email ? props.application.email : "",
        },
        studies: {
            university: props.application.university ? props.application.university : "",
            department: props.application.department ? props.application.department : "",
            otherstudies: props.application.otherstudies ? props.application.otherstudies : "",
        },
        social: {
            linkedin: props.application.linkedin ? props.application.linkedin : "",
            facebook: props.application.facebook ? props.application.facebook : "",
            instagram: props.application.instagram ? props.application.instagram : "",
        },
        links: {
            googleDrive:props.application.google_drive ? props.application.google_drive : "",
            asana: props.application.asana ? props.application.asana : "",
        }
    })

	const showapplicationNoteModal = ref(false);

	// function editapplication( applicationId ) {
    //     Inertia.get('/applications/' + applicationId + '/edit')
    // }

    function deleteapplication( applicationId ) {
		Inertia.delete('/applications/' + applicationId, {
            onBefore: () => confirm('Επιβεβαίωση διαγραφής?'),
        } );
    }
	
	// function changeapplicationstatus( statusChangeReason ) {
	// 	Inertia.put('/applications/' + props.application.id + '/status', {
	// 		newStatusValue: selectedapplicationstatus.value,
	// 		statusChangeReason: statusChangeReason
	// 	}, 
	// 	{
	// 		preserveState: true,
	// 		replace: true
	// 	});

	// 	showapplicationstatusChangeModal.value = false;
	// }

	// const applicationProfileImage = computed( () => {
	// 	if( props.application.profile_image ) {
	// 		return props.application.profile_image;
	// 	}

	// 	switch( props.application.gender ) {
	// 		case 'male':
	// 			return '/images/profile_picture_male.png';
	// 		case 'female':
	// 			return '/images/profile_picture_female.png';
	// 		default:
	// 			return 'https://placehold.co/32x32';
	// 	}
	// })
	// const calculatedapplicationstatus = computed( () => {
	// 	let status = "";

	// 	status = props.applicationstatusDropdownOptions.find( (item) => {
	// 		if( item.id === props.application.status ) {
	// 			return item.name;
	// 		}
	// 	});

	// 	return status?.name ?? 'Άγνωστο';
	// })

	// const applicationRole = computed( () => {
	// 	return props.application.application_role ? JSON.parse(props.application.application_role).name : '';
	// })

	// const hasCV = computed( () => {
	// 	return props.application.cv && props.application.cv.trim() !== '';
	// })

	// const hasStudies = computed( () => {
	// 	return props.application.university || props.application.studies || props.application.otherstuddies;
	// })

	// const hasProfessionalExperience = computed( () => {
	// 	return props.application.current_company || props.application.current_role || props.application.years_experience || props.application.career_status;
	// })

	// const hasSocialMedia = computed( () => {
	// 	return props.application.socialMedia.some(item => item.link && item.link.trim() !== '');
	// })
</script>

<style scoped>
	.grid {
		grid-template-columns: 1fr 2fr; /* Adjust these fractions according to your needs */
	}

	.label {
		max-width: 80px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis; 
	}
</style>