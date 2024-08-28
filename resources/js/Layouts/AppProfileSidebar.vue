<script setup>
    import { useLayout } from '@/Layouts/composables/layout';
    import { router } from '@inertiajs/vue3';
    import { reactive, onMounted } from 'vue';
    import { useUserStore } from '@/Stores/useUser.store';

    const { layoutState } = useLayout();
    const userStore = useUserStore();

    function logout() {
        router.post(`/logout/`);
    }
    
    function navigateTo(route) {
        router.visit(route);
    }

    const user = reactive({
        firstname: '',
        lastname: '',
        role: '',
    });

    onMounted(async() => {
        const fetchedUser = await userStore.getUser;
        user.firstname = fetchedUser?.firstName;
        user.lastname = fetchedUser?.lastName;
        user.role = fetchedUser?.role;
    });
</script>

<template>
    <Sidebar v-model:visible="layoutState.profileSidebarVisible.value" position="right" class="layout-profile-sidebar w-full sm:w-25rem">
        <div class="flex flex-column mx-auto md:mx-0">
            <span class="mb-2 font-semibold">{{ user.firstname }} {{ user.lastname }}</span>
            <span class="text-color-secondary font-medium mb-5">{{ user.role }}</span>

            <ul class="list-none m-0 p-0">
                <li>
                    <a @click="navigateTo('/profile')" class="cursor-pointer flex surface-border mb-3 p-3 align-items-center border-1 surface-border border-round hover:surface-hover transition-colors transition-duration-150">
                        <span>
                            <i class="pi pi-user text-xl text-primary"></i>
                        </span>
                        <div class="ml-3">
                            <span class="mb-2 font-semibold">Προφίλ</span>
                            <!-- <p class="text-color-secondary m-0">Διαχείριση του προσωπικού λογαριασμού</p> -->
                        </div>
                    </a>
                </li>
                <!-- <li>
                    <a class="cursor-pointer flex surface-border mb-3 p-3 align-items-center border-1 surface-border border-round hover:surface-hover transition-colors transition-duration-150">
                        <span>
                            <i class="pi pi-money-bill text-xl text-primary"></i>
                        </span>
                        <div class="ml-3">
                            <span class="mb-2 font-semibold">Billing</span>
                            <p class="text-color-secondary m-0">Amet mimin mıollit</p>
                        </div>
                    </a>
                </li> -->
                <!-- <li>
                    <a class="cursor-pointer flex surface-border mb-3 p-3 align-items-center border-1 surface-border border-round hover:surface-hover transition-colors transition-duration-150">
                        <span>
                            <i class="pi pi-cog text-xl text-primary"></i>
                        </span>
                        <div class="ml-3">
                            <span class="mb-2 font-semibold">Ρυθμίσεις</span> -->
                            <!-- <p class="text-color-secondary m-0">Exercitation veniam</p> -->
                        <!-- </div>
                    </a>
                </li> -->
                <li>
                    <a @click="logout()" class="cursor-pointer flex surface-border mb-3 p-3 align-items-center border-1 surface-border border-round hover:surface-hover transition-colors transition-duration-150">
                        <span>
                            <i class="pi pi-power-off text-xl text-primary"></i>
                        </span>
                        <div class="ml-3">
                            <span  class="mb-2 font-semibold">Αποσύνδεση</span>
                            <!-- <p class="text-color-secondary m-0">Sed ut perspiciatis</p> -->
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="flex flex-column mt-5 mx-auto md:mx-0">
            <span class="mb-2 font-semibold">Ειδοποιήσεις</span>
            <span class="text-color-secondary font-medium mb-5">Δεν έχεις καμία ειδοποίηση</span>

            <!-- <ul class="list-none m-0 p-0">
                <li>
                    <a class="cursor-pointer flex surface-border mb-3 p-3 align-items-center border-1 surface-border border-round hover:surface-hover transition-colors transition-duration-150">
                        <span>
                            <i class="pi pi-comment text-xl text-primary"></i>
                        </span>
                        <div class="ml-3">
                            <span class="mb-2 font-semibold">Your post has new comments</span>
                            <p class="text-color-secondary m-0">5 min ago</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="cursor-pointer flex surface-border mb-3 p-3 align-items-center border-1 surface-border border-round hover:surface-hover transition-colors transition-duration-150">
                        <span>
                            <i class="pi pi-trash text-xl text-primary"></i>
                        </span>
                        <div class="ml-3">
                            <span class="mb-2 font-semibold">Your post has been deleted</span>
                            <p class="text-color-secondary m-0">15min ago</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="cursor-pointer flex surface-border mb-3 p-3 align-items-center border-1 surface-border border-round hover:surface-hover transition-colors transition-duration-150">
                        <span>
                            <i class="pi pi-folder text-xl text-primary"></i>
                        </span>
                        <div class="ml-3">
                            <span class="mb-2 font-semibold">Post has been updated</span>
                            <p class="text-color-secondary m-0">3h ago</p>
                        </div>
                    </a>
                </li>
            </ul> -->
        </div>

        <div class="flex flex-column mt-5 mx-auto md:mx-0">
            <span class="mb-2 font-semibold">Μηνύματα</span>
            <span class="text-color-secondary font-medium mb-5">Δεν έχεις κανένα νέο μήνυμα</span>

            <!-- <ul class="list-none m-0 p-0">
                <li>
                    <a class="cursor-pointer flex surface-border mb-3 p-3 align-items-center border-1 surface-border border-round hover:surface-hover transition-colors transition-duration-150">
                        <span> <img src="/demo/images/avatar/circle/avatar-m-8.png" alt="Avatar" class="w-2rem h-2rem" /> </span>
                        <div class="ml-3">
                            <span class="mb-2 font-semibold">James Robinson</span>
                            <p class="text-color-secondary m-0">10 min ago</p>
                        </div>
                        <Badge value="3" class="ml-auto"></Badge>
                    </a>
                </li>
                <li>
                    <a class="cursor-pointer flex surface-border mb-3 p-3 align-items-center border-1 surface-border border-round hover:surface-hover transition-colors transition-duration-150">
                        <span> <img src="/demo/images/avatar/circle/avatar-f-8.png" alt="Avatar" class="w-2rem h-2rem" /> </span>
                        <div class="ml-3">
                            <span class="mb-2 font-semibold">Mary Watson</span>
                            <p class="text-color-secondary m-0">15min ago</p>
                        </div>
                        <Badge value="1" class="ml-auto"></Badge>
                    </a>
                </li>
                <li>
                    <a class="cursor-pointer flex surface-border mb-3 p-3 align-items-center border-1 surface-border border-round hover:surface-hover transition-colors transition-duration-150">
                        <span> <img src="/demo/images/avatar/circle/avatar-f-4.png" alt="Avatar" class="w-2rem h-2rem" /> </span>
                        <div class="ml-3">
                            <span class="mb-2 font-semibold">Aisha Webb</span>
                            <p class="text-color-secondary m-0">3h ago</p>
                        </div>
                        <Badge value="2" class="ml-auto"></Badge>
                    </a>
                </li>
            </ul> -->
        </div>
    </Sidebar>
</template>
