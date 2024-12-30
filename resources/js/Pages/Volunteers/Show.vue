<script setup>
/* Core */
import { computed, ref, provide, watch } from 'vue';
import { router } from '@inertiajs/vue3'

/* Layouts */
import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
import SocialMediaLink from './SocialMediaLink.vue';
import VolunteerSection from './ShowVolunteer/VolunteerSection.vue';
import VSectionHeading from './ShowVolunteer/VSectionHeading.vue';
import VSectionInfoGridItem from './ShowVolunteer/VSectionInfoGridItem.vue';
import TheShowVolunteerCVModal from '@/Components/Modals/TheShowVolunteerCVModal.vue';
import VolunteerStatusChangeModal from '@/Components/Modals/VolunteerStatusChangeModal.vue';
import VolunteerNotesModal from '@/Components/Modals/VolunteerNotesModal.vue';
import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
import HistoryCard from './HistoryCard.vue';

import TheVolunteerCommentModal from '@/Components/Modals/TheVolunteerCommentModal.vue';
import TheCommentList from '@/Components/TheCommentList.vue';
import BaseFormattedText from '@/Components/Base/BaseFormattedText.vue';
import BaseTimeline from '@/Components/Base/BaseTimeline.vue';

import { useVolunteerStatusMapper } from '@/Composables/useVolunteerStatusMapper';
import { useToastNotification } from '@/Composables/useToastNotification';
import { useToast } from 'primevue/usetoast';

let props = defineProps({
	user: Object,
	volunteer: Object,
	comments: Array,
	response: Object,
	roles: Array,
	errors: Object,
    teamDropdownOptions: {
        type: Array,
        default: () => []
    },
	volunteerStatusDropdownOptions: {
		type: Array,
		default: () => []
	},
	volunteerAssignedRecruiterDropdownOptions: {
		type: Array,
		default: () => []
	},
	volunteerHistory: {
		type: Array,
		default: () => []
	}
});

const { adjustOpacity, determineTextColor } = useVolunteerStatusMapper(props.volunteerStatusDropdownOptions);
const { notify } = useToastNotification();
const toast = useToast();

const selectedVolunteerStatus = ref(props.volunteer.status);
const selectedAssignedRecruiter = ref(props.volunteer.assigned_to);
const selectedTeam = ref(props.volunteer.team_id);
const showVolunteerNotesModal = ref(false);

function submitComment(comment) {
	router.post('/volunteers/' + props.volunteer.id + '/comment', {
		comment: comment
	}, {
		preserveState: true,
		replace: true,
		onSuccess: () => {
			console.log("xaxa")
			let saveNotesMsg = 'Οι σημειώσεις του εθελοντή ενημερώθηκαν επιτυχώς.';
			notify('success', 'Ολοκληρώθηκε', saveNotesMsg);
		},
	});
}

function updateNotes(notes) {
	router.post('/volunteers/' + props.volunteer.id + '/notes', {
		notes: notes
	}, {
		preserveState: true,
		replace: true,
		onSuccess: () => {
			// Popup a notification for the susccessful update of the notes.
			let saveNotesMsg = 'Οι σημειώσεις του εθελοντή ενημερώθηκαν επιτυχώς.';
			notify('success', 'Ολοκληρώθηκε', saveNotesMsg);

			// Close the volunteer notes modal.
			showVolunteerNotesModal.value = false;
		},
	});
}

function handleTeamChange(event) {
    selectedTeam.value = event.target.value;

    console.log("asdas")
    router.put('/volunteers/' + props.volunteer.id + '/team', {
        teamId: selectedTeam.value,
    }, {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            // Popup a notification
            let changeTeamMsg = 'Η ομάδα του εθελοντή άλλαξε επιτυχώς.';
            notify('success', 'Ολοκληρώθηκε', changeTeamMsg);
        }
    });
}

function changeVolunteerStatus(form) {
	router.put('/volunteers/' + props.volunteer.id + '/status', {
		newStatusValue: selectedVolunteerStatus.value,
		statusChangeReason: form.reason,
		sendEmail: form.sendEmail
	}, {
		preserveState: true,
		replace: true,
		onSuccess: () => {
			// Popup a notification
			let changeStatusMsg = 'Η κατάσταση του εθελοντή ' + props.volunteer.firstname + ' ' + props.volunteer.lastname + ' άλλαξε επιτυχώς.';
			notify('success', 'Ολοκληρώθηκε', changeStatusMsg);

			// Close the modal
			showVolunteerStatusChangeModal.value = false;
		}
	});
}

const volunteerProfileImage = computed(() => {
	if (props.volunteer.profile_image) {
		return props.volunteer.profile_image;
	}

	switch (props.volunteer.gender) {
		case 'male':
			return '/images/profile_picture_male.png';
		case 'female':
			return '/images/profile_picture_female.png';
		default:
			return 'https://placehold.co/100x100';
	}
})

const volStatusDropdownOptions = computed(() => {
	return props.volunteerStatusDropdownOptions.map(option => ({
		id: option.id,
		label: option.name
	}));
});

const teamDropdownOptions = computed(() => {
	return props.teamDropdownOptions.map(option => ({
		id: option.id,
		label: option.name
	}));
});

const volAssignedRecruiterDropdownOptions = computed(() => {
	return props.volunteerAssignedRecruiterDropdownOptions.map(option => ({
		id: option.id,
		label: option.firstname + ' ' + option.lastname
	}));
});

const volunteerStatus = computed(() => {
	let status = "";

	status = props.volunteerStatusDropdownOptions.find((item) => {
		if (item.id === props.volunteer.status) {
			return item;
		}
	});

	return status;
})

const volunteerRole = computed(() => {
	return props.volunteer.volunteer_role ? JSON.parse(props.volunteer.volunteer_role).name : '';
})

const hasCV = computed(() => {
	return props.volunteer.cv && props.volunteer.cv.trim() !== '';
})

const hasStudies = computed(() => {
	return props.volunteer.university || props.volunteer.studies || props.volunteer.otherstuddies;
})

const hasProfessionalExperience = computed(() => {
	return props.volunteer.current_company || props.volunteer.current_role || props.volunteer.years_experience || props.volunteer.career_status;
})

const hasSocialMedia = computed(() => {
	return props.volunteer.socialMedia.some(item => item.link && item.link.trim() !== '');
})

provide('volunteerStatusDropdownOptions', props.volunteerStatusDropdownOptions);

const showVolunteerStatusChangeModal = ref(false);

watch(() => selectedVolunteerStatus.value, (newVal) => {
	showVolunteerStatusChangeModal.value = true;
});

watch(() => selectedTeam.value, (newVal) => {
    router.put('/volunteers/' + props.volunteer.id + '/team', {
        teamId: selectedTeam.value,
    }, {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            // Popup a notification
            let changeTeamMsg = 'Η ομάδα του εθελοντή άλλαξε επιτυχώς.';
            notify('success', 'Ολοκληρώθηκε', changeTeamMsg);
        }
    });
});

watch(() => selectedAssignedRecruiter.value, (newVal) => {
	router.put('/volunteers/' + props.volunteer.id + '/assign-recruiter', {
		recruiterId: selectedAssignedRecruiter.value,
	}, {
		preserveState: true,
		replace: true,
		onSuccess: () => {
			// Popup a notification
			let assignedRecruiterMsg = 'Ο εθελοντής σας ανατέθηκε επιτυχώς!';
			notify('success', 'Ολοκληρώθηκε', assignedRecruiterMsg);
		}
	});
});


const volunteerStatusInfo = computed(() => {
	return `<span class="whitespace-nowrap text-md mr-2 border-l-2 px-4 py-1 rounded-md shadow-md"
					style="background-color: ${adjustOpacity(volunteerStatus.value.id, 0.2)}; color: ${determineTextColor(volunteerStatus.value.id)};">
					${volunteerStatus.value.name}
				</span>`;
});

const vInfo = computed(() => {
	try {
		return JSON.parse(props.volunteer.additional_info);
	} catch (e) {
		console.error('Error parsing additional_info:', e);
		return {};
	}
});
</script>

<style scoped>
.grid {
	grid-template-columns: 1fr 2fr;
	/* Adjust these fractions according to your needs */
}

.label {
	max-width: 80px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
</style>

<template>
	<AppPageWrapper>
		<template #page-title>
		</template>

		<template #page-content>
			<div class="grid">
				<div class="col">
					<!-----------------------------------------------------------------------------------------
					| HERO SECTION
					------------------------------------------------------------------------------------------>
					<div class="flex flex-col md:flex-row items-center gap-6">
						<!-- Text Block Section -->
						<div class="gap-2">
							<div class="grid">
								<div class="col-fixed m-3" style="width:100px">
									<img :src="volunteerProfileImage" alt="">
								</div>
								<div class="col">
									<div class="text-3xl font-bold text-primary">
										{{ volunteer.firstname + " " + volunteer.lastname }}
									</div>

									<div class="text-xl py-2">
										{{ volunteerRole }}
									</div>

									<div v-html="volunteerStatusInfo" class="text-xl mt-2"></div>
								</div>
							</div>
							<!-- <select
								v-model="selectedVolunteerStatus"
								@change="handleStatusChange( $event )"
								class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-lime-500 focus:border-lime-500 block p-2 dark:bg-slate-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-slate-200 dark:focus:ring-lime-500 dark:focus:border-lime-500"
							>
								<option
									v-for="statusItem in volunteerStatusDropdownOptions"
									:value="statusItem.id"
									v-html="statusItem.name"
								></option>
							</select> -->

							<VolunteerStatusChangeModal
                                :volunteerId="volunteer.id"
								:isOpen="showVolunteerStatusChangeModal"
								@update:isOpen="showVolunteerStatusChangeModal = $event"
								@change="changeVolunteerStatus"
                            />

							<BaseDropdownInput
                                v-model="selectedVolunteerStatus"
                                label="Κατάσταση Εθελοντή"
								:options="volStatusDropdownOptions"
                                @change="handleStatusChange(event)"
                            />

							<BaseDropdownInput
                                v-model="selectedAssignedRecruiter"
                                label="Υπεύθυνος Recruiter"
								:options="volAssignedRecruiterDropdownOptions"
                            />

                            <BaseDropdownInput
                                v-model="selectedTeam"
                                label="Ομάδα"
								:options="teamDropdownOptions"
                            />

							<VSectionInfoGridItem
                                v-if="volunteer.disapproved_reason"
                                label="Τελευταίο Σχόλιο"
								:value="volunteer.disapproved_reason"
                            />
						</div>
					</div>

					<!-----------------------------------------------------------------------------------------
					| PERSONAL INFORMATION
					------------------------------------------------------------------------------------------>
					<VolunteerSection :sectionId="'personal-information'">
						<template #header>
							<VSectionHeading>Προσωπικά Στοιχεία</VSectionHeading>
						</template>

						<VSectionInfoGridItem label="Όνομα" :value="volunteer.firstname" />
						<VSectionInfoGridItem label="Επώνυμο" :value="volunteer.lastname" />
						<VSectionInfoGridItem label="Τηλέφωνο" :value="volunteer.phone" />
						<VSectionInfoGridItem label="Email" :value="volunteer.email" />

						<VSectionInfoGridItem v-if="volunteer.date_of_birth" label="Ημ/νία Γέννησης"
							:value="volunteer.date_of_birth" />
						<VSectionInfoGridItem v-if="volunteer.age" label="Ηλικία" :value="volunteer.age" />
						<VSectionInfoGridItem v-if="volunteer.gender" label="Φύλλο" :value="volunteer.gender" />
						<VSectionInfoGridItem v-if="volunteer.city" label="Πόλη" :value="volunteer.city" />
						<VSectionInfoGridItem v-if="volunteer.address" label="Διεύθυνση" :value="volunteer.address" />

						<!-- <VSectionInfoGrid> -->

						<!-- </VSectionInfoGrid> -->
					</VolunteerSection>


					<!-----------------------------------------------------------------------------------------
					| VOLUNTEERING EXPERIENCE
					------------------------------------------------------------------------------------------>
					<VolunteerSection :sectionId="'volunteering-experience'">
						<template #header>
							<VSectionHeading>Εθελοντική Συμμετοχή</VSectionHeading>
						</template>
						<VSectionInfoGridItem v-if="volunteer.start_date" label="Ημ/νία Αίτησης"
							:value="volunteer.start_date" />
						<VSectionInfoGridItem v-if="volunteer.end_date" label="Ημ/νία Αποχώρησης"
							:value="volunteer.end_date" />
						<VSectionInfoGridItem v-if="vInfo?.volunteering?.marketing_channel" label="Κανάλι Marketing"
							:value="vInfo.volunteering.marketing_channel" />

						<VSectionInfoGridItem v-if="volunteer.hour_per_week" label="Διαθεσιμότητα (h/w)"
							:value="volunteer.hour_per_week" />
						<VSectionInfoGridItem v-if="volunteer.previous_volunteering" label="Προηγούμενη εθ. εμπειρία"
							:value="volunteer.previous_volunteering" />


						<!-- <VSectionInfoGridItem label="Ολοκλήρωση του Onboarding" :value="volunteer.onboarding_completed ? 'Ναι' : 'Όχι'" /> -->
						<!-- <VSectionInfoGridItem v-if="volunteer" label="Ρόλος Εθελοντή" :value="JSON.parse(volunteer.volunteer_role).name" /> -->
						<!-- <VSectionInfoGridItem v-if="volunteer.hours_contributed" label="Ώρες Συνεισφοράς" :value="volunteer.hours_contributed" /> -->
						<!-- <VSectionInfoGridItem v-if="volunteer.previous_volunteer_experience" label="Προυπηρεσία" :value="volunteer.previous_volunteer_experience" /> -->
					</VolunteerSection>

					<!-----------------------------------------------------------------------------------------
					| STUDIES
					------------------------------------------------------------------------------------------>
					<VolunteerSection :sectionId="'studies'" v-if="hasStudies">
						<template #header>
							<VSectionHeading>Σπουδές & Εκπαίδευση</VSectionHeading>
						</template>

						<VSectionInfoGridItem v-if="volunteer.university" label="Πανεπιστήμιο"
							:value="volunteer.university" />
						<VSectionInfoGridItem v-if="volunteer.department" label="Τμήμα" :value="volunteer.department" />
						<VSectionInfoGridItem v-if="volunteer.otherstuddies" label="Επιπλέον Σπουδές"
							:value="volunteer.otherstudies" />
					</VolunteerSection>

					<!-----------------------------------------------------------------------------------------
					| PROFESSIONAL EXPERIENCE
					------------------------------------------------------------------------------------------>
					<VolunteerSection :sectionId="'professional-experience'" v-if="hasProfessionalExperience">
						<template #header>
							<VSectionHeading>Επαγγελματική Εμπειρία</VSectionHeading>
						</template>

						<VSectionInfoGridItem v-if="volunteer.current_company" label="Εταιρεία"
							:value="volunteer.current_company" />
						<VSectionInfoGridItem v-if="volunteer.current_role" label="Ρόλος"
							:value="volunteer.current_role" />
						<VSectionInfoGridItem v-if="volunteer.years_experience" label="Χρόνια Εργασίας"
							:value="volunteer.years_experience" />
						<VSectionInfoGridItem v-if="volunteer.career_status" label="Κατάσταση Καριέρας"
							:value="volunteer.career_status" />
					</VolunteerSection>

					<!-----------------------------------------------------------------------------------------
					| CV
					------------------------------------------------------------------------------------------>
					<TheShowVolunteerCVModal v-if="hasCV" :cv="volunteer.cv"></TheShowVolunteerCVModal>

					<!-----------------------------------------------------------------------------------------
						| SOCIAL MEDIA
					------------------------------------------------------------------------------------------>
					<VSectionHeading v-if="hasSocialMedia">Social Media</VSectionHeading>

					<template v-if="hasSocialMedia">
						<div class="flex flex-row">
							<template v-for="(sm, smIndex) in volunteer.socialMedia" :key="smIndex">
								<SocialMediaLink v-if="sm.link" :label="sm.label" :link="sm.link" class="m-1" />
							</template>
						</div>
					</template>

				</div>
				<div class="col">
					<VolunteerSection :sectionId="'notes'">
						<template #header>
							<VSectionHeading>
								<span>Σημειώσεις</span>

								<template #actions>
									<Button
										@click="showVolunteerNotesModal = true"
										icon="pi pi-pencil"
										text
										rounded
									/>
								</template>
							</VSectionHeading>
						</template>

						<BaseFormattedText
							:text="volunteer.notes"
						/>

						<VolunteerNotesModal
							:notes="volunteer.notes ?? ''"
							:show-modal="showVolunteerNotesModal"
							@change="updateNotes"
						/>
					</VolunteerSection>

					<VolunteerSection :sectionId="'comments'">
						<template #header>
							<VSectionHeading>
								<span>Σχόλια</span>
							</VSectionHeading>
						</template>

						<BaseTimeline :items="props.comments"/>

						<!-- <TheCommentList :comments="props.comments" /> -->

						<TheVolunteerCommentModal
							@submitComment="submitComment"
						/>
					</VolunteerSection>

					<!-----------------------------------------------------------------------------------------
						| PERSONALITY
					------------------------------------------------------------------------------------------>
					<VolunteerSection :sectionId="'personality'">
						<template #header>
							<VSectionHeading>Προσωπικότητα</VSectionHeading>
						</template>

						<template v-if="volunteer.volunteering_details">
							<h5 class="mt-2 text-md ">
								Περιέγραψε τον εθελοντικό ρόλο και τις αρμοδιότητες που είχες
							</h5>
							<div>{{ volunteer.volunteering_details }}</div>
						</template>

						<template v-if="volunteer.expectations">
							<h5 class="mt-2 text-md ">
								Τι προσδοκίες έχεις από τον οργανισμό και τη συνεργασία μας;
							</h5>
							<div>{{ volunteer.expectations }}</div>
						</template>

						<template v-if="volunteer.interests">
							<h5 class="mt-2 text-md ">
								Τι εμπειρίες ή δεξιότητες έχεις που σε καθιστούν κατάλληλο για αυτόν τον ρόλο;
							</h5>
							<div>{{ volunteer.interests }}</div>
						</template>

						<template v-if="volunteer.description">
							<h5 class="mt-2 text-md ">
								Πως θα περιέγραφες τον εαυτό σου σε μια παράγραφο;
							</h5>
							<div>{{ volunteer.description }}</div>
						</template>

						<template v-if="volunteer.reason">
							<h5 class="mt-2 text-md">
								Με τι θα σε ενδιέφερε να ασχοληθείς στην ομάδα του FutureGeneration και γιατί;
							</h5>
							<div>{{ volunteer.reason }}</div>
						</template>
					</VolunteerSection>


					<VolunteerSection :sectionId="'personality'">
						<template #header>
							<VSectionHeading>Ιστορικό Ενεργειών</VSectionHeading>
						</template>

						<div class="flex flex-row gap-0 md:flex-column overflow-auto">
							<HistoryCard v-for="user in volunteerHistory" :key="user" :user="user" />
						</div>
					</VolunteerSection>
				</div>
			</div>
		</template>
	</AppPageWrapper>
</template>
