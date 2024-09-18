<script setup>
    import { ref } from "vue";
    import { router } from "@inertiajs/vue3";

    const navigateTo = (path) => {
        router.visit(path);
    };

    const ratingValue = ref(4.2);
</script>

<template>
    <div
        class="grid grid-nogutter justify-content-between align-items-center mb-6 py-6 md:mb-8 md:py-8"
    >
        <div
            class="col-12 md:col-6 flex flex-column gap-4 flex-order-1 md:flex-order-0 align-items-center md:align-items-start text-center md:text-left"
        >
            <!-- Kicker -->
            <div class="flex flex-column items-center sm:items-start">
                <div>
                    <Rating
                        v-model="ratingValue"
                        readonly
                        :cancel="false"
                        class="mb-1"
                    />
                </div>
                <span class="font-medium text-lg">
                    <strong>45.000+</strong> ολοκληρωμένα τεστ
                </span>
            </div>

            <!-- Super Clear -->
            <span class="block text-900 font-bold text-4xl sm:text-5xl md:text-5xl lg:text-6xl xl:text-6xl">
                <!-- Ανακάλυψε όλες τις δυνατότητές σου -->
                <!-- Explore more career paths than you think. -->
                <!-- Explore unique career paths. -->
                <span class="highlight-word">Explore</span> your future.
            </span>

            <!-- Extra content -->
            <span class="block text-700 font-light text-xl sm:text-xl md:text-xl lg:text-2xl xl:text-2xl">
                Ανακάλυψε το
                <strong
                    >κατάλληλο επαγγελματικό μονοπάτι για εσένα</strong
                >
                και απέκτησε πρόσβαση σε
                <strong>δωρεάν καθοδήγηση και συμβουλές</strong> για να
                χτίσεις μια <strong>επιτυχημένη καριέρα</strong>.
            </span>

            <!-- Clear any doubts -->
            <ul class="flex flex-wrap gap-5 list-none p-0">
                <li class="flex align-items-center">
                    <i class="pi pi-check-circle text-primary mr-2" style="font-size: 1.4rem"></i>

                    <span class="text-900 font-light ml-2 text-left text-xl sm:text-xl md:text-xl lg:text-xl xl:text-xl">
                        Μάθε τι μπορείς να <strong>κάνεις με το πτυχίο σου</strong>.
                    </span>
                </li>

                <li class="flex align-items-center">
                    <i class="pi pi-check-circle text-primary mr-2" style="font-size: 1.4rem"></i>

                    <span class="text-900 font-light ml-2 text-left text-xl sm:text-xl md:text-xl lg:text-xl xl:text-xl">
                        Ανακάλυψε <strong>ποιό επάγγελμα σου ταιριάζει περισσότερο</strong>.
                    </span>
                </li>
                
                <li class="flex align-items-center">                    
                    <i class="pi pi-check-circle text-primary mr-2" style="font-size: 1.4rem" ></i>

                    <span class="text-900 font-light ml-2 text-left text-xl sm:text-xl md:text-xl lg:text-xl xl:text-xl">
                        Χτίσε μια στρατηγική για να <strong>αλλάξεις καριέρα</strong>.
                    </span>
                </li>
            </ul>

            <Button
                @click="navigateTo('tests/holland/start')"
                type="button"
                label="Κάνε το δωρεάν Τεστ"
                icon="pi pi-arrow-right"
                iconPos="right"
                class="w-20rem text-xl"
            />
        </div>

        <div
            class="col-12 md:col-6 flex-order-0 md:flex-order-1 mb-6 md:mb-0 border-round"
        >
            <!-- <img :src="`/demo/images/landing/${darkMode ? 'dashboard-dark' : 'dashboard-light'}.png`" alt="" class="w-full h-full border-round shadow-2 animation-duration-1000 fadeinright" /> -->
            <img
                :src="`/images/career-explorer.png`"
                alt="Career Explorer"
                class="w-full h-full animation-duration-1000 fadeinright"
            />
        </div>
    </div>
</template>

<style scoped>
    .highlight-word {
        background-color: var(--primary-color); /* Change this to your desired highlight color */
        font-weight: bold;
        padding: 0px 10px;
    }
</style>