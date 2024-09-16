<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3'

    const isHidden = ref(false);

    function redirect( url ) {
        router.visit(url)
    }
</script>

<template>
    <div class="flex align-items-center justify-content-between relative lg:static py-6 px-4 mx-0 md:px-7 lg:px-8 lg:py-6 lg:mx-8">
        <a class="cursor-pointer" @click="redirect('/')">
            <img width="120" src="/ce_logo.png" />
        </a>

        <i v-styleclass="{ selector: '@next', enterClass: 'hidden', leaveToClass: 'hidden', hideOnOutsideClick: true }" class="pi pi-bars text-4xl cursor-pointer block md:hidden text-700"></i>

        <div class="align-items-center flex-grow-1 hidden md:flex absolute md:static w-full md:px-0 z-3 shadow-2 md:shadow-none fadein" :class="{ hidden: isHidden }" :style="{ top: '80px', right: '0%' }">
            <ul class="list-none p-3 md:p-0 m-0 ml-auto flex md:align-items-center select-none flex-column md:flex-row cursor-pointer surface-card md:surface-ground">
                <li>
                    <a @click="redirect('/')" v-ripple class="p-ripple flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 text-xl">
                        <span>Αρχική</span>
                    </a>
                </li>
                <!-- <li>
                    <a @click="navigateAndToggle('#apps')" v-ripple class="p-ripple flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3">
                        <span>Apps</span>
                    </a>
                </li> -->
                <li>
                    <a @click="redirect('/explore')" v-ripple class="p-ripple flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 text-xl">
                        <span>Ανακάλυψε Καριέρες</span>
                    </a>
                </li>
                <li>
                    <a @click="redirect('/tests/holland/start')" v-ripple class="p-ripple flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 text-xl">
                        <span>Τεστ Επαγγελματικού Προσανατολισμού</span>
                    </a>
                </li>
                <li>
                    <Button @click="redirect('/subscribe')" type="button" label="Εγγραφή" class="m-0 mt-3 md:mt-0 md:ml-5 text-xl"></Button>
                </li>
            </ul>
        </div>
    </div>
</template>