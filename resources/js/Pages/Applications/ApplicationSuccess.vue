<script setup>
    /* Layouts */
    import GuestLayout from '@/Layouts/GuestLayout.vue';

    let props = defineProps({
        user: Object,
        response: Object,

        errors: {
            type: Object,
            required: false,
            default: () => ({}),
        },
    });

</script>

<template>
    <div class="card">
        <span class="text-700 text-xl">Τέλεια!</span>
        <div class="text-900 font-bold text-4xl my-2">Επιτυχής υποβολής αίτησης 🚀</div>
        <p class="text-700 text-xl mt-0 mb-4 p-0">Τα στοιχεία σου έχουν καταχωρηθεί με ασφάλεια στο σύστημα. Ένας εκπρόσωπος του οργανισμού θα έρθει σε επικοινωνία μαζί σου στο email ή τηλέφωνο που έδωσες.</p>

        <div :style="{ height: '3px', background: 'linear-gradient(90deg, var(--primary-color) 0%, rgba(33, 150, 243, 0) 50%)' }"></div>

        <!-- <div class="flex flex-column sm:flex-row sm:align-items-center sm:justify-content-between py-5">
            <div class="mb-3 sm:mb-0">
                <span class="font-medium text-xl text-900 mr-2">Order number:</span>
                <span class="font-medium text-xl text-blue-500">451234</span>
            </div>
            <div>
                <Button label="Details" icon="pi pi-list" class="mr-2" outlined></Button>
                <Button label="Print" icon="pi pi-print" outlined></Button>
            </div>
        </div> -->
        <div class="border-round surface-border border-1">
            <ul class="list-none p-0 m-0">
                <li v-for="(product, i) in products" :key="i" class="p-3 surface-border flex align-items-start sm:align-items-center" :class="{ 'border-bottom-1': i !== products.length - 1 }">
                    <img :src="product.image" class="w-3rem sm:w-8rem flex-shrink-0 mr-3 shadow-2" :alt="product.name" />
                    <div class="flex flex-column">
                        <span class="text-900 font-semibold text-xl mb-2">{{ product.name }}</span>
                        <span class="text-700 font-medium mb-3"> {{ product.color }} | {{ product.size }} </span>
                        <span class="text-900 font-medium">Quantity: {{ product.quantity }}</span>
                    </div>
                    <span class="text-900 font-medium text-lg ml-auto">{{ product.price }}</span>
                </li>
            </ul>
        </div>
    
        <div class="text-center mb-4 mt-5">
            <div class="flex flex-row justify-center">
                <div class="px-2">
                    <a href="https://www.facebook.com/futuregenerationgr" target="_blank">
                        <i class="pi pi-facebook"style="font-size: 3.5rem; color: darkblue"></i>
                    </a> 
                </div>

                <div class="px-2">
                    <a href="https://www.linkedin.com/company/futuregeneration" target="_blank">
                        <i class="pi pi-linkedin"style="font-size: 3.5rem; color: darkblue"></i>

                    </a>
                </div>

                <div class="px-2">
                    <a href="https://www.instagram.com/futuregenerationgr/" target="_blank">
                        <i class="pi pi-instagram"style="font-size: 3.5rem; color: darkblue"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>