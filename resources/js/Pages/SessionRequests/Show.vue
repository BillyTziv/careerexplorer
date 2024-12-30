<script setup>
/* Core */
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3'

/* Layouts */
import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
// import SocialMediaLink from './SocialMediaLink.vue';
import VolunteerSection from '../Volunteers/ShowVolunteer/VolunteerSection.vue';
import VSectionHeading from '../Volunteers/ShowVolunteer/VSectionHeading.vue';
import VSectionInfoGridItem from '../Volunteers/ShowVolunteer/VSectionInfoGridItem.vue';
// import TheShowVolunteerCVModal from '@/Components/Modals/TheShowVolunteerCVModal.vue';
// import sessionRequestStatusChangeModal from '@/Components/Modals/sessionRequestStatusChangeModal.vue';
import SessionRequestNotesModal from '@/Components/Modals/SessionRequestNotesModal.vue';
// import BaseDropdownInput from '@/Components/Base/BaseDropdownInput.vue';
import HistoryCard from '../Volunteers/HistoryCard.vue';

import TheVolunteerCommentModal from '@/Components/Modals/TheVolunteerCommentModal.vue';
// import TheCommentList from '@/Components/TheCommentList.vue';
import BaseFormattedText from '@/Components/Base/BaseFormattedText.vue';
import BaseTimeline from '@/Components/Base/BaseTimeline.vue';

// import { usesessionRequestStatusMapper } from '@/Composables/usesessionRequestStatusMapper';
import { useToastNotification } from '@/Composables/useToastNotification';
import { useToast } from 'primevue/usetoast';

let props = defineProps({
	user: Object,
	sessionRequest: Object,
	comments: Array,
	response: Object,
	errors: Object,
	sessionRequestStatusDropdownOptions: {
		type: Array,
		default: () => []
	}
});

const { notify } = useToastNotification();

const showSessionRequestNotesModal = ref(false);
const completeSRDialog = ref(false);

function updateNotes(notes) {
	router.post('/session-requests/' + props.sessionRequest.id + '/notes', {
		notes: notes
	}, {
		preserveState: true,
		replace: true,
		onSuccess: () => {
			// Popup a notification for the susccessful update of the notes.
			let saveNotesMsg = 'Οι σημειώσεις της συνεδρίας ενημερώθηκαν επιτυχώς.';
			notify('success', 'Ολοκληρώθηκε', saveNotesMsg);

			// Close the volunteer notes modal.
			showSessionRequestNotesModal.value = false;
		},
	});
}

// function changesessionRequestStatus(form) {
// 	router.put('/volunteers/' + props.sessionRequest.id + '/status', {
// 		newStatusValue: selectedsessionRequestStatus.value,
// 		statusChangeReason: form.reason,
// 		sendEmail: form.sendEmail
// 	}, {
// 		preserveState: true,
// 		replace: true,
// 		onSuccess: () => {
// 			// Popup a notification
// 			let changeStatusMsg = 'Η κατάσταση του εθελοντή ' + props.sessionRequest.firstname + ' ' + props.sessionRequest.lastname + ' άλλαξε επιτυχώς.';
// 			notify('success', 'Ολοκληρώθηκε', changeStatusMsg);

// 			// Close the modal
// 			showsessionRequestStatusChangeModal.value = false;
// 		}
// 	});
// }

const volunteerProfileImage = computed(() => {
	if (props.sessionRequest.profile_image) {
		return props.sessionRequest.profile_image;
	}

	switch (props.sessionRequest.gender) {
		case 'male':
			return '/images/profile_picture_male.png';
		case 'female':
			return '/images/profile_picture_female.png';
		default:
			return 'https://placehold.co/100x100';
	}
})

// const volStatusDropdownOptions = computed(() => {
// 	return props.sessionRequestStatusDropdownOptions.map(option => ({
// 		id: option.id,
// 		label: option.name
// 	}));
// });

// const volAssignedRecruiterDropdownOptions = computed(() => {
// 	return props.sessionRequestAssignedRecruiterDropdownOptions.map(option => ({
// 		id: option.id,
// 		label: option.firstname + ' ' + option.lastname
// 	}));
// });

const sessionRequestStatus = computed(() => {
	let status = "";

	status = props.sessionRequestStatusDropdownOptions.find((item) => {
		if (item.id === props.sessionRequest.status) {
			return item;
		}
	});

	return status;
})


// const hasCV = computed(() => {
// 	return props.sessionRequest.cv && props.sessionRequest.cv.trim() !== '';
// })

// const hasStudies = computed(() => {
// 	return props.sessionRequest.university || props.sessionRequest.studies || props.sessionRequest.otherstuddies;
// })

// const hasProfessionalExperience = computed(() => {
// 	return props.sessionRequest.current_company || props.sessionRequest.current_role || props.sessionRequest.years_experience || props.sessionRequest.career_status;
// })

// const hasSocialMedia = computed(() => {
// 	return props.sessionRequest.socialMedia.some(item => item.link && item.link.trim() !== '');
// })

// provide('sessionRequestStatusDropdownOptions', props.sessionRequestStatusDropdownOptions);

// const showsessionRequestStatusChangeModal = ref(false);

// watch(() => selectedsessionRequestStatus.value, (newVal) => {
// 	showsessionRequestStatusChangeModal.value = true;
// });

// watch(() => selectedAssignedRecruiter.value, (newVal) => {
// 	router.put('/volunteers/' + props.sessionRequest.id + '/assign-recruiter', {
// 		recruiterId: selectedAssignedRecruiter.value,
// 	}, {
// 		preserveState: true,
// 		replace: true,
// 		onSuccess: () => {
// 			// Popup a notification
// 			let assignedRecruiterMsg = 'Ο εθελοντής σας ανατέθηκε επιτυχώς!';
// 			notify('success', 'Ολοκληρώθηκε', assignedRecruiterMsg);
// 		}
// 	});
// });


// const sessionRequestStatusInfo = computed(() => {
// 	return `<span class="whitespace-nowrap text-md mr-2 border-l-2 px-4 py-1 rounded-md shadow-md"
// 					style="background-color: ${adjustOpacity(sessionRequestStatus.value.id, 0.2)}; color: ${determineTextColor(sessionRequestStatus.value.id)};">
// 					${sessionRequestStatus.value.name}
// 				</span>`;
// });

const vInfo = computed(() => {
	try {
		return JSON.parse(props.sessionRequest.additional_info);
	} catch (e) {
		console.error('Error parsing additional_info:', e);
		return {};
	}
});

const confirmCompleteSR = (sessionRequest) => {
    completeSRDialog.value = true;
};

const completeSR = () => {
    router.put(`/session-requests/${props.sessionRequest.id}/complete`, {},
        {
            preserveState: true,
            replace: true,
            onSuccess: () => {
                completeSRDialog.value = false;

                notify('success', 'Ολοκληρώθηκε', `Η συνεδρία επαγγελματικού προσανατολισμού ολοκληρώθηκε επιτυχώς.`);
            }
        }
    );
};
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
			<!-- <div class="flex flex-column">
				<div class="px-2 flex text-center justify-center">{{ sessionRequest.firstname + " " + sessionRequest.lastname }}</div>
			</div> -->

			<div class="flex p-2">
				<!-- <sessionRequestStatusChangeModal
					:volunteerId="sessionRequest.id"
					:roles="roles"
					@change="changesessionRequestStatus"
				/> -->



				<!-- <select

					@change="handleStatusChange( $event )"
					class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-lime-500 focus:border-lime-500 block p-2 dark:bg-slate-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-slate-200 dark:focus:ring-lime-500 dark:focus:border-lime-500"
				>
					<option
						v-for="statusItem in sessionRequestStatusDropdownOptions"
						:value="statusItem.id"
						v-html="statusItem.name"
					></option>
				</select> -->
			</div>
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
										{{ sessionRequest.firstname + " " + sessionRequest.lastname }}
									</div>

                                    <br />

                                    <Button
                                        @click="confirmCompleteSR"
                                        icon="pi pi-check"
                                        label="Ολοκλήρωση Συνεδρίας"
                                    />

									<!-- <div class="text-xl py-2">
										{{ volunteerRole }}
									</div> -->

									<!-- <div v-html="sessionRequestStatusInfo" class="text-xl mt-2"></div> -->
								</div>
							</div>
							<!-- <select
								v-model="selectedsessionRequestStatus"
								@change="handleStatusChange( $event )"
								class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-lime-500 focus:border-lime-500 block p-2 dark:bg-slate-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-slate-200 dark:focus:ring-lime-500 dark:focus:border-lime-500"
							>
								<option
									v-for="statusItem in sessionRequestStatusDropdownOptions"
									:value="statusItem.id"
									v-html="statusItem.name"
								></option>
							</select> -->
<!--
							<sessionRequestStatusChangeModal :volunteerId="sessionRequest.id"
								:isOpen="showsessionRequestStatusChangeModal"
								@update:isOpen="showsessionRequestStatusChangeModal = $event"
								@change="changesessionRequestStatus" /> -->

							<!-- <BaseDropdownInput v-model="selectedsessionRequestStatus" label="Κατάσταση Εθελοντή"
								:options="volStatusDropdownOptions" @change="handleStatusChange(event)" /> -->

							<!-- <VSectionInfoGridItem v-if="sessionRequest.disapproved_reason" label="Τελευταίο Σχόλιο"
								:value="sessionRequest.disapproved_reason" /> -->
						</div>
					</div>

					<!-----------------------------------------------------------------------------------------
					| PERSONAL INFORMATION
					------------------------------------------------------------------------------------------>
					<VolunteerSection :sectionId="'personal-information'">
						<template #header>
							<VSectionHeading>Προσωπικά Στοιχεία</VSectionHeading>
						</template>

						<VSectionInfoGridItem label="Όνομα" :value="sessionRequest.firstname" />
						<VSectionInfoGridItem label="Επώνυμο" :value="sessionRequest.lastname" />
						<VSectionInfoGridItem label="Τηλέφωνο" :value="sessionRequest.phone" />
						<VSectionInfoGridItem label="Email" :value="sessionRequest.email" />

						<VSectionInfoGridItem v-if="sessionRequest.date_of_birth" label="Ημ/νία Γέννησης" :value="sessionRequest.date_of_birth" />
						<VSectionInfoGridItem v-if="sessionRequest.age" label="Ηλικία" :value="sessionRequest.age" />
						<VSectionInfoGridItem v-if="sessionRequest.gender" label="Φύλλο" :value="sessionRequest.gender" />
						<VSectionInfoGridItem v-if="sessionRequest.city" label="Πόλη" :value="sessionRequest.city" />
						<VSectionInfoGridItem v-if="sessionRequest.address" label="Διεύθυνση" :value="sessionRequest.address" />

					</VolunteerSection>
				</div>
				<div class="col">
					<VolunteerSection :sectionId="'notes'">
						<template #header>
							<VSectionHeading>
								<span>Σημειώσεις Συνεδριών</span>

								<template #actions>
									<Button
										@click="showSessionRequestNotesModal = true"
										icon="pi pi-pencil"
										text
										rounded
									/>
								</template>
							</VSectionHeading>
						</template>

						<BaseFormattedText
							:text="sessionRequest.notes"
						/>

						<SessionRequestNotesModal
							:notes="sessionRequest.notes ?? ''"
							:show-modal="showSessionRequestNotesModal"
							@change="updateNotes"
						/>
					</VolunteerSection>

					<VolunteerSection :sectionId="'comments'">
						<!-- <template #header>
							<VSectionHeading>
								<span>Σχόλια</span>
							</VSectionHeading>
						</template> -->

						<!-- <BaseTimeline :items="props.comments"/> -->

						<!-- <TheVolunteerCommentModal
							@submitComment="submitComment"
						/> -->
					</VolunteerSection>

					<VolunteerSection :sectionId="'personality'">
						<!-- <template #header>
							<VSectionHeading>Ιστορικό Ενεργειών</VSectionHeading>
						</template> -->

						<div class="flex flex-row gap-0 md:flex-column overflow-auto">
							<!-- <HistoryCard v-for="user in volunteerHistory" :key="user" :user="user" /> -->
						</div>
					</VolunteerSection>
				</div>
			</div>
		</template>
	</AppPageWrapper>

    <Dialog v-model:visible="completeSRDialog" :style="{ width: '450px' }" header="Επιβεβαίωση Ολοκλήρωσης"
        :modal="true">
        <div class="flex align-items-center justify-content-center">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
            <span>
                Είστε σίγουροι πως θέλετε να ολοκληρώσετε την συνεδρία? Με την ενέργεια αυτή, θα σταλεί ένα ενημερωτικό
                email στον υποψήφιο.
            </span>
        </div>

        <template #footer>
            <Button label="No" icon="pi pi-times" text @click="completeSRDialog = false" />
            <Button label="Yes" icon="pi pi-check" text @click="completeSR" />
        </template>
    </Dialog>
</template>
