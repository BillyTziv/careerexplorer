<template>
    <div class="layout-content-wrapper">
        <div class="layout-content">
            <div class="surface-card p-4 shadow-2 border-round">
                <div class="mb-3">
                    <div class="mb-3 flex flex-column md:flex-row md:align-items-center md:justify-content-between">
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

                                    <span class="text-2xl font-bold text-900">
                                        {{ career.title }}
                                    </span>
                                </div>
                                <!-- <div class="font-medium text-500 mb-3 text-sm">
                                    <pre>{{ career.category }}</pre>
                                </div> -->
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button class="p-button p-component p-button-icon-only p-button-rounded p-button-text"
                                type="button" data-pc-name="button" data-pc-section="root" data-pd-ripple="true"><span
                                    class="p-button-icon pi pi-heart" data-pc-section="icon"></span><span class="p-button-label"
                                    data-pc-section="label">&nbsp;</span><!----><span role="presentation" aria-hidden="true"
                                    data-p-ink="true" data-p-ink-active="false" class="p-ink" data-pc-name="ripple"
                                    data-pc-section="root"></span>
                            </button>

                            <button class="p-button p-component p-button-icon-only p-button-rounded p-button-text"
                                type="button" data-pc-name="button" data-pc-section="root" data-pd-ripple="true"><span
                                    class="p-button-icon pi pi-share-alt" data-pc-section="icon"></span><span
                                    class="p-button-label" data-pc-section="label">&nbsp;</span><!----><span role="presentation"
                                    aria-hidden="true" data-p-ink="true" data-p-ink-active="false" class="p-ink"
                                    data-pc-name="ripple" data-pc-section="root"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid">
                   
                    <div class="col-12 lg:col">
                       
                        <div class="line-height-3 text-xl text-900 mb-5">
                            <div>
                                <p class="text-sm">
                                    {{ career.description }}
                                </p>

                                <template v-if="hasSkills">
                                    <h2 class="text-lg font-light">Δεξιότητες / Προσόντα</h2>
                                    <ul class="text-sm">
                                        <li v-for="(skill, index) in career.skills" :key="index">
                                            {{ skill.name }}
                                        </li>
                                    </ul>
                                </template>

                                <template v-if="hasResponsibilities">
                                    <h2 class="text-lg font-light">Αρμοδιότητες / Καθήκοντα</h2>
                                    <ul class="text-sm">
                                        <li v-for="(respo, index) in career.responsibilities" :key="index">
                                            {{ respo.text }}
                                        </li>
                                    </ul>
                                </template>

                                <template v-if="hasInterests">
                                    <h2 class="text-lg font-light">Ενδιαφέροντα</h2>
                                    <ul class="text-sm">
                                        <li v-for="(interest, index) in career.interests" :key="index">
                                            {{ interest.name }}
                                        </li>
                                    </ul>
                                </template>

                                <template v-if="hasCourses">
                                    <h2 class="text-lg font-light">Μαθήματα</h2>

                                    <div class="text-900 w-full md:w-10">
                                        <div class="grid mt-0 mr-0">
                                            <div v-for="(course, index) in career.courses" :key="index" class="col-12 md:col-6">
                                                <div class="p-3 border-1 surface-border border-round surface-card">
                                                    <div class="text-900 mb-2">
                                                        <!-- <i class="pi pi-github mr-2"></i> -->
                                                        <span class="font-bold text-sm">{{ course.title }}</span>
                                                    </div>

                                                    <div class="text-700 text-sm">{{ course.description }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                            </div>

                            <!-- <div class="flex align-items-center cursor-pointer">
                                <span class="font-bold mr-3"></span>
                                <i class="pi pi-arrow-right"></i>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-12 lg:col-3 lg:border-left-1 surface-border">
                        <div class="p-2">
                            <h2 class="text-sm font-bold">TOP 5 Careers</h2>


                            <!-- <div class="flex border-bottom-1 surface-border pb-4 mb-5"><img
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
                            </div> -->
                        </div>
                    </div>
                </div>


            
                <div class="border-2 border-dashed surface-border border-round mt-3 p-4">

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';

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
</script>