<script setup>
import { useLayout } from "@/Layouts/composables/layout";
import { computed, ref } from "vue";
import AppConfig from "@/Layouts/AppConfig.vue";
import { usePrimeVue } from "primevue/config";

import PublicMenu from "@/Pages/Static/PublicMenu.vue";
import PublicPageLayout from "@/Pages/Static/PublicPageLayout.vue";

import TheHeroSection from "@/Pages/Static/Landing/TheHeroSection.vue";
import TheBenefitsSection from "@/Pages/Static/Landing/TheBenefitsSection.vue";
import TheFaqSection from "@/Pages/Static/Landing/TheFaqSection.vue";
import TheHowToSection from "@/Pages/Static/Landing/TheHowToSection.vue";
import TheC2ASection from "@/Pages/Static/Landing/TheC2ASection.vue";
import TheTeamSection from "@/Pages/Static/Landing/TheTeamSection.vue";
import TheStatsSection from "@/Pages/Static/Landing/TheStatsSection.vue";
import TheTestimonialsSection from "@/Pages/Static/Landing/TheTestimonialsSection.vue";

const $primevue = usePrimeVue();

defineExpose({
    $primevue,
});

const isHidden = ref(false);

const toggleHidden = () => {
    isHidden.value = !isHidden.value;
};

const { layoutConfig } = useLayout();

const darkMode = computed(() => {
    return layoutConfig.colorScheme.value !== "light";
});

const smoothScroll = (id) => {
    document.querySelector(id).scrollIntoView({
        behavior: "smooth",
    });
};

const navigateAndToggle = (id) => {
    smoothScroll(id);
    toggleHidden();
};

</script>

<template>
    <PublicPageLayout>
        <PublicMenu />

        <div class="py-4 px-4 mx-0 md:mx-6 lg:mx-8 lg:px-8 z-2">
            <TheHeroSection />

            <TheBenefitsSection />

            <TheStatsSection />

            <TheC2ASection />

            <TheTestimonialsSection />

            <TheHowToSection />

            <TheC2ASection />

            <TheFaqSection />

            <!-- <TheTeamSection /> -->

            <!-- <TheTargetAudienceSection /> -->
                
            <!-- <TheFooterSection /> -->
            
            <!--------------------------------------------------------------
                <TheSuccessStoriesSection />
                
                Placement: You can place it after TheTestimonialsSection or combine it with that section.
                
                Purpose: To give more detailed case studies or success stories of how specific users or clients have benefited from your platform. This can add depth to the testimonials and show the journey someone has taken with your platform.
            -------------------------------------------------------------->
           

            <!--------------------------------------------------------------
                <TheSocialProofSection />
            
                Placement: Between TheHowToSection and TheC2ASection.
            
                Purpose: Show a typical user’s journey on the platform in a visual flow, outlining how the user goes from first engagement (test/career assessment) to achieving their career goals with your platform. It helps connect the dots for users who want to see the entire experience at a glance.
            -------------------------------------------------------------->

            <!--------------------------------------------------------------
                <TheNewsletterSection />
                
                Placement: Before or after TheC2ASection.
                
                Purpose: A section to capture email signups for newsletters or showcase highlights from your blog (if you have one). It can be a great way to keep users engaged over time.
            -------------------------------------------------------------->

            <!-------------------------------------------------------------- 
                <TheUserJourneySection />

                Placement: Between TheHowToSection and TheC2ASection.
                
                Purpose: Show a typical user’s journey on the platform in a visual flow, outlining how the user goes from first engagement (test/career assessment) to achieving their career goals with your platform. It helps connect the dots for users who want to see the entire experience at a glance.
            -------------------------------------------------------------->
        </div>
    </PublicPageLayout>

    <AppConfig simple />
</template>

<style scoped>
</style>
