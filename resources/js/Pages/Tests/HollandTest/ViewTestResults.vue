<template>
    <div class="p-3 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-5 bg-white shadow">

           <div class="resultMessage mt-5">
                <div style="margin: auto; max-width: 55px;">
                    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                
                <div class="text-center mb-4">
                    <div class="text-center mb-4">
                        <div class="title">Μπράβο! Ολοκλήρωσες το τεστ με επιτυχία!</div>
                    </div>

                    <p class="mb-0 text-center text-md ">
                        Παρακάτω θα βρεις τα αποτελέσματα του τεστ. Αν τα βρίσκεις σκούρα, τώρα μπορείς να κλείσεις μια ΔΩΡΕΑΝ συνεδρία με τους συμβούλους επαγγελματικού προσανατολισμού!
                    </p>

                    <div class="flex flex-col items-center">
                        <Link :href="'/session-requests/create'" class="mt-5 flex items-center justify-start text-white bg-gradient-to-r from-lime-500 via-lime-600 to-lime-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-3 py-2.5 text-center" type="submit">
                            <span class="px-2 py-1">Κλείσε το δωρεάν ραντεβού σου</span>
                        </Link>
                    </div>
                </div>

                <canvas height= "330px" id="riasecChartPlaceholder" class="mt-4" style="max-height: 330px; min-height: 330px;"></canvas>
        
                <p class="text-center text-md mb-4 mt-2">
                    Η προσωπικότητά σου χαρακτηρίζεται κυρίως από τις 3 μεγαλύτερες μπάρες που βλέπεις παρακάτω. Κάνε κλίκ στις σχετικές κατηγορίες και ανακάλυψε τι επαγγέλματα μπορείς να κάνεις.
                </p>

                <div class="flex flex-col items-center">
                    <Link :href="'/session-requests/create'" class="mt-5 flex items-center justify-start text-white bg-gradient-to-r from-lime-500 via-lime-600 to-lime-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-3 py-2.5 text-center" type="submit">
                        <span class="px-2 py-1">Κλείσε το δωρεάν ραντεβού σου</span>
                    </Link>
                </div>

                <div class="flex flex-row flex-wrap">

                    <div v-for="(type, index) in types" :key="type.index" class="bg-white mx-4 my-2">
                        <div class="rounded overflow-hidden shadow-lg">
                    
                            <div class="flex flex-col sm:flex-row p-3">
                            <div>
                                <img style="max-width: 150px;" :src="type.featureImage" alt="{{ type.title }}">
                            </div>
                            <div class="pl-3">
                                <div class="font-bold text-lg mb-2">{{ type.title }}</div>
                                <p class="text-gray-700 text-base">{{ type.description }}</p>
                    
                    
                                <a :href="type.link" target="_blank" class="mt-2 inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-lime-700 rounded-lg hover:bg-lime-800 focus:ring-4 focus:outline-none focus:ring-lime-300 dark:bg-lime-600 dark:hover:bg-lime-700 dark:focus:ring-lime-800">
                                    Δες τα επαγγέλματα
                                    <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="https://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </div>
                    
                            </div>
                        </div>
                    </div>
                  
                </div>

                <div class="flex flex-col items-center">
                    <Link :href="'/session-requests/create'" class="mt-5 flex items-center justify-start text-white bg-gradient-to-r from-lime-500 via-lime-600 to-lime-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-3 py-2.5 text-center" type="submit">
                        <span class="px-2 py-1">Κλείσε το δωρεάν ραντεβού σου</span>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    import { Inertia } from '@inertiajs/inertia';
    import { ref, watch, reactive } from 'vue';
    window.moment = require('moment');
    import NavbarPublic from '../../Common/NavbarPublic.vue';

    import Pagination from '../../Common/Pagination.vue';
    import Notification from '../../Common/Notification.vue';

    let props = defineProps({
        riasec: Object,
        response: Object
    });

    let types = [
        {
            title: "Πρακτικός Τύπος",
            description: "Κατασκευές και επιδιορθώσεις με διάφορα εργαλεία, μηχανές ή γενικότερα την φυσική άσκηση. Χρησιμοποιείται περισσότερο το σώμα παρά το πνεύμα. ",
            featureImage: "/images/holland-code/practical-holland-type.png",
            link: "https://futuregeneration.gr/holland-code-realistic-old/",
        },
        {
            title: "Ερευνητικός Τύπος",
            description: "Ενδιαφέρεται κυρίως για θέσεις εργασίας που περιλαμβάνουν θεωρία, έρευνα και επιστημονική έρευνα και είναι συνήθως διανοητικοί, περίεργοι άνθρωποι (με την καλή έννοια) που απολαμβάνουν την αναζήτηση γεγονότων και κατανόησης.",
            featureImage: "/images/holland-code/investigative-holland-type.png",
            link: "https://futuregeneration.gr/holland-code-investigative/",
        },
        {
            title: "Καλλιτεχνικός Τύπος",
            description: "Ενδιαφέρεται κυρίως για θέσεις εργασίας που περιλαμβάνουν δημιουργικότητα και αυθεντικότητα καθώς και αυτό που αποκαλούμε καλλιτεχνική έκφραση. Ως καλλιτέχνης στόχος σου είναι να μετατρέψεις αυτό που σκέφτεσαι, αυτό που φαντάζεσαι και ονειρεύεσαι σε ένα έργο..",
            featureImage: "/images/holland-code/artistic-holland-type.png",
            link: "https://futuregeneration.gr/holland-code-artistic/",
        },
        {
            title: "Κοινωνικός Τύπος",
            description: "Ενδιαφέρεται εργασίες και επαγγέλματα που έχουν ως στόχο να βοηθήσουν άλλους Ανθρώπους.",
            featureImage: "/images/holland-code/social-holland-type.png",
            link: "https://futuregeneration.gr/holland-code-social/",
        },
        {
            title: "Επιχειρηματικός Τύπος",
            description: "Ενδιαφέρεται για επαγγέλματα σχετικά με την ηγεσία. Συνήθως είναι άνθρωπος γεμάτος με ενέργεια και κυριαρχεί στον κύκλο του.",
            featureImage: "/images/holland-code/enterprising-holland-type.png",
            link: "https://futuregeneration.gr/holland-code-enterprising/",
        },
        {
            title: "Οργανωτικός Τύπος",
            description: "Ενδιαφέρεται επαγγέλματα που έχουν να κάνουν με δεδομένα και ανάλυση. Μπορεί να είναι διάφορες πληροφορίες ή αρχεία. Οι οργανωτικοί χαρακτηρίζονται από μεθοδικότητα και οργανωτικότητα. ",
            featureImage: "/images/holland-code/organizing-holland-type.png",
            link: "https://futuregeneration.gr/holland-code-conventional/",
        }
    ];
    
    function createSessionRequest() {
        router.push({ path: 'session-request/create' })
    }

    setTimeout(() => {
        const riasecChartOptions = {
            scales: {
                x: {
                    ticks: {
                        beginAtZero: true,
                        max: 50
                    }
                },
                y: {
                    // any y-axis configuration if needed
                }
            }
        };

        const riasecChartData = {
            labels: [
                'Πρακτικός',
                'Ερευνητικός',
                'Καλλιτεχνικός',
                'Κοινωνικός',
                'Επιχειρηματικός',
                'Οργανωτικός'
            ],
            datasets: [{
                label: "",
                data: Object.values(props.riasec),
                fill: true,
                tension: 0.1,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)'
                ],
            }]
        };

        var riasec = new Chart(document.getElementById('riasecChartPlaceholder'), {
            type: 'bar',
            data: riasecChartData,
            options: riasecChartOptions
        });

    }, 500);

</script>


<style>
    .resultMessage {
        color: #155724;
    }

    .resultMessage .title {
        font-size: 1.4rem;
    }


    .session-button {
        background: #155724;
        color: white;
        
        padding: 0.75rem 1.25rem;
        border-radius: 7px;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }


    @media only screen and (max-width: 640px) {
      .flex-col {
        flex-direction: column;
      }
    }
</style>

