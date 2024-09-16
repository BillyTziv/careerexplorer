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
                    
                    <img src="/demo/images/avatar/profile.jpg" class="w-4rem rounded-full">

                    <div class="ml-4 mt-4 md:mt-0">
                        <p class="mt-0 mb-3 line-height-3 text-lg sm:text-xl">No matter where you go, Blocks is the coolest, most happening
                            thing around! We can't understand how we've been living without Blocks.</p>
                        <div class="text-900 font-medium mb-1 text-right">Jane Cooper</div>
                        <div class="text-600 text-sm text-right">Belton, Inc</div>
                    </div>
                </div>
                <div class="surface-card shadow-2 p-4 flex flex-column align-items-center md:flex-row md:align-items-start mb-5"
                    style="border-radius: 10px;">

                    <img src="/demo/images/avatar/profile.jpg" class="w-4rem rounded-full">

                    <div class="ml-4 mt-4 md:mt-0">
                        <p class="mt-0 mb-3 line-height-3 text-lg sm:text-xl">We can't understand how we've been living without Blocks.
                            Blocks impressed me on multiple levels. It's really wonderful.</p>
                        <div class="text-900 font-medium mb-1 text-right">Floyd Miles</div>
                        <div class="text-600 text-sm text-right">Belton, Inc</div>
                    </div>
                </div>
                <div class="surface-card shadow-2 p-4 flex flex-column align-items-center md:flex-row md:align-items-start"
                    style="border-radius: 10px;">

                    <img src="/demo/images/avatar/profile.jpg" class="w-4rem rounded-full">

                    <div class="ml-4 mt-4 md:mt-0">
                        <p class="mt-0 mb-3 line-height-3 text-lg sm:text-xl">Just what I was looking for. I would like to personally thank
                            you for your outstanding product.</p>
                        <div class="text-900 font-medium mb-1 text-right">Leslie Alexander</div>
                        <div class="text-600 text-sm text-right">Alfred, Inc</div>
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