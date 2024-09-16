<script setup>
    import TheSectionHeader from '@/Pages/Static/Landing/TheSectionHeader.vue';
    import SectionLayout from '@/Pages/Static/Landing/SectionLayout.vue';
    
    const benefitList = [
        {
            title: "Αποκτάς σαφήνεια και αυτοπεποίθηση στην επιλογή της καριέρας σου",
            subtitle: "-",
            description: "Κατανοείς βαθύτερα τα ενδιαφέροντα και τις δεξιότητές σου, οδηγώντας σε ενημερωμένες και σίγουρες αποφάσεις για την επαγγελματική σου πορεία.",
            icon: "pi pi-compass",
            svg: "",
            active: true
        },
        {
            title: "Εξοικονομείς χρόνο και χρήμα και προσπάθεια στην έρευνα της ιδανικής σου καριέρας",
            subtitle: "-",
            description: "Όλες τις πληροφορίες που χρειάζεσαι σε ένα μέρος, μειώνοντας τον χρόνο και την προσπάθεια που απαιτούνται για να βρεις την ιδανική καριέρα για εσένα.",
            icon: "pi pi-clock",
            svg: "",
            active: true
        },
        {
            title: "Ξεκάθαρη καθοδήγηση για το πώς να πετύχεις τους επαγγελματικούς σου στόχους.",
            subtitle: "-",
            description: "Λάβε λεπτομερείς οδηγίες και βήματα για να φτάσεις στην καριέρα που επιθυμείς, από την εκπαίδευση μέχρι την επαγγελματική ανάπτυξη.",
            icon: "pi pi-directions",
            svg: "",
            active: false
        },
        {
            title: "Κερδίζεις συνεχή εξέλιξη στην καριέρα σου με υποστήριξη σε κάθε της στάδιο",
            subtitle: "-",
            description: "Παρέχουμε συνεχή υποστήριξη και πόρους για να αναπτύξεις την καριέρα σου σε κάθε φάση, εξασφαλίζοντας την επαγγελματική σου ανάπτυξη μακροπρόθεσμα.",
            icon: "pi pi-chart-line",
            active: true
        },
        {
            title: "Προσωποποιημένη αναζήτηση καριέρας που ταιριάζει στα μοναδικά σου χαρακτηριστικά",
            subtitle: "-",
            description: "Χρησιμοποίησε τα φίλτρα αναζήτησης, για να ανακαλύψεις επαγγέλματα που ανταποκρίνονται ακριβώς στα ενδιαφέροντα, τις δεξιότητες και τις αξίες σου.",
            icon: "pi pi-search",
            active: true
        },
        {
            title: "Έμπνευση και γνώσεις από επαγγελματίες του κλάδου",
            subtitle: "-",
            description: "Μάθε από τις εμπειρίες και τις συμβουλές επαγγελματιών μέσω συνεντεύξεων, αποκτώντας πολύτιμη γνώση για τον κλάδο που σε ενδιαφέρει.",
            icon: "pi pi-comment",
            active: false
        },
        
    ];
</script>

<template>
    <SectionLayout>
        <TheSectionHeader>
            <template #title>
                Εστιάζουμε σε αυτό που έχει <strong class="text-primary">πραγματικά αξία</strong>
            </template>
            <template #description>
                Στην <span class=""><strong>προσωπική σου εξέλιξη και επιτυχία</strong></span>, μέσα από σωστή καθοδήγηση και συνεχής υποστήριξη.
            </template>
        </TheSectionHeader>

        <div class="grid">
            <template
                v-for="benefit in benefitList"
                :key="benefit.title"
            >
                <div
                    v-if="benefit.active"
                    class="col-12 lg:col-6 p-3"
                >
                    <div
                        class="shadow-2 rounded-2xl surface-card mb-3 h-full flex-column justify-content-between flex "
                        style="border-radius: 12px !important;"
                    >
                        <div class="p-4">
                            <div class="flex align-items-center justify-content-center mt-4">
                                <span class="bg-primary-100 inline-flex border-circle align-items-center justify-content-center bg-yellow-50 w-3rem h-3rem mb-4">
                                    <div class="border-circle bg-primary-50 p-4 text-primary inline-flex justify-content-center align-items-center mb-4">
                                        <i :class="`pi pi-${benefit.icon} text-5xl`"></i>
                                    </div>
                                </span>
                            </div>

                            <div class="text-900 font-semibold text-2xl text-center mb-4">
                                {{ benefit.title }}
                            </div>

                            
                            <p class="mt-0 mb-3 text-700 line-height-3 text-2xl text-center">
                                {{ benefit.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </SectionLayout>
<!--             
    <div class="px-4 py-8 md:px-6 lg:px-8"
        >
        <div class="text-blue-600 text-xl font-medium mb-3">ALL-IN-ONE</div>
        <div class="text-900 text-3xl font-bold mb-3">Focus on the work that matters</div>
        <div class="text-700">Ac turpis egestas maecenas pharetra convallis posuere morbi leo urna.</div>
        <div class="grid mt-7">
            <div class="col-12 md:col-4">
                <div class="shadow-1 p-5 surface-card text-center border-round">
                  
                    <div class="text-900 font-medium text-2xl mb-4">Simpler Interface</div>
                    <div class="text-700 mb-4 line-height-3">Duis aute irure dolor in reprehenderit in voluptate velit
                        esse cillum dolore eu fugiat nulla pariatur.</div><button
                        class="p-button p-component p-button-text font-bold" type="button" aria-label="Learn More"
                        data-pc-name="button" data-pc-section="root" data-pd-ripple="true">pan
                            class="p-button-label" data-pc-section="label">Learn More</span<span
                            role="presentation" aria-hidden="true" data-p-ink="true" data-p-ink-active="false"
                            class="p-ink" data-pc-name="ripple" data-pc-section="root"></span></button>
                </div>
            </div>
            <div class="col-12 md:col-4">
                <div class="shadow-1 p-5 surface-card text-center border-round">
                    <div
                        class="border-circle bg-purple-50 p-4 text-purple-600 inline-flex justify-content-center align-items-center mb-4">
                        <i class="pi pi-wifi text-5xl"></i></div>
                    <div class="text-900 font-medium text-2xl mb-4">Extensive Connectivity</div>
                    <div class="text-700 mb-4 line-height-3">Risus nec feugiat in fermentum posuere urna nec. Posuere
                        sollicitudin aliquam ultrices sagittis.</div><button
                        class="p-button p-component p-button-text font-bold" type="button" aria-label="Learn More"
                        data-pc-name="button" data-pc-section="root" data-pd-ripple="true">span
                            class="p-button-label" data-pc-section="label">Learn More</spanspan
                            role="presentation" aria-hidden="true" data-p-ink="true" data-p-ink-active="false"
                            class="p-ink" data-pc-name="ripple" data-pc-section="root"></span></button>
                </div>
            </div>
            <div class="col-12 md:col-4">
                <div class="shadow-1 p-5 surface-card text-center border-round">
                    <div
                        class="border-circle bg-orange-50 p-4 text-orange-600 inline-flex justify-content-center align-items-center mb-4">
                        <i class="pi pi-lock text-5xl"></i></div>
                    <div class="text-900 font-medium text-2xl mb-4">Magical Privacy</div>
                    <div class="text-700 mb-4 line-height-3">Laoreet sit amet cursus sit. Velit ut tortor pretium
                        viverra. Sollicitudin aliquam ultrices sagittis orci.</div><button
                        class="p-button p-component p-button-text font-bold" type="button" aria-label="Learn More"
                        data-pc-name="button" data-pc-section="root" data-pd-ripple="true">pan
                            class="p-button-label" data-pc-section="label">Learn More</sppan
                            role="presentation" aria-hidden="true" data-p-ink="true" data-p-ink-active="false"
                            class="p-ink" data-pc-name="ripple" data-pc-section="root"></span></button>
                </div>
            </div>
        </div>
    </div> -->
</template>

<style scoped>

</style>