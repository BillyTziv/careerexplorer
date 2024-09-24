<script setup>
    import { computed, ref } from 'vue';
    import BaseCheckList from '@/Components/Base/BaseCheckList.vue';
    import BaseDataView from '@/Components/Base/BaseDataView.vue';

    import PublicPageLayout from '@/Pages/Static/PublicPageLayout.vue';
    import PublicMenu from '@/Pages/Static/PublicMenu.vue';

    import { router } from '@inertiajs/vue3'
    
    let props = defineProps({
        career: {
            type: Object,
            required: true
        }
    });

    const hasCourses = computed(() => props.career.courses?.length > 0);
    const hasResponsibilities = computed(() => props.career.responsibilities?.length > 0);
    const hasInterests = computed(() => props.career.interests?.length > 0);
    const hasSkills = computed(() => props.career.skills?.length > 0);

    const hollandCodes = computed(() => props.career.riasec_codes.map((code) => code.symbol));

    const getHollandCodeColor = ( hollandCode ) => {
    switch ( hollandCode ) {
        case 'R':
            return '#7F1D1D';

        case 'I':
            return '#15803D';

        case 'A':
            return '#EC4899';

        case 'S':
            return '#FACC15';

        case 'E':
            return '#F97316';

        case 'C':
            return '#6B7280'; 

        default:
            return '#323232';
    }
    };


    const careerSkills = computed(() => {
        return props.career.skills.map(skill => {
            return {
                id: skill.id,
                text: skill.name
            };
        });
    });

    const careerInterests = computed(() => {
        return props.career.interests.map(interest => {
            return {
                id: interest.id,
                text: interest.name
            };
        });
    });


    function goBack() {
        window.history.back();
    }
</script>

<template>
    <PublicPageLayout>
        <PublicMenu />

        <div class="py-4 px-4 mx-0 md:mx-6 lg:mx-8 lg:px-8 z-2">
            <div id="personality-summary" class="mb-6 pb-6 md:my-8 md:py-8">
                <!-- <span class="text-900 block font-bold text-5xl mb-4 text-center">
                    Ανακάλυψε πως είναι να είσαι..
                </span> -->

                <Button
                    @click="goBack()"
                    label="Πίσω" 
                    icon="pi pi-arrow-left"
                    type="button"
                    iconPos="left"
                    class="m-0 my-3 p-2"
                    text
                />
                <div class="mb-0 flex flex-column md:flex-row md:align-items-center md:justify-content-between">
                    <div class="flex align-items-start">
                        <!-- <img src="/images/blocks/logos/hyper.svg" alt="Image" height="40" class="mr-3"> -->
                        <div>
                            <div class="mb-2">
                                <div>
                                    <template v-for="(code, index) in hollandCodes" :key="index">
                                        <Tag :value="''" :style="{ 
                                                backgroundColor: getHollandCodeColor(code), 
                                                width: '10px', 
                                                height: '11px', 
                                                margin: '1px' 
                                            }" :rounded="true"></Tag>
                                    </template>
                                </div>

                                <span class="text-900 block font-bold text-3xl mb-4">
                                    {{ career.title }}
                                </span>
                            </div>
                            <!-- <div class="font-medium text-500 mb-3 text-sm">
                                <pre>{{ career.category }}</pre>
                            </div> -->
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <!-- <button class="p-button p-component p-button-icon-only p-button-rounded p-button-text"
                            type="button" data-pc-name="button" data-pc-section="root" data-pd-ripple="true"><span
                                class="p-button-icon pi pi-heart" data-pc-section="icon"></span><span class="p-button-label"
                                data-pc-section="label">&nbsp;</span><span role="presentation" aria-hidden="true"
                                data-p-ink="true" data-p-ink-active="false" class="p-ink" data-pc-name="ripple"
                                data-pc-section="root"></span>
                        </button>

                        <button class="p-button p-component p-button-icon-only p-button-rounded p-button-text"
                            type="button" data-pc-name="button" data-pc-section="root" data-pd-ripple="true"><span
                                class="p-button-icon pi pi-share-alt" data-pc-section="icon"></span><span
                                class="p-button-label" data-pc-section="label">&nbsp;</span><span role="presentation"
                                aria-hidden="true" data-p-ink="true" data-p-ink-active="false" class="p-ink"
                                data-pc-name="ripple" data-pc-section="root"></span>
                        </button> -->
                    </div>
                </div>

                <div class="grid">
                    
                    <div class="col-12 lg:col">
                        
                        <div class="line-height-3 text-xl text-900 mb-5">
                            <div>
                                <p class="text-lg">
                                    {{ career.description }}
                                </p>

                                <!---------------------------------------------------------------------------------->
                                <!-- CAREER SKILLS -->
                                <!---------------------------------------------------------------------------------->
                                <template v-if="hasSkills">
                                    <BaseCheckList
                                        :items="careerSkills"
                                        title="Δεξιότητες / Προσόντα"
                                    />
                                </template>

                                <!---------------------------------------------------------------------------------->
                                <!-- CAREER RESPONSIBILITIES -->
                                <!---------------------------------------------------------------------------------->
                                <template v-if="hasResponsibilities">
                                    <BaseCheckList
                                        :items="career.responsibilities"
                                        title="Αρμοδιότητες / Καθήκοντα"
                                    />
                                </template>

                                <!---------------------------------------------------------------------------------->
                                <!-- CAREER INTERESTS -->
                                <!---------------------------------------------------------------------------------->
                                <template v-if="hasInterests">
                                    <BaseCheckList
                                        :items="careerInterests"
                                        title="Ενδιαφέροντα"
                                    />
                                </template>

                                <!---------------------------------------------------------------------------------->
                                <!-- CAREER COURSES -->
                                <!---------------------------------------------------------------------------------->
                                <template v-if="hasCourses">
                                    <BaseDataView
                                        title="Μαθήματα"
                                        :items="career.courses" 
                                        paginator 
                                        :rows="3" 
                                        layout="grid"
                                    />
                                </template>

                            </div>

                            <!-- <div class="flex align-items-center cursor-pointer">
                                <span class="font-bold mr-3"></span>
                                <i class="pi pi-arrow-right"></i>
                            </div> -->
                        </div>
                    </div>
                    <!-- <div class="col-12 lg:col-3 lg:border-left-1 surface-border">
                        <div class="p-2">
                            <h2 class="text-sm font-bold">TOP 5 Careers</h2>


                           <div class="flex border-bottom-1 surface-border pb-4 mb-5"><img
                                    src="/images/blocks/avatars/circle/avatar-f-1.png" class="mr-3 h-5rem w-5rem">
                                <div class="flex flex-column align-items-start"><span
                                        class="text-lg text-900 font-medium mb-1">Jessica Doe</span><span
                                        class="text-600 font-medium mb-2">1.7K Followers</span><button
                                        class="p-button p-component p-button-rounded p-button-primary" type="button"
                                        aria-label="Follow" data-pc-name="button" data-pc-section="root"
                                        data-pd-ripple="true">-><span class="p-button-label"
                                            data-pc-section="label">Follow</span><span role="presentation"
                                            aria-hidden="true" data-p-ink="true" data-p-ink-active="false" class="p-ink"
                                            data-pc-name="ripple" data-pc-section="root"></span></button></div>
                            </div><span class="text-900 font-medium text-xl block mb-5">Other Posts</span>
                            <div class="flex pb-4"><img src="/images/blocks/content/content-5.png" class="mr-3 h-5rem w-5rem">
                                <div class="flex flex-column align-items-start"><span
                                        class="text-lg text-900 font-medium mb-1">Post Title</span>

                                </div>
                            </div>

                            <div class="flex pb-4"><img src="/images/blocks/content/content-6.png" class="mr-3 h-5rem w-5rem">
                                <div class="flex flex-column align-items-start"><span
                                        class="text-lg text-900 font-medium mb-1">Post Title</span><span
                                        class="text-600 mb-2">Metus aliquam eleifend mi in nulla posuere.</span></div>
                            </div>
                            <div class="flex pb-4"><img src="/images/blocks/content/content-7.png" class="mr-3 h-5rem w-5rem">
                                <div class="flex flex-column align-items-start"><span
                                        class="text-lg text-900 font-medium mb-1">Post Title</span><span
                                        class="text-600 mb-2">Metus aliquam eleifend mi in nulla posuere.</span></div>
                            </div>
                            <div class="flex flex-column gap-3">
                                <div class="w-full h-full p-5 border-round"
                                    style="background: linear-gradient(rgba(0, 0, 0, 0.4) 0%, rgb(0, 0, 0) 100%), url(&quot;/images/blocks/content/content-8.png&quot;);">
                                    <span class="text-white font-medium mb-3">Post Title</span><span
                                        class="text-white-alpha-70 block line-height-3">Metus aliquam eleifend mi innulla
                                        posuere.</span></div>
                                <div class="w-full h-full p-5 border-round"
                                    style="background: linear-gradient(rgba(0, 0, 0, 0.4) 0%, rgb(0, 0, 0) 100%), url(&quot;/images/blocks/content/content-9.png&quot;);">
                                    <span class="text-white font-medium mb-3">Post Title</span><span
                                        class="text-white-alpha-70 block line-height-3">Metus aliquam eleifend mi innulla
                                        posuere.</span></div>
                                <div class="w-full h-full p-5 border-round"
                                    style="background: linear-gradient(rgba(0, 0, 0, 0.4) 0%, rgb(0, 0, 0) 100%), url(&quot;/images/blocks/content/content-10.png&quot;);">
                                    <span class="text-white font-medium mb-3">Post Title</span><span
                                        class="text-white-alpha-70 block line-height-3">Metus aliquam eleifend mi in nulla
                                        posuere.</span></div>
                            </div>
                        </div>
                    </div> -->
                </div>

            </div>
        </div>
    </PublicPageLayout>
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

    .p-dataview >>> .p-dataview-content {
        background: transparent;
    }
</style>