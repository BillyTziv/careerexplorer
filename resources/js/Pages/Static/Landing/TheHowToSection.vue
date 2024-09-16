<script setup>
    import TheSectionHeader from '@/Pages/Static/Landing/TheSectionHeader.vue';
    import SectionLayout from '@/Pages/Static/Landing/SectionLayout.vue';
</script>

<template>
    <SectionLayout>
        <TheSectionHeader>
            <template #title>
                Πως να ξεκινήσεις με <strong class="text-primary">3 απλά βήματα</strong>
            </template>
            <template #description>
                Ξεκίνα το επαγγελματικό σου ταξίδι ακολουθώντας αυτά τα 3 απλά βήματα και ανακάλυψε την καριέρα που σου ταιριάζει.
            </template>
        </TheSectionHeader>

        <div class="flex lg:justify-content-center mb-5">
            <div class="py-3 pr-8 pl-3 w-30rem hidden lg:block">
                <img src="/images/career-test-feature.png" alt="Τεστ Επαγγελματικού Προσανατολισμού" class="w-full mr-8">
            </div>

            <div class="flex flex-column align-items-center w-2rem">
                <span
                    class="bg-blue-500 text-0 flex align-items-center justify-content-center border-circle text-2xl"
                    style="min-width: 2.5rem; min-height: 2.5rem;"
                >
                    1
                </span>
                <div class="h-full bg-blue-500" style="width: 2px; min-height: 4rem;"></div>
            </div>
            <div class="py-3 lg:pl-8 pl-3 lg:w-30rem">
                <div class="text-900 mb-2 font-semibold text-xl sm:text-3xl">
                    Κάνε το δωρεάν Τεστ Επαγγελματικού Προσανατολισμού
                </div>
                <span class="block text-700 line-height-3 mb-3 text-lg sm:text-xl">
                    Το πρώτο βήμα, είναι η δημιουργία ενός προφίλ και μέσα από το δωρεάν Τεστ Επαγγελματικού Προσανατολισμού, αποτυπώνονται οι δεξιότητες, τα ενδιαφέροντα και οι προτιμήσεις σου.
                </span>

                <!-- <div class="pt-3 border-top-1 border-300">
                   <div class="mb-2 line-height-3"><span class="text-900 font-medium">Sed lectus vestibulum</span>
                        mattis ullamcorper velit. Laoreet sit amet cursus sit. </div>
                    <div class="line-height-3"><span class="text-900 font-medium">Fames ac turpis</span> egestas sed
                        tempus urna et. Cursus turpis massa. </div>
                </div> -->

               <img src="/images/career-test-feature.png" alt="Τεστ Επαγγελματικού Προσανατολισμού" class="w-full mt-3 block lg:hidden">
            </div>
        </div>
        <div class="flex justify-content-center mb-5">
            <div class="py-3 pl-5 pr-3 lg:pr-8 lg:pl-3 lg:w-30rem flex-order-1 lg:flex-order-0">
                <div class="text-900 mb-2 font-semibold text-xl sm:text-3xl">
                    Εξερεύνησε όλα τα πιθανά μονοπάτια καριέρας
                </div>

                <span class="block text-700 line-height-3 mb-3 text-lg sm:text-xl">
                    Βάση των απαντήσεών σου, θα βρείς εκατοντάδες επαγγελματικά μονοπάτια, με περιγραφές, απαραίτητες δεξιότητες, αρμοδιότητες, συνεντεύξεις και όλες τις πληροφορίες που χρειάστείς για να πάρεις την καλύτερη δυνατή απόφαση για το μέλλον σου..
                </span>
                
                <!-- <div class="pt-3 border-top-1 border-300">
                    <div class="mb-2 line-height-3"><span class="text-900 font-medium">Quis hendrerit dolor</span> magna
                        eget est. Pellentesque pulvinar pellentesque. </div>
                    <div class="line-height-3"><span class="text-900 font-medium">Lectus urna duis</span> convallis
                        convallis tellus id interdum velit laoreet. </div>
                </div> -->
                
                <img src="/images/career-search-feature.png" alt="Λίστα με Μονοπάτια Καριέρας" class="w-full mt-3 block lg:hidden">
            </div>
            <div class="flex flex-column align-items-center w-2rem flex-order-0 lg:flex-order-1"><span
                    class="bg-orange-500 text-0 flex align-items-center justify-content-center border-circle  text-2xl"
                    style="min-width: 2.5rem; min-height: 2.5rem;">2</span>
                <div class="h-full bg-orange-500" style="width: 2px; min-height: 4rem;"></div>
            </div>
            <div class="py-3 pl-8 pr-3 w-30rem hidden lg:block flex-order-2"><img
                    src="/images/career-search-feature.png" alt="Λίστα με Μονοπάτια Καριέρας" class="w-full mr-8"></div>
        </div>
        <div class="flex justify-content-center">
            <div class="py-3 pr-8 pl-3 w-30rem hidden lg:block">
                <img src="/images/session-request-feature.png" alt="Συνεδρία Επαγγελματικού Προσανατολισμού" class="w-full mr-8"></div>
            <div class="flex flex-column align-items-center w-2rem"><span
                    class="bg-green-500 text-0 flex align-items-center justify-content-center border-circle  text-2xl"
                    style="min-width: 2.5rem; min-height: 2.5rem;">3</span>
                <div class="h-full bg-green-500" style="width: 2px; min-height: 4rem;"></div>
            </div>
            <div class="py-3 lg:pl-8 pl-3 lg:w-30rem">
                <div class="text-900 mb-2 font-semibold text-xl sm:text-3xl">
                    Δωρεάν Συνεδρία Επαγγελματικού Προσανατολισμού
                </div>

                <span class="block text-700 line-height-3 mb-3 text-lg sm:text-xl">
                    Επειδή οι λίγες επιλογές, δεν είναι αρκετές και οι πολλές μπορεί να σε οδηγήσουν σε χάος, η συνεδρία επαγγελματικού προσανατολισμού είναι η καλύτερη λύση για να καταλάβεις τι είναι αυτό που σε κάνει να νιώθεις ευτυχισμένος στην καριέρα σου.
                </span>
  
                <!-- <div class="pt-3 border-top-1 border-300">
                   <div class="mb-2 line-height-3"><span class="text-900 font-medium">Egestas dui id</span> ornare arcu
                        odio ut. Mi bibendum neque egestas congue.</div>
                    <div class="line-height-3"><span class="text-900 font-medium">Sed velit dignissim</span> sodales ut
                        eu. Massa placerat duis ultricies lacus. </div>
                </div> -->

                <img src="/images/session-request-feature.png" alt="Συνεδρία Επαγγελματικού Προσανατολισμού" class="w-full mt-3 block lg:hidden"> -->
            </div>
        </div>
    </SectionLayout>
</template>

<style scoped>

</style>