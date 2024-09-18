<script setup>
    import TheSectionHeader from '@/Pages/Static/Landing/TheSectionHeader.vue';
    import SectionLayout from '@/Pages/Static/Landing/SectionLayout.vue';
</script>

<template>
    <SectionLayout>
        <div class="flex flex-column lg:flex-row">
            <div class="flex-1 pr-0 lg:pr-6 pb-6 lg:pb-0">
                <TheSectionHeader
                    :alignContent="'left'"
                >
                    <template #title>
                        Μιλάμε για <strong class="text-primary">ρεαλιστικά αποτελέσματα</strong>
                    </template>
                    <template #description>
                        <p>Οι επιτυχίες όσων χρησιμοποιούν το CareerExplorer, είναι η καλύτερη απόδειξη της αξίας μας. </p>
                        <p>Μέχρι σήμερα, έχει βοηθήσει <strong>πάνω από 40.000 ανθρώπους</strong> σαν εσένα να <strong>βρουν την ιδανική καριέρα</strong>, να πετύχουν τους επαγγελματικούς τους στόχους και να κάνουν πραγματικότητα τα όνειρά τους.</p>
                        <p>Οι ιστορίες μιλούν για τα αποτελέσματα που μπορείς να επιτύχεις κι εσύ.</p>
                    </template>
                </TheSectionHeader>
            </div>

            <div class="flex-1">
                <div class="surface-card shadow-2 p-4 flex flex-column align-items-center md:flex-row md:align-items-start mb-5"
                    style="border-radius: 10px;">
                    
                    <img src="/images/clean/maria.webp" class="w-4rem rounded-full">

                    <div class="ml-4 mt-4 md:mt-0">
                        <p class="mt-0 mb-3 line-height-3 text-lg sm:text-xl">Το τεστ καριέρας με βοήθησε να ανακαλύψω τις πραγματικές μου δεξιότητες και ενδιαφέροντα. Μέσα σε λίγα λεπτά, είχα μια ξεκάθαρη εικόνα των επαγγελματικών μου δυνατοτήτων. Συνιστώ την πλατφόρμα ανεπιφύλακτα.</p>
                        <div class="text-900 font-medium mb-1 text-right">Μαρία Π.</div>
                        <!-- <div class="text-600 text-sm text-right">Belton, Inc</div> -->
                    </div>
                </div>
                <div class="surface-card shadow-2 p-4 flex flex-column align-items-center md:flex-row md:align-items-start mb-5"
                    style="border-radius: 10px;">

                    <img src="/images/clean/giannis.webp" class="w-4rem rounded-full">

                    <div class="ml-4 mt-4 md:mt-0">
                        <p class="mt-0 mb-3 line-height-3 text-lg sm:text-xl">Η καθοδήγηση που έλαβα ήταν εξαιρετική! Η ομάδα με βοήθησε να βελτιώσω το βιογραφικό μου και να προετοιμαστώ για συνεντεύξεις με αυτοπεποίθηση. Ένιωσα πραγματικά ότι έχω υποστήριξη σε κάθε βήμα..</p>
                        <div class="text-900 font-medium mb-1 text-right">Γιάννης Κ.</div>
                        <!-- <div class="text-600 text-sm text-right">Belton, Inc</div> -->
                    </div>
                </div>
                <div class="surface-card shadow-2 p-4 flex flex-column align-items-center md:flex-row md:align-items-start"
                    style="border-radius: 10px;">

                    <img src="/images/clean/eleni.webp" class="w-4rem rounded-full">

                    <div class="ml-4 mt-4 md:mt-0">
                        <p class="mt-0 mb-3 line-height-3 text-lg sm:text-xl">Δεν ήξερα ποια επαγγελματική κατεύθυνση να ακολουθήσω, αλλά με το τεστ και τις συνεδρίες καθοδήγησης βρήκα επιτέλους το μονοπάτι που μου ταιριάζει. Είναι ανεκτίμητη η βοήθεια που παρέχουν</p>
                        <div class="text-900 font-medium mb-1 text-right">Ελένη Μ.</div>
                        <!-- <div class="text-600 text-sm text-right">Alfred, Inc</div> -->
                    </div>
                </div>
            </div>
        </div>
    </SectionLayout>
</template>

<style scoped>
    .rounded-full {
        border-radius: 50%;
    }
</style>