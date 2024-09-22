<script setup>
    import { ref, computed } from 'vue';
    import { router } from '@inertiajs/vue3';
    import axios from 'axios';
	import { useToastNotification } from '@/Composables/useToastNotification';

    let props = defineProps({
        hollandCodes: Array,
        response: Object,
        user: {
            type: Object,
            required: true
        },
        testSubmissionId: Number,
    });

    const requestSessionFromCoach = ref( false );
	const { notify } = useToastNotification();

    function requestCareerSession( withSessionRequest ) {
        requestSessionFromCoach.value = withSessionRequest;

        console.log( props.user );

        axios.post('/session-requests', {
            firstname: props.user.firstname,
            lastname: props.user.lastname,
            email:  props.user.email,
            phone:  props.user.phone
        })
        .then((response) => {
            alert("Επιτυχής Καταχώρηση!");
            //notify('success', 'Ολοκληρώθηκε', `Η αίτηση συνεδρίας καταχωρήθηκε με επιτυχία!`);
        })
        
        // Send the request to the backend
        // router.post('session-requests', {
        //     firstname: props.user.firstname,
        //     lastname: props.user.lastname,
        //     email:  props.user.email,
        //     phone:  props.user.phone,
        // }, { 
        //     preserveState: true, 
        //     replace: true, 
        //     onSuccess: () => {
        //         notify('success', 'Ολοκληρώθηκε', `Η αίτηση συνεδρίας καταχωρήθηκε με επιτυχία!`);
        //     }
        // });
    }

    const adjustOpacity = (colorHex, opacity) => {
        // Extract the base color without the hash
        const baseColor = colorHex.substring(1);

        // Convert hex to RGB
        const r = parseInt(baseColor.slice(0, 2), 16);
        const g = parseInt(baseColor.slice(2, 4), 16);
        const b = parseInt(baseColor.slice(4, 6), 16);
        
        // Increase RGB values towards 255 (white)
        const lightR = Math.min(255, r + (255 - r) * opacity);
        const lightG = Math.min(255, g + (255 - g) * opacity);
        const lightB = Math.min(255, b + (255 - b) * opacity);

        // Return the RGBA color
        return  `rgba(${lightR}, ${lightG}, ${lightB}, ${opacity})`;
    };
</script>

<template>
    <div class="relative overflow-hidden flex flex-column justify-content-center">

        <div class="bg-circle opacity-50" :style="{ top: '-200px', left: '-700px' }"></div>
        <div class="bg-circle hidden lg:flex" :style="{ top: '50px', right: '-800px', transform: 'rotate(60deg)' }"></div>

        <div class="landing-wrapper">
            <div class="py-4 px-4 mx-0 md:mx-6 lg:mx-8 lg:px-8 z-2">
                <div id="personality-summary" class="my-2 py-2 md:my-5 md:py-5">
                    <div class="text-center">
                        <i class="pi pi-check-circle text-primary" style="color: green !important; font-size: 5rem;"></i>
                    </div>

                    <span class="text-900 block font-bold text-4xl mb-4 text-center">
                        Μπράβο, ολοκλήρωσες το τεστ με επιτυχία!
                    </span>
                    <span class="text-700 block text-2xl mb-5 text-center line-height-3">
                        Σύμφωνα με τις απαντήσεις που έδωσες και την θεωρία του ψυχολόγου J. Holland, η προσωπικότητά σου αναλύεται στις παρακάτω τρείς επικρατέστερες κατηγορίες. Αυτές δείχνουν μια ενδεχομένως κλίση, ένα ενδιαφέρον ή ίσως κάτι που καλλιεργείς χρόνια και δεν ετυχε να το αντιληφθείς.
                    </span>

                    <div class="grid mb-5">
                        <div
                            v-for="code in hollandCodes"
                            :key=code.id
                            class="col-12 md:col-6 xl:col-4 flex justify-content-center p-3"
                        >
                            <div
                                class="box p-4 w-full border rounded-xl border-blue-500"
                                :style="`background-color: ${adjustOpacity(code.color, 0.1)} !important;`"
                            >
                                <!-- <img :src="`/demo/images/landing/icon-components.svg`" alt="components icon" class="block mb-3" /> -->
                             
                                <ProgressBar
                                    :value="code.value"
                                    :color="code.color"
                                    class="mb-4"
                                />
                                
                                <span class="text-900 block font-semibold mb-3 text-3xl" :style="`color: ${code.color}`">
                                    {{ code.name }}
                                </span>

                                <p class="m-0 text-secondary text-700 text-xl">
                                    {{ code.description }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- <span class="text-700 block text-2xl mb-5 text-center line-height-3">
                        Εάν βρήκες τα αποτελέσματα ενδιαφέρον, συνέχισε ανακαλύπτοντας περισσότερα για τους σχετικούς κλάδους-τομείς εργασίας.
                    </span> -->
                    
                    <!-- <div class="mt-2 text-center">
                        <Button
                            @click="nextStep" 
                            type="submit" 
                            label="Θέλω να συνεχίσω"
                            icon="pi pi-arrow-right"
                            raised 
                            iconPos="right"
                            class="w-20rem text-xl"
                        />
                    </div> -->
                </div>

                <div id="sessionRequest" class="my-0 py-0 md:my-5 md:py-5">
                    <span class="text-900 block font-bold text-3xl mb-4 text-center">
                        Είσαι έτοιμος να κάνεις το επόμενο βήμα;
                    </span>
                    <span class="text-700 block text-2xl mb-4 text-center line-height-3">
                        Το επόμενο βήμα, είναι να κλείσεις ένα δωρεάν ραντεβού 30-45 λεπτών με έναν από τους συμβούλους μας. Αυτό θα σε βοηθήσει να καταλάβεις καλύτερα τις επιλογές σου και να προσανατολιστείς σωστά. Αν πιστεύεις οτι το έχεις ανάγκη, κάνε το κλίκ και τα υπόλοιπα άσε τα πάνω μας.
                    </span>

                    <div class="mt-2 text-center">
                        <Button
                            @click="requestCareerSession( true )" 
                            type="submit" 
                            label="Θέλω την δωρεάν συνεδρία"
                            class="w-20rem my-1 *: text-xl"
                        />

                        <!-- <br />

                        <Button
                            @click="requestCareerSession( false )" 
                            type="submit" 
                            label="Όχι, δεν το χρειάζομαι"
                            outlined
                            class="w-20rem my-1 text-lg"
                        /> -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
    .bg-circle {
        width: 1000px;
        height: 1000px;
        border-radius: 50%;
        background-image: linear-gradient(140deg, var(--primary-color), var(--surface-ground) 80%);
        position: absolute;
        opacity: 0.25;
        z-index: -1;
    }

    .p-progressbar {
        background: #d9d9d9;
    }
    
    .p-progressbar >>> .p-progressbar-label	 {
        color: white;
    }

   .p-progressbar >>> .p-progressbar-value {
        background: linear-gradient(to bottom right, #f97316, #ec4899, #a855f7) !important;
    }
</style>