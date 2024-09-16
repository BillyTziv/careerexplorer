<script setup>
    import SectionLayout from '@/Pages/Static/Landing/SectionLayout.vue';
    import { Link } from '@inertiajs/vue3';

</script>

<template>
    <SectionLayout
        :full-width="true"
    >
        <div
            class="flex flex-wrap relative overflow-hidden"
            style="border-radius: 1rem; background: radial-gradient(100% 1126.49% at 100% 0%, rgba(255, 0, 126, 0.4) 0%, rgba(1, 27, 61, 0) 100%), rgb(1, 27, 61);"
        >
            <div class="px-6 py-8">                
                <div class="text-white text-semibold mt-3 mb-5 text-4xl sm:text-5xl">
                    Είσαι έτοιμος να κάνεις το επόμενο βήμα;
                </div>

               
                <Link
                    href="tests/holland/start"
                    class="w-20rem text-xl"
                    type="button"
                >
                    <Button
                        type="button"
                        label="Κάνε το δωρεάν Τεστ"
                        icon="pi pi-arrow-right"
                        iconPos="right"
                        class="w-16rem text-xl"
                    />
                </Link>

            </div>
            <!-- <img src="/images/blocks/cta/cta-browser.png" alt="Image" class="static lg:absolute block mx-auto"
                style="height: 400px; top: -2rem; right: -1rem;"> -->
        </div>
    </SectionLayout>

    <!-- <div class="surface-section px-4 pb-8 md:px-6 lg:px-8"><div class="p-6 shadow-2 flex flex-column md:flex-row align-items-center justify-content-between" style="border-radius: 1rem; background: linear-gradient(0deg, rgba(0, 123, 255, 0.5), rgba(0, 123, 255, 0.5)), linear-gradient(92.54deg, rgb(28, 128, 207) 47.88%, rgb(255, 255, 255) 100.01%);"><div class="pr-6"><div class="text-blue-100 font-medium text-xl mb-3">TAKE THE NEXT STEP</div><div class="text-white font-medium text-5xl">Enpower your customer experience</div></div><div class="mt-4 mr-auto md:mt-0 md:mr-0"><button class="p-button p-component font-bold px-5 py-3 p-button-warning p-button-rounded p-button-raised white-space-nowrap" type="button" aria-label="Get Started" data-pc-name="button" data-pc-section="root" data-pd-ripple="true"><span class="p-button-label" data-pc-section="label">Get Started</span><span role="presentation" aria-hidden="true" data-p-ink="true" data-p-ink-active="false" class="p-ink" data-pc-name="ripple" data-pc-section="root"></span></button></div></div></div> -->
</template>

<style scoped>

</style>