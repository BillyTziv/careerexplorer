<script setup>
    import TheSectionHeader from '@/Pages/Static/Landing/TheSectionHeader.vue';
    import SectionLayout from '@/Pages/Static/Landing/SectionLayout.vue';

    // const statsList = [
    //     {
    //         title: "40,000+",
    //         subtitle: "ολοκληρωμένα τεστ",
    //         description: "",
    //         icon: "pi pi-check",
    //         active: true
    //     },
    //     {
    //         title: "250+",
    //         subtitle: "μονοπάτια καριέρας",
    //         description: "",
    //         icon: "pi pi-briefcase",
    //         active: true
    //     },
    //     {
    //         title: "4500",
    //         subtitle: "Likes",
    //         description: "",
    //         icon: "pi pi-thumbs-up",
    //         active: true
    //     },
    // ];
</script>

<template>
    <SectionLayout>
        <TheSectionHeader>
            <template #title>
                Εκτιμούμε κάθε <strong class="text-primary">σχέση εμπιστοσύνης</strong> που δημιουργείται
            </template>
            <template #description>
            </template>
        </TheSectionHeader>

        <div class="grid grid-nogutter">
            <!-- <div class="col-12 md:col-6 lg:col-3 p-3">
                <div class="bg-blue-50 text-center p-5 border-round flex flex-column"><i
                        class="text-blue-500 pi pi-play text-4xl block mb-4"></i><span
                        class="text-900 text-5xl">20+</span>
                    <div class="text-blue-700 mt-3 font-medium">συνεδρίσσες</div>
                </div>
            </div> -->

            <div class="col-12 md:col-6 lg:col-4 p-3">
                <div class="bg-purple-50 text-center p-5 border-round flex flex-column border-primary border-2"><i
                        class="text-purple-500 pi pi-check-circle text-4xl block mb-4"></i><span
                        class="text-900 text-4xl">40,000+</span>
                    <div class="text-purple-700 mt-3 font-medium text-lg">ολοκληρωμένα τεστ</div>
                </div>
            </div>
            <div class="col-12 md:col-6 lg:col-4 p-3">
                <div class="bg-teal-50 text-center p-5 border-round flex flex-column border-primary border-2"><i
                        class="text-teal-500 pi pi-briefcase text-4xl block mb-4"></i><span
                        class="text-900 text-4xl">250+</span>
                    <div class="text-teal-700 mt-3 font-medium text-lg">μονοπάτια καριέρας</div>
                </div>
            </div>
            <div class="col-12 md:col-6 lg:col-4 p-3">
                <div class="bg-indigo-50 text-center p-5 border-round flex flex-column border-primary border-2"><i
                        class="text-indigo-500 pi pi-thumbs-up text-4xl block mb-4"></i><span
                        class="text-900 text-4xl">4500</span>
                    <div class="text-indigo-700 mt-3 font-medium text-lg">Likes</div>
                </div>
            </div>
        </div>
    </SectionLayout>

    <!-- <div class="surface-section px-4 py-8 md:px-6 lg:px-8 overflow-hidden"><div class="font-bold text-900 text-3xl mb-3 text-center">Explore The Features</div><div class="text-700 text-center mb-5 line-height-3">Libero justo laoreet sit amet cursus sit amet dictum. Auctor neque vitae tempus quam pellentesque nec nam.</div><div class="flex lg:justify-content-center mb-5"><div class="py-3 pr-8 pl-3 w-30rem hidden lg:block"><img src="/images/blocks/feature/feature-timeline-1.png" alt="Image" class="w-full mr-8"></div><div class="flex flex-column align-items-center w-2rem"><span class="bg-blue-500 text-0 flex align-items-center justify-content-center border-circle" style="min-width: 2.5rem; min-height: 2.5rem;">1</span><div class="h-full bg-blue-500" style="width: 2px; min-height: 4rem;"></div></div><div class="py-3 pl-5 lg:pl-8 pl-3 lg:w-30rem"><div class="text-900 text-xl mb-2 font-medium">Ornare Arcu Odio</div><span class="block text-700 line-height-3 mb-3">Quisque sagittis purus sit amet volutpat consequat mauris nunc. Scelerisque eleifend.</span><div class="pt-3 border-top-1 border-300"><div class="mb-2 line-height-3"><span class="text-900 font-medium">Sed lectus vestibulum</span> mattis ullamcorper velit. Laoreet sit amet cursus sit. </div><div class="line-height-3"><span class="text-900 font-medium">Fames ac turpis</span> egestas sed tempus urna et. Cursus turpis massa. </div></div><img src="/images/blocks/feature/feature-timeline-1.png" alt="Image" class="w-full mt-3 block lg:hidden"></div></div><div class="flex justify-content-center mb-5"><div class="py-3 pl-5 pr-3 lg:pr-8 lg:pl-3 lg:w-30rem flex-order-1 lg:flex-order-0"><div class="text-900 text-xl mb-2 font-medium">A diam maecenas</div><span class="block text-700 line-height-3 mb-3">Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit.</span><div class="pt-3 border-top-1 border-300"><div class="mb-2 line-height-3"><span class="text-900 font-medium">Quis hendrerit dolor</span> magna eget est. Pellentesque pulvinar pellentesque. </div><div class="line-height-3"><span class="text-900 font-medium">Lectus urna duis</span> convallis convallis tellus id interdum velit laoreet. </div></div><img src="/images/blocks/feature/feature-timeline-2.png" alt="Image" class="w-full mt-3 block lg:hidden"></div><div class="flex flex-column align-items-center w-2rem flex-order-0 lg:flex-order-1"><span class="bg-yellow-500 text-0 flex align-items-center justify-content-center border-circle" style="min-width: 2.5rem; min-height: 2.5rem;">2</span><div class="h-full bg-yellow-500" style="width: 2px; min-height: 4rem;"></div></div><div class="py-3 pl-8 pr-3 w-30rem hidden lg:block flex-order-2"><img src="/images/blocks/feature/feature-timeline-2.png" alt="Image" class="w-full mr-8"></div></div><div class="flex justify-content-center"><div class="py-3 pr-8 pl-3 w-30rem hidden lg:block"><img src="/images/blocks/feature/feature-timeline-3.png" alt="Image" class="w-full mr-8"></div><div class="flex flex-column align-items-center w-2rem"><span class="bg-cyan-500 text-0 flex align-items-center justify-content-center border-circle" style="min-width: 2.5rem; min-height: 2.5rem;">3</span><div class="h-full bg-cyan-500" style="width: 2px; min-height: 4rem;"></div></div><div class="py-3 pl-5 lg:pl-8 pl-3 lg:w-30rem"><div class="text-900 text-xl mb-2 font-medium">Pharetra et ultrices neque</div><span class="block text-700 line-height-3 mb-3">Id interdum velit laoreet id. A iaculis at erat pellentesque adipiscing commodo elit at imperdiet.</span><div class="pt-3 border-top-1 border-300"><div class="mb-2 line-height-3"><span class="text-900 font-medium">Egestas dui id</span> ornare arcu odio ut. Mi bibendum neque egestas congue.</div><div class="line-height-3"><span class="text-900 font-medium">Sed velit dignissim</span> sodales ut eu. Massa placerat duis ultricies lacus. </div></div><img src="/images/blocks/feature/feature-timeline-3.png" alt="Image" class="w-full mt-3 block lg:hidden"></div></div></div> -->
</template>

<style scoped>

</style>