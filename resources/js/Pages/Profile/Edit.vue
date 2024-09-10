<script setup>
    import { computed, } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    
    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import AppFormLayout from '@/Layouts/AppFormLayout.vue';

    /* Components */
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';
    import BaseNumberInput from '@/Components/Base/BaseNumberInput.vue';
    import BaseEmailInput from '@/Components/Base/BaseEmailInput.vue';
    
    let props = defineProps({
        auth: Object,
        userData: Object,
        errors: Object,
    });
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            <span>Το Προφίλ μου</span>
        </template>

        <template #page-content>
            <h1>Under Construction</h1>
            <!-- <div class="p-fluid flex flex-column lg:flex-row">
                <ul
                    class="list-none m-0 p-0 flex flex-row lg:flex-column justify-content-between lg:justify-content-start mb-5 lg:mb-0">
                    <li><a class="p-ripple lg:w-15rem flex align-items-center cursor-pointer p-3 border-round hover:surface-200 transition-duration-150 transition-colors"
                            data-pd-ripple="true"><i class="pi pi-user md:mr-2 text-600"></i><span
                                class="font-medium hidden md:block text-700">Profile</span><span role="presentation"
                                aria-hidden="true" data-p-ink="true" data-p-ink-active="false" class="p-ink"
                                data-pc-name="ripple" data-pc-section="root"></span></a></li>
                    <li><a class="p-ripple lg:w-15rem flex align-items-center cursor-pointer p-3 border-round hover:surface-200 transition-duration-150 transition-colors surface-200"
                            data-pd-ripple="true"><i class="pi pi-cog md:mr-2 text-700"></i><span
                                class="font-medium hidden md:block text-800">Account</span><span role="presentation"
                                aria-hidden="true" data-p-ink="true" data-p-ink-active="false" class="p-ink"
                                data-pc-name="ripple" data-pc-section="root"
                                style="height: 300px; width: 300px; top: -110px; left: -2.5px;"></span></a></li>
                    <li><a class="p-ripple lg:w-15rem flex align-items-center cursor-pointer p-3 border-round hover:surface-200 transition-duration-150 transition-colors"
                            data-pd-ripple="true"><i class="pi pi-palette md:mr-2 text-600"></i><span
                                class="font-medium hidden md:block text-700">Appearance</span><span role="presentation"
                                aria-hidden="true" data-p-ink="true" data-p-ink-active="false" class="p-ink"
                                data-pc-name="ripple" data-pc-section="root"
                                style="height: 300px; width: 300px; top: -111px; left: 2.5px;"></span></a></li>
                    <li><a class="p-ripple lg:w-15rem flex align-items-center cursor-pointer p-3 border-round hover:surface-200 transition-duration-150 transition-colors"
                            data-pd-ripple="true"><i class="pi pi-sun md:mr-2 text-600"></i><span
                                class="font-medium hidden md:block text-700">Accessibility</span><span
                                role="presentation" aria-hidden="true" data-p-ink="true" data-p-ink-active="false"
                                class="p-ink" data-pc-name="ripple" data-pc-section="root"></span></a></li>
                    <li><a class="p-ripple lg:w-15rem flex align-items-center cursor-pointer p-3 border-round hover:surface-200 transition-duration-150 transition-colors"
                            data-pd-ripple="true"><i class="pi pi-bell md:mr-2 text-600"></i><span
                                class="font-medium hidden md:block text-700">Notifications</span><span
                                role="presentation" aria-hidden="true" data-p-ink="true" data-p-ink-active="false"
                                class="p-ink" data-pc-name="ripple" data-pc-section="root"></span></a></li>
                </ul>
                <div class="surface-card p-5 shadow-2 border-round flex-auto xl:ml-5">
                    <div class="text-900 font-semibold text-lg mt-3">Profile</div>
                    <div class="p-divider p-component p-divider-horizontal p-divider-solid p-divider-left"
                        role="separator" aria-orientation="horizontal" data-pc-name="divider" data-pc-section="root"
                        style="justify-content: center;"></div>
                    <div class="flex gap-5 flex-column-reverse md:flex-row">
                        <div class="flex-auto p-fluid">
                            <div class="mb-4"><label for="email"
                                    class="block font-normal text-900 mb-2">Name</label><input
                                    class="p-inputtext p-component" data-pc-name="inputtext" data-pc-section="root"
                                    id="email" type="text"></div>
                            <div class="mb-4"><label for="bio"
                                    class="block font-normal text-900 mb-2">Bio</label><textarea
                                    class="p-inputtextarea p-inputtext p-component p-inputtextarea-resizable"
                                    data-pc-name="textarea" data-pc-section="root" id="bio" type="text" rows="5"
                                    style="height: 150px; overflow: hidden;"></textarea>
                                <p class="mt-2 mb-0 font-medium text-base text-600"> You can <span
                                        class="font-medium text-900">@mention</span> other users and organizations to
                                    link to them. </p>
                            </div>
                            <div class="mb-4"><label for="website" class="block font-normal text-900 mb-2">URL</label>
                                <div class="p-inputgroup"><span class="p-inputgroup-addon">https://</span><input
                                        class="p-inputtext p-component" data-pc-name="inputtext" data-pc-section="root"
                                        id="website" type="text"></div>
                            </div>
                            <div class="mb-4"><label for="company"
                                    class="block font-normal text-900 mb-2">Company</label><input
                                    class="p-inputtext p-component" data-pc-name="inputtext" data-pc-section="root"
                                    id="company" type="text"></div>
                            <div class="mb-4"><label for="visibility" class="block font-normal text-900 mb-2">Profile
                                    Visibility</label>
                                <div class="flex align-items-center">
                                    <div class="p-checkbox p-component" data-pc-name="checkbox" data-pc-section="root">
                                        <div class="p-hidden-accessible" data-pc-section="hiddeninputwrapper"
                                            data-p-hidden-accessible="true"><input id="visibility" type="checkbox"
                                                data-pc-section="hiddeninput"></div>
                                        <div class="p-checkbox-box" data-pc-section="input" data-p-highlight="false"
                                            data-p-disabled="false" data-p-focused="false"></div>
                                    </div><span class="ml-2 font-normal text-base text-color-primary">Make profile
                                        private and hide all activity</span>
                                </div>
                            </div>
                            <div><button class="p-button p-component w-auto mt-3" type="button"
                                    aria-label="Update Profile" data-pc-name="button" data-pc-section="root"
                                    data-pd-ripple="true"><span class="p-button-label"
                                        data-pc-section="label">Update Profile</span><span role="presentation"
                                        aria-hidden="true" data-p-ink="true" data-p-ink-active="false" class="p-ink"
                                        data-pc-name="ripple" data-pc-section="root"></span></button></div>
                        </div>
                        <div class="flex flex-column align-items-center flex-or"><span
                                class="font-normal text-900 mb-2">Profile Picture</span><img
                                src="/images/blocks/avatars/circle-big/avatar-f-2.png" class="h-10rem w-10rem"><button
                                class="p-button p-component p-button-icon-only p-button-rounded -mt-4" type="button"
                                data-pc-name="button" data-pc-section="root" data-pd-ripple="true"><span
                                    class="p-button-icon pi pi-pencil" data-pc-section="icon"></span><span
                                    class="p-button-label" data-pc-section="label">&nbsp;</span><span
                                    role="presentation" aria-hidden="true" data-p-ink="true" data-p-ink-active="false"
                                    class="p-ink" data-pc-name="ripple" data-pc-section="root"></span></button></div>
                    </div>
                </div>
            </div>-->
        </template> 
    </AppPageWrapper>
</template>