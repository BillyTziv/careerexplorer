<script setup>
    import { useLayout } from '@/Layouts/composables/layout';
    import { computed, ref } from 'vue';
    import AppConfig from '@/Layouts/AppConfig.vue';
    import { usePrimeVue } from 'primevue/config';
    import { router } from '@inertiajs/vue3'

    const $primevue = usePrimeVue();

    defineExpose({
        $primevue
    });


    const isHidden = ref(false);

    const toggleHidden = () => {
        isHidden.value = !isHidden.value;
    };

    const { layoutConfig } = useLayout();

    const darkMode = computed(() => {
        return layoutConfig.colorScheme.value !== 'light';
    });

    const smoothScroll = (id) => {
        document.querySelector(id).scrollIntoView({
            behavior: 'smooth'
        });
    };

    const navigateAndToggle = (id) => {
        smoothScroll(id);
        toggleHidden();
    };
    const navigateToDashboard = () => {
        router.push('/');
    };

    function redirect( url ) {
        router.visit(url)
    }
</script>

<template>
    <div class="relative overflow-hidden flex flex-column justify-content-center">
        <div class="bg-circle opacity-50" :style="{ top: '-200px', left: '-700px' }"></div>
        <div class="bg-circle hidden lg:flex" :style="{ top: '50px', right: '-800px', transform: 'rotate(60deg)' }"></div>
        <div class="landing-wrapper">
            <div class="flex align-items-center justify-content-between relative lg:static py-6 px-4 mx-0 md:px-7 lg:px-8 lg:py-6 lg:mx-8">
                <a class="cursor-pointer" @click="navigateToDashboard">
                    <img width="120" src="/ce_logo.png" />
                </a>

                <i v-styleclass="{ selector: '@next', enterClass: 'hidden', leaveToClass: 'hidden', hideOnOutsideClick: true }" class="pi pi-bars text-4xl cursor-pointer block md:hidden text-700"></i>

                <div class="align-items-center flex-grow-1 hidden md:flex absolute md:static w-full md:px-0 z-3 shadow-2 md:shadow-none fadein" :class="{ hidden: isHidden }" :style="{ top: '80px', right: '0%' }">
                    <ul class="list-none p-3 md:p-0 m-0 ml-auto flex md:align-items-center select-none flex-column md:flex-row cursor-pointer surface-card md:surface-ground">
                        <li>
                            <a @click="navigateAndToggle('#home')" v-ripple class="p-ripple flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3">
                                <span>Home</span>
                            </a>
                        </li>
                        <li>
                            <a @click="navigateAndToggle('#apps')" v-ripple class="p-ripple flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3">
                                <span>Apps</span>
                            </a>
                        </li>
                        <li>
                            <a @click="navigateAndToggle('#pricing')" v-ripple class="p-ripple flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3">
                                <span>Pricing</span>
                            </a>
                        </li>
                        <li>
                            <a @click="navigateAndToggle('#features')" v-ripple class="p-ripple flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3">
                                <span>Features</span>
                            </a>
                        </li>

                        <li>
                            <Button @click="redirect('/login')" type="button" label="Σύνδεση" class="m-0 mt-3 md:mt-0 md:ml-5"></Button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="py-4 px-4 mx-0 md:mx-6 lg:mx-8 lg:px-8 z-2">
                <div id="home" class="grid grid-nogutter justify-content-between align-items-center mb-6 py-6 md:mb-8 md:py-8">
                    <div class="col-12 md:col-4 flex flex-column gap-4 flex-order-1 md:flex-order-0 align-items-center md:align-items-start text-center md:text-left">
                        <span class="block text-900 font-bold text-4xl">Explore new opportunities</span>
                        <span class="block text-700 text-lg">The ultimate collection of design-agnostic, flexible and accessible UI Components.</span>
                        <ul class="flex flex-wrap gap-5 list-none p-0">
                            <li class="flex align-items-center">
                                <div class="p-1 border-circle bg-green-400 inline-block mr-2"></div>
                                <span class="text-900 font-semibold">Javascript</span>
                            </li>
                            <li class="flex align-items-center">
                                <div class="p-1 border-circle bg-green-400 inline-block mr-2"></div>
                                <span class="text-900 font-semibold">TypeScript</span>
                            </li>
                            <li class="flex align-items-center">
                                <div class="p-1 border-circle bg-green-400 inline-block mr-2"></div>
                                <span class="text-900 font-semibold">Vue</span>
                            </li>
                        </ul>
                        <Button type="button" label="Ανακάλυψε" icon="pi pi-arrow-right" iconPos="right" class="w-12rem" outlined></Button>
                    </div>

                    <div class="col-12 md:col-7 flex-order-0 md:flex-order-1 mb-6 md:mb-0 border-round">
                        <img :src="`/demo/images/landing/${darkMode ? 'dashboard-dark' : 'dashboard-light'}.png`" alt="" class="w-full h-full border-round shadow-2 animation-duration-1000 fadeinright" />
                    </div>
                </div>

                <div id="apps" class="my-6 md:my-8">
                    <span class="text-900 block font-bold text-5xl mb-4 text-center">Apps</span>
                    <span class="text-700 block text-xl mb-8 text-center line-height-3">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit numquam eligendi quos.</span>

                    <div class="flex flex-column lg:flex-row align-items-center justify-content-between mt-8 gap-8">
                        <div class="flex flex-column align-items-center">
                            <img :src="`/demo/images/landing/${darkMode ? 'chat-dark' : 'chat-light'}.png`" alt="chat" class="w-full h-full border-round surface-border shadow-2 animation-duration-500 scalein origin-top" />
                            <span class="block text-900 text-lg font-semibold mt-4">Chat</span>
                        </div>
                        <div class="flex flex-column align-items-center">
                            <img :src="`/demo/images/landing/${darkMode ? 'mail-dark' : 'mail-light'}.png`" alt="chat" class="w-full h-full border-round surface-border shadow-2 animation-duration-500 scalein origin-top" />
                            <span class="block text-900 text-lg font-semibold mt-4">Mail</span>
                        </div>
                    </div>
                </div>

                <div id="pricing" class="my-6 py-6 md:my-8 md:py-8">
                    <div class="text-900 font-bold text-5xl mb-4 text-center">Pricing Plans</div>
                    <div class="text-700 text-xl mb-8 text-center line-height-3">Choose a plan that works best for you and your team.</div>

                    <div class="grid grid-nogutter justify-content-center mt-8">
                        <div class="col-12 lg:col-6 xl:col-4">
                            <div class="p-3 h-full">
                                <div class="shadow-2 p-6 h-full flex flex-column surface-card" :style="{ borderRadius: '6px' }">
                                    <span class="text-900 block font-medium text-xl mb-2 text-center">Basic Licence</span>
                                    <span class="font-bold block text-2xl text-900 text-center">$29</span>

                                    <ul class="list-none p-0 m-0 flex-grow-1 mt-6">
                                        <li class="flex align-items-center mb-3">
                                            <i class="pi pi-check text-green-500 mr-2"></i>
                                            <span>Up to 10 Active Users</span>
                                        </li>
                                        <li class="flex align-items-center mb-3">
                                            <i class="pi pi-check text-green-500 mr-2"></i>
                                            <span>Up to 30 Project Integrations</span>
                                        </li>
                                        <li class="flex align-items-center mb-3">
                                            <i class="pi pi-check text-green-500 mr-2"></i>
                                            <span>Analytics Module</span>
                                        </li>
                                        <li class="flex align-items-center mb-3">
                                            <i class="pi pi-times text-red-500 mr-2"></i>
                                            <span>Finance Module</span>
                                        </li>
                                    </ul>

                                    <Button label="Choose Plan" class="px-5 w-full mt-6" outlined icon="pi pi-arrow-right" iconPos="right"></Button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 xl:col-4">
                            <div class="p-3 h-full">
                                <div class="shadow-2 p-6 h-full flex flex-column surface-card" :style="{ borderRadius: '6px' }">
                                    <span class="text-900 block font-medium text-xl mb-2 text-center">Extended Licence</span>
                                    <span class="font-bold block text-2xl text-900 text-center">$49</span>

                                    <ul class="list-none p-0 m-0 flex-grow-1 mt-6">
                                        <li class="flex align-items-center mb-3">
                                            <i class="pi pi-check text-green-500 mr-2"></i>
                                            <span>Up to 10 Active Users</span>
                                        </li>
                                        <li class="flex align-items-center mb-3">
                                            <i class="pi pi-check text-green-500 mr-2"></i>
                                            <span>Up to 30 Project Integrations</span>
                                        </li>
                                        <li class="flex align-items-center mb-3">
                                            <i class="pi pi-check text-green-500 mr-2"></i>
                                            <span>Analytics Module</span>
                                        </li>
                                        <li class="flex align-items-center mb-3">
                                            <i class="pi pi-times text-red-500 mr-2"></i>
                                            <span>Finance Module</span>
                                        </li>
                                    </ul>

                                    <Button label="Choose Plan" class="px-5 w-full mt-6" outlined icon="pi pi-arrow-right" iconPos="right"></Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="features" class="my-6 py-6 md:my-8 md:py-8">
                    <span class="text-900 block font-bold text-5xl mb-4 text-center">Features</span>
                    <span class="text-700 block text-xl mb-8 text-center line-height-3">PrimeTek Informatics is the author of PrimeVue, a UI Component vendor with well known vastly popular projects including PrimeFaces, PrimeNG and PrimeReact.</span>

                    <div class="grid mt-8">
                        <div class="col-12 md:col-6 xl:col-3 flex justify-content-center p-3">
                            <div class="box p-4 w-full surface-card surface-border border-1 border-round">
                                <img :src="`/demo/images/landing/icon-components.svg`" alt="components icon" class="block mb-3" />
                                <span class="text-900 block font-semibold mb-3 text-lg">90+ UI Components</span>
                                <p class="m-0 text-secondary text-700">The ultimate set of UI Components to assist you with 90+ impressive Vue Components.</p>
                            </div>
                        </div>
                        <div class="col-12 md:col-6 xl:col-3 flex justify-content-center p-3">
                            <div class="box p-4 w-full surface-card surface-border border-1 border-round">
                                <img :src="`/demo/images/landing/icon-community.svg`" alt="components icon" class="block mb-3" />
                                <span class="text-900 block font-semibold mb-3 text-lg">Community</span>
                                <p class="m-0 text-secondary text-700">Connect with the other open source community members, collaborate and have a voice in the project roadmap.</p>
                            </div>
                        </div>
                        <div class="col-12 md:col-6 xl:col-3 flex justify-content-center p-3">
                            <div class="box p-4 w-full surface-card surface-border border-1 border-round">
                                <img :src="`/demo/images/landing/icon-productivity.svg`" alt="components icon" class="block mb-3" />
                                <span class="text-900 block font-semibold mb-3 text-lg">Productivity</span>
                                <p class="m-0 text-secondary text-700">Boost your productivity by achieving more in less time and accomplish amazing results.</p>
                            </div>
                        </div>
                        <div class="col-12 md:col-6 xl:col-3 flex justify-content-center p-3">
                            <div class="box p-4 w-full surface-card surface-border border-1 border-round">
                                <img :src="`/demo/images/landing/icon-accessibility.svg`" alt="components icon" class="block mb-3" />
                                <span class="text-900 block font-semibold mb-3 text-lg">Accessibility</span>
                                <p class="m-0 text-secondary text-700">The ultimate set of UI Components to assist you with 90+ impressive Vue Components.</p>
                            </div>
                        </div>
                        <div class="col-12 md:col-6 xl:col-3 flex justify-content-center p-3">
                            <div class="box p-4 w-full surface-card surface-border border-1 border-round">
                                <img :src="`/demo/images/landing/icon-support.svg`" alt="components icon" class="block mb-3" />
                                <span class="text-900 block font-semibold mb-3 text-lg">Enterprise Support</span>
                                <p class="m-0 text-secondary text-700">Exceptional support service featuring response within 1 business day and option to request enhancements and new features for the library.</p>
                            </div>
                        </div>
                        <div class="col-12 md:col-6 xl:col-3 flex justify-content-center p-3">
                            <div class="box p-4 w-full surface-card surface-border border-1 border-round">
                                <img :src="`/demo/images/landing/icon-mobile.svg`" alt="components icon" class="block mb-3" />
                                <span class="text-900 block font-semibold mb-3 text-lg">Mobile</span>
                                <p class="m-0 text-secondary text-700">First class support for responsive design led by touch optimized elements.</p>
                            </div>
                        </div>
                        <div class="col-12 md:col-6 xl:col-3 flex justify-content-center p-3">
                            <div class="box p-4 w-full surface-card surface-border border-1 border-round">
                                <img :src="`/demo/images/landing/icon-theme.svg`" alt="components icon" class="block mb-3" />
                                <span class="text-900 block font-semibold mb-3 text-lg">Themes</span>
                                <p class="m-0 text-secondary text-700">Built on a design-agnostic api, choose from a vast amount of themes such as material, bootstrap, custom or develop your own.</p>
                            </div>
                        </div>
                        <div class="col-12 md:col-6 xl:col-3 flex justify-content-center p-3">
                            <div class="box p-4 w-full surface-card surface-border border-1 border-round">
                                <img :src="`/demo/images/landing/icon-ts.svg`" alt="components icon" class="block mb-3" />
                                <span class="text-900 block font-semibold mb-3 text-lg">Typescript</span>
                                <p class="m-0 text-secondary text-700">Top-notch support for Typescript with types and tooling assistance.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid justify-content-between my-6 pt-4 md:my-8">
                    <div class="col-12 md:col-2 text-center md:text-left">
                        <a class="cursor-pointer" href="#">
                            <img width="120" src="/ce_logo.png" />
                        </a>
                    </div>

                    <div class="col-12 md:col-10 lg:col-7">
                        <div class="grid text-center md:text-left">
                            <div class="col-12 md:col-3">
                                <h4 class="font-medium text-xl line-height-3 mb-3 text-900">Company</h4>
                                <a class="line-height-3 block cursor-pointer mb-2 text-700">About Us</a>
                                <a class="line-height-3 block cursor-pointer mb-2 text-700">Εθελοντισμός</a>
                            </div>

                            <div class="col-12 md:col-3 mt-4 md:mt-0">
                                <h4 class="font-medium text-xl line-height-3 mb-3 text-900">Resources</h4>
                                <a class="line-height-3 block cursor-pointer mb-2 text-700">Get Started</a>
                                <a class="line-height-3 block cursor-pointer mb-2 text-700">Learn</a>
                                <a class="line-height-3 block cursor-pointer text-700">Case Studies</a>
                            </div>

                            <div class="col-12 md:col-3 mt-4 md:mt-0">
                                <h4 class="font-medium text-xl line-height-3 mb-3 text-900">Community</h4>
                                <a class="line-height-3 block cursor-pointer mb-2 text-700">Discord</a>
                                <a class="line-height-3 block cursor-pointer mb-2 text-700">Events</a>
                                <a class="line-height-3 block cursor-pointer mb-2 text-700">FAQ</a>
                                <a class="line-height-3 block cursor-pointer text-700">Blog</a>
                            </div>

                            <div class="col-12 md:col-3 mt-4 md:mt-0">
                                <h4 class="font-medium text-xl line-height-3 mb-3 text-900">Legal</h4>
                                <a class="line-height-3 block cursor-pointer mb-2 text-700">Brand Policy</a>
                                <a class="line-height-3 block cursor-pointer mb-2 text-700">Privacy Policy</a>
                                <a class="line-height-3 block cursor-pointer text-700">Terms of Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <AppConfig simple />
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
</style>@/Layouts/AppConfig.vue
