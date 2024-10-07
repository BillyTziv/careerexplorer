<script setup>
import { computed, ref } from 'vue';
import BaseCheckList from '@/Components/Base/BaseCheckList.vue';
import BaseDataView from '@/Components/Base/BaseDataView.vue';
import BaseHeadingText from '@/Components/Base/BaseHeadingText.vue';
import BaseDivider from '@/Components/Base/BaseDivider.vue';

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

const getHollandCodeColor = (hollandCode) => {
    switch (hollandCode) {
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

const topCareers = [
    {
        title: 'Προγραμματιστής'
    },
    {
        title: 'Δικηγόρος'
    },
    {
        title: 'Γιατρός'
    },
    {
        title: 'Μηχανικός'
    },
    {
        title: 'Δάσκαλος'
    }
];

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

                <Button @click="goBack()" label="Πίσω" icon="pi pi-arrow-left" type="button" iconPos="left"
                    class="m-0 my-3 p-2" outlined />

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

                    <div class="col-12 lg:col-9">

                        <div class="line-height-3 text-xl text-900 mb-5">
                            <div>
                                <p class="text-lg" style="white-space: pre-wrap;">
                                    {{ career.description }}
                                </p>

                                <!---------------------------------------------------------------------------------->
                                <!-- CAREER SKILLS -->
                                <!---------------------------------------------------------------------------------->
                                <template v-if="hasSkills">
                                    <BaseHeadingText title="Δεξιότητες / Προσόντα" />
                                    <BaseDivider />

                                    <BaseCheckList :items="careerSkills" />
                                </template>

                                <!---------------------------------------------------------------------------------->
                                <!-- CAREER RESPONSIBILITIES -->
                                <!---------------------------------------------------------------------------------->
                                <template v-if="hasResponsibilities">
                                    <BaseHeadingText title="Αρμοδιότητες / Καθήκοντα" />
                                    <BaseDivider />

                                    <BaseCheckList :items="career.responsibilities" />
                                </template>

                                <!---------------------------------------------------------------------------------->
                                <!-- CAREER INTERESTS -->
                                <!---------------------------------------------------------------------------------->
                                <template v-if="hasInterests">
                                    <BaseHeadingText title="Ενδιαφέροντα" />
                                    <BaseDivider />

                                    <BaseCheckList :items="careerInterests" />
                                </template>

                                <!---------------------------------------------------------------------------------->
                                <!-- CAREER COURSES -->
                                <!---------------------------------------------------------------------------------->
                                <template v-if="hasCourses">
                                    <BaseHeadingText title="Μαθήματα" />
                                    <BaseDivider />

                                    <BaseDataView :items="career.courses" paginator :rows="3" layout="grid" />
                                </template>

                            </div>

                        </div>
                    </div>
                    <div class="col-12 lg:col-3 lg:border-left-1 surface-border">
                        <div class="p-2">
                            <h3 class="text-2xl font-semibold">
                                TOP 5 Καριέρες
                            </h3>
                            <div class="flex flex-column gap-5">
                                <template v-for="career in topCareers" :key="career">
                                    <div class="w-full h-full p-3 border-round"
                                        style="background: #E5E8EB; color: #011b3d; border-bottom: solid 4px #011b3d;">
                                        <span class="text-xl font-light">
                                            {{ career.title }}
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <Button @click="goBack()" label="Πίσω" icon="pi pi-arrow-left" type="button" iconPos="left"
                    class="m-0 my-3 p-2" outlined />
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
</style>