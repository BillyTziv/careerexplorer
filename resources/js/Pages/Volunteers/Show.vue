<template>
	<CustomNavbar :user="user">

		<template #page-header>
			<div class="flex flex-column">
				<div class="px-2 flex text-center justify-center">{{ volunteer.firstname + " " + volunteer.lastname }}</div>
			</div>
		</template>

		<template #page-actions>
			<!-- Edit Volunteer -->
			<BaseClickButton
				@click="editVolunteer( volunteer.id )"
				:svg-path="['M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125']"
				:label="'Επεξεργασία'"
			/>
			
			<!-- Delete Volunteer -->
			<BaseClickButton
				@click="deleteVolunteer( volunteer.id )"
				:svg-path="['M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0']"
				:label="'Διαγραφή'"
			/>
		</template>

		<template #main>
			<div class="flex flex-col md:flex-row lg:flex-row xl:flex-row">
				<div class="flex-1 md:w-2/3 lg:w-2/3 xl:w-2/3 border-r border-slate-600 mx-2">
					<!-----------------------------------------------------------------------------------------
					| HERO SECTION
					------------------------------------------------------------------------------------------>
					<div class="flex flex-col md:flex-row items-center gap-6">
						<!-- Image Section -->
						<div class="flex-shrink-0">
							<img :src="volunteerProfileImage" :alt="volunteer.firstname + ' ' + volunteer.lastname" class="w-full md:w-32 h-auto rounded-lg">
						</div>

						<!-- Text Block Section -->
						<div class="flex flex-col gap-2">
							<h2 class="text-xl font-bold dark:text-lime-300">
								{{ volunteer.firstname + " " + volunteer.lastname }}
							</h2>
							
							<p class="text-lg text-slate-300">
								{{ volunteerRole }}
							</p>

							<select
								v-model="selectedVolunteerStatus" 
								@change="handleStatusChange( $event )" 
								class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-lime-500 focus:border-lime-500 block p-2 dark:bg-slate-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-slate-200 dark:focus:ring-lime-500 dark:focus:border-lime-500"
							>
								<option 
									v-for="statusItem in volunteerStatusDropdownOptions" 
									:value="statusItem.id" 
									v-html="statusItem.name"
								></option>
							</select>

							<VSectionInfoGridItem
								v-if="volunteer.disapproved_reason" 
								label="Σχόλια Αλλαγής Κατάστασης" 
								:value="volunteer.disapproved_reason"
							/>
							
							<!-- <VolunteerStatus
								:status="parseInt(volunteer.status)"
							/>  -->

							<!-- Social Media Links -->
							<template v-if="hasSocialMedia">
								<div class="flex flex-column">
									<template
										v-for="(sm, smIndex) in volunteer.socialMedia"
										:key="smIndex"
									>
										<SocialMediaLink 
											v-if="sm.link"
											:label="sm.label" 
											:link="sm.link" 
											class="m-1"
										/>
									</template>
								</div>
							</template>
						
						</div>
					</div>
						
					<!-----------------------------------------------------------------------------------------
					| PERSONAL INFORMATION
					------------------------------------------------------------------------------------------>
					<VolunteerSection
						:sectionId="'personal-information'"
					>
						<template #header>
							<VSectionHeading>Προσωπικά Στοιχεία</VSectionHeading>
						</template>

						<VSectionInfoGrid>
							<!-- <VSectionInfoGridItem label="Όνομα" :value="volunteer.firstname" /> -->
							<!-- <VSectionInfoGridItem label="Επώνυμο" :value="volunteer.lastname" /> -->
							<VSectionInfoGridItem label="Τηλέφωνο" :value="volunteer.phone" />
							<VSectionInfoGridItem label="Email" :value="volunteer.email" />
							<VSectionInfoGridItem v-if="volunteer.date_of_birth" label="Ημ/νία Γέννησης" :value="volunteer.date_of_birth" />
							<VSectionInfoGridItem v-if="volunteer.age" label="Ηλικία" :value="volunteer.age" />
							<VSectionInfoGridItem v-if="volunteer.gender" label="Φύλλο" :value="volunteer.gender" />
							<VSectionInfoGridItem v-if="volunteer.city" label="Πόλη" :value="volunteer.city" />
							<VSectionInfoGridItem v-if="volunteer.address" label="Διεύθυνση" :value="volunteer.address" />
						</VSectionInfoGrid>
					</VolunteerSection>

					<!-----------------------------------------------------------------------------------------
					| STUDIES
					------------------------------------------------------------------------------------------>
					<VolunteerSection
						:sectionId="'studies'"
						v-if="hasStudies"
					>
						<template #header>
							<VSectionHeading>Σπουδές & Εκπαίδευση</VSectionHeading>
						</template>	

						<VSectionInfoGrid>
							<VSectionInfoGridItem v-if="volunteer.university" label="Πανεπιστήμιο" :value="volunteer.university" />
							<VSectionInfoGridItem v-if="volunteer.department" label="Τμήμα" :value="volunteer.department" />
							<VSectionInfoGridItem v-if="volunteer.otherstuddies" label="Επιπλέον Σπουδές" :value="volunteer.otherstudies" />
						</VSectionInfoGrid>
					</VolunteerSection>

					<!-----------------------------------------------------------------------------------------
					| VOLUNTEERING EXPERIENCE
					------------------------------------------------------------------------------------------>
					<VolunteerSection
						:sectionId="'volunteering-experience'"
					>
						<template #header>
							<VSectionHeading>Εθελοντική Εμπειρία</VSectionHeading>
						</template>

						<VSectionInfoGrid>
							<VSectionInfoGridItem label="Ανατέθηκε σε" :value="volunteer.assigned_to"/>

							<!-- <VSectionInfoGridItem label="Ολοκλήρωση του Onboarding" :value="volunteer.onboarding_completed ? 'Ναι' : 'Όχι'" /> -->
							<!-- <VSectionInfoGridItem v-if="volunteer" label="Ρόλος Εθελοντή" :value="JSON.parse(volunteer.volunteer_role).name" /> -->
							<VSectionInfoGridItem label="Ημ/νία Έναρξης" :value="volunteer.start_date" />
							<VSectionInfoGridItem label="Ημ/νία Λήξης" :value="volunteer.end_date" />
							<VSectionInfoGridItem label="Ώρες Συνεισφοράς" :value="volunteer.hours_contributed" />
							<VSectionInfoGridItem label="Προυπηρεσία" :value="volunteer.previous_volunteer_experience" />
						</VSectionInfoGrid>
					</VolunteerSection>

					<!-----------------------------------------------------------------------------------------
					| PROFESSIONAL EXPERIENCE
					------------------------------------------------------------------------------------------>
					<VolunteerSection
						:sectionId="'professional-experience'"
						v-if="hasProfessionalExperience"
					>
						<template #header>
							<VSectionHeading>Επαγγελματική Εμπειρία</VSectionHeading>
						</template>

						<VSectionInfoGrid>
							<VSectionInfoGridItem v-if="volunteer.current_company" label="Εταιρεία" :value="volunteer.current_company" />
							<VSectionInfoGridItem v-if="volunteer.current_role" label="Ρόλος" :value="volunteer.current_role" />
							<VSectionInfoGridItem v-if="volunteer.years_experience" label="Χρόνια Εργασίας" :value="volunteer.years_experience" />
							<VSectionInfoGridItem v-if="volunteer.career_status" label="Κατάσταση Καριέρας" :value="volunteer.career_status" />
						</VSectionInfoGrid>
					</VolunteerSection>

					<!-----------------------------------------------------------------------------------------
					| PROFESSIONAL EXPERIENCE
					------------------------------------------------------------------------------------------>
					<VolunteerSection
						:sectionId="'cv'"
						v-if="hasCV"
					>
						<template #header>
							<VSectionHeading>Βιογραφικό</VSectionHeading>
						</template>

						<BaseClickButton
							@click="openCVModal = true"
							:svg-path="['m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10']"
							:label="'Προβολή Βιογραφικού'"
							class="my-1"
						/>

						<TheShowVolunteerCVModal
							:volunteer="volunteer"
							:isOpen="openCVModal"
							@update:isOpen="openCVModal = $event"
						></TheShowVolunteerCVModal>
					</VolunteerSection>
				</div>
				<div class="flex-1 md:w-1/3 lg:w-1/3 xl:w-1/3 mx-2">
					<VolunteerSection
						:sectionId="'notes'"
					>
						<template #header>
							<VSectionHeading>
								Σημειώσεις
							</VSectionHeading>
						</template>
						
						<p style="white-space: pre-wrap;" class="dark:text-slate-100 dark:text-sm">
							{{ form.notes }}
						</p>

						<BaseClickButton
							@click="showVolunteerNoteModal = true"
							:svg-path="['m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10']"
							:label="'Προσθήκη Σημείωσης'"
							class="my-1"
						/>
					</VolunteerSection>

					<!-----------------------------------------------------------------------------------------
						| PERSONALITY
					------------------------------------------------------------------------------------------>
					<VolunteerSection
						:sectionId="'personality'"
					>
						<template #header>
							<VSectionHeading>Προσωπικότητα</VSectionHeading>
						</template>

						
						<template v-if="volunteer.description">
							<h5 class="mt-2 text-md font-bold text-slate-800 dark:text-slate-300">
								Πως θα περιέγραφες τον εαυτό σου σε μια παράγραφο;
							</h5>
							<div class="pl-3 text-slate-800 dark:text-slate-300">{{ volunteer.description }}</div>
						</template>

						<template v-if="volunteer.expectations">
							<h5 class="mt-2 text-md font-bold text-slate-800 dark:text-slate-300">
								Ποιες είναι οι προσδοκίες σου από το FutureGeneration;
							</h5>
							<div class="pl-3 text-slate-800 dark:text-slate-300">{{ volunteer.expectations }}</div>
						</template>
						
						<template v-if="volunteer.interests">
							<h5 class="mt-2 text-md font-bold text-slate-800 dark:text-slate-300">
								Ποια είναι τα ενδιαφέροντά σου στην προσωπική σου ζωή
							</h5>
							<div class="pl-3 text-slate-800 dark:text-slate-300">{{ volunteer.interests }}</div>
						</template>
						
						<template v-if="volunteer.reason">
							<h5 class="mt-2 text-md font-bold text-slate-800 dark:text-slate-300">
								Με τι θα σε ενδιέφερε να ασχοληθείς στην ομάδα του FutureGeneration και γιατί;
							</h5>
							<div class="pl-3 text-slate-800 dark:text-slate-300">{{ volunteer.reason }}</div>
						</template>
					</VolunteerSection>
				</div>
			</div>

			<VolunteerStatusChangeModal
				:volunteerId="volunteer.id"
				:isOpen="showVolunteerStatusChangeModal" 
				:roles="roles"
				@update:isOpen="showVolunteerStatusChangeModal = $event"
				@change="changeVolunteerStatus"
			/>

			<VolunteerNotesModal
				:notes="volunteer.notes"
				:isOpen="showVolunteerNoteModal" 
				@update:isOpen="showVolunteerNoteModal = $event"
				@change="updateNotes"
			/>
		</template>
	</CustomNavbar>
</template>

<script setup>
	import { provide, ref, onMounted, reactive, computed } from 'vue';
	import { Inertia } from '@inertiajs/inertia';

	import CustomNavbar from '../Common/CustomNavbar.vue';

	import VolunteerStatusChangeModal from './VolunteerStatusChangeModal.vue';
	import SocialMediaLink from './SocialMediaLink.vue';

	import VolunteerSection from './ShowVolunteer/VolunteerSection.vue';
	import VSectionHeading from './ShowVolunteer/VSectionHeading.vue';
	import VSectionInfoGrid from './ShowVolunteer/VSectionInfoGrid.vue';
	import VSectionInfoGridItem from './ShowVolunteer/VSectionInfoGridItem.vue';

    import BaseClickButton from '@/Pages/Common/UI/Buttons/BaseClickButton.vue';
	import VolunteerStatus from '@/Pages/Common/Datatable/Columns/VolunteerStatus.vue';
	import VolunteerNotesModal from './VolunteerNotesModal.vue';

	import { useVolunteersStore } from '@/Stores/useVolunteer.store';
    import TheShowVolunteerCVModal from '@/Components/Modals/TheShowVolunteerCVModal.vue';

    const volunteerStore = useVolunteersStore();

	let props = defineProps({
		user: Object,
		volunteer: Object,
		response: Object,
		roles: Array,
		errors: Object,
        volunteerStatusDropdownOptions: {
            type: Array,
            default: () => []
        }
	});

	const selectedVolunteerStatus = ref(props.volunteer.status);
	const showVolunteerStatusChangeModal = ref( false );

	const tableFilters = ref({});
	const openCVModal = ref(false);

	onMounted(() => {
        tableFilters.value = volunteerStore.getTableFilters;
    });

	const form = reactive({
        id: props.volunteer.id ? props.volunteer.id : null,
        role: props.volunteer.role ? props.volunteer.role : null,
        notes: props.volunteer.notes ? props.volunteer.notes : "",
		assigned_to: props.volunteer.assigned_to ? props.volunteer.assigned_to : "",
        personality: {
            expectations: props.volunteer.expectations ? props.volunteer.expectations : "",
            reason: props.volunteer.reason ? props.volunteer.reason : "",
            interests: props.volunteer.interests ? props.volunteer.interests : "",
            description: props.volunteer.description ? props.volunteer.description : "",
        },
        personalInfo: {
            firstname: props.volunteer.firstname ? props.volunteer.firstname : "",
            lastname: props.volunteer.lastname ? props.volunteer.lastname : "",
            phone: props.volunteer.phone ? props.volunteer.phone : "",
            email: props.volunteer.email ? props.volunteer.email : "",
        },
        studies: {
            university: props.volunteer.university ? props.volunteer.university : "",
            department: props.volunteer.department ? props.volunteer.department : "",
            otherstudies: props.volunteer.otherstudies ? props.volunteer.otherstudies : "",
        },
        social: {
            linkedin: props.volunteer.linkedin ? props.volunteer.linkedin : "",
            facebook: props.volunteer.facebook ? props.volunteer.facebook : "",
            instagram: props.volunteer.instagram ? props.volunteer.instagram : "",
        },
        links: {
            googleDrive:props.volunteer.google_drive ? props.volunteer.google_drive : "",
            asana: props.volunteer.asana ? props.volunteer.asana : "",
        }
    })

	const showVolunteerNoteModal = ref(false);

	function editVolunteer( volunteerId ) {
        Inertia.get('/volunteers/' + volunteerId + '/edit')
    }

    function deleteVolunteer( volunteerId ) {
		Inertia.delete('/volunteers/' + volunteerId, {
            onBefore: () => confirm('Επιβεβαίωση διαγραφής?'),
        } );
    }

	function updateNotes( notes ) {
		Inertia.post('/volunteers/' + props.volunteer.id + '/notes', {
			notes: notes
		}, {
			preserveState: true,
			replace: true
		});

		setTimeout(() => {
			form.notes = props.volunteer.notes;
		}, 1000);
	}
	
	function handleStatusChange( event ) {
		showVolunteerStatusChangeModal.value = true;
	}
	
	function changeVolunteerStatus( form ) {
		Inertia.put('/volunteers/' + props.volunteer.id + '/status', {
			newStatusValue: selectedVolunteerStatus.value,
			statusChangeReason: form.reason,
			sendEmail: form.sendEmail
		}, { preserveState: true, replace: true });

		showVolunteerStatusChangeModal.value = false;
	}

	const volunteerProfileImage = computed( () => {
		if( props.volunteer.profile_image ) {
			return props.volunteer.profile_image;
		}

		switch( props.volunteer.gender ) {
			case 'male':
				return '/images/profile_picture_male.png';
			case 'female':
				return '/images/profile_picture_female.png';
			default:
				return 'https://placehold.co/32x32';
		}
	})
	const calculatedVolunteerStatus = computed( () => {
		let status = "";

		status = props.volunteerStatusDropdownOptions.find( (item) => {
			if( item.id === props.volunteer.status ) {
				return item.name;
			}
		});

		return status?.name ?? 'Άγνωστο';
	})

	const volunteerRole = computed( () => {
		return props.volunteer.volunteer_role ? JSON.parse(props.volunteer.volunteer_role).name : '';
	})

	const hasCV = computed( () => {
		return props.volunteer.cv && props.volunteer.cv.trim() !== '';
	})

	const hasStudies = computed( () => {
		return props.volunteer.university || props.volunteer.studies || props.volunteer.otherstuddies;
	})

	const hasProfessionalExperience = computed( () => {
		return props.volunteer.current_company || props.volunteer.current_role || props.volunteer.years_experience || props.volunteer.career_status;
	})

	const hasSocialMedia = computed( () => {
		return props.volunteer.socialMedia.some(item => item.link && item.link.trim() !== '');
	})

	provide('volunteerStatusDropdownOptions', props.volunteerStatusDropdownOptions);
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