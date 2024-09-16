<script setup>
    /* Core */
    import { onMounted } from 'vue';
    import { router, Head, Link, useForm } from '@inertiajs/vue3';

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';

    /* Emits Actions */
    import { useUserStore } from '@/Stores/useUser.store';

    import BaseOverviewCard from '@/Components/Base/Graph/BaseOverviewCard.vue';

    const userStore = useUserStore();

    /* Component Properties */
    let props = defineProps({
        user: Object
    });

    onMounted(() => {
        userStore.setUser( props.user )
    });

    function redirectTo(route) {
        router.visit({ name: route });
    }
</script>

<template>
    <AppPageWrapper>
        <template #page-content>
            <div class="grid">
                <div class="col-12">
                    <div class="flex flex-column sm:flex-row align-items-center gap-4">
                        <div class="flex flex-column sm:flex-row align-items-center gap-3">
                            <!-- <img alt="avatar" src="/demo/images/avatar/circle/avatar-f-1.png" class="w-4rem h-4rem flex-shrink-0" /> -->
                            <div class="flex flex-column align-items-center sm:align-items-start">
                                <span class="text-900 font-bold text-4xl">Welcome</span>
                                <!-- <p class="text-600 m-0">Your last login was on 04/05/2022 at 10:24 am</p> -->
                            </div>
                        </div>
                        <!-- <div class="flex gap-2 sm:ml-auto">
                            <Button type="button" v-tooltip.bottom="'Exchange'" icon="pi pi-arrows-h" outlined rounded></Button>
                            <Button type="button" v-tooltip.bottom="'Withdraw'" icon="pi pi-download" outlined rounded></Button>
                            <Button type="button" v-tooltip.bottom="'Send'" icon="pi pi-send" rounded></Button>
                        </div>  -->
                    </div>
                </div>
                <div class="col-12 md:col-6 xl:col-4">
                    <a href="/applications/fg/volunteer" class="text-primary hover:underline cursor-pointer font-medium">
                        Αίτηση Εγγραφής Εθελοντή
                    </a>

                    <br /> <br />

                    <a href="/applications/fg/session-request" class="text-primary hover:underline cursor-pointer font-medium">
                        Αίτηση Συνεδρίας Ε.Π.
                    </a>

                    <br /> <br />

                    <a href="/explore" class="text-primary hover:underline cursor-pointer font-medium">
                        Εύρεση Επαγγέλματος
                    </a>

                 
                </div>
                <!-- <div class="col-12 md:col-6 xl:col-4">
                    <div class="card h-full relative overflow-hidden">
                        <svg id="visual" viewBox="0 0 900 600" xmlns="http://www.w3.org/2000/svg" xmlnsXlink="http://www.w3.org/1999/xlink" version="1.1" class="absolute left-0 top-0 h-full w-full" preserveAspectRatio="none">
                            <rect x="0" y="0" width="900" height="600" fill="var(--primary-600)"></rect>
                            <path
                                d="M0 400L30 386.5C60 373 120 346 180 334.8C240 323.7 300 328.3 360 345.2C420 362 480 391 540 392C600 393 660 366 720 355.2C780 344.3 840 349.7 870 352.3L900 355L900 601L870 601C840 601 780 601 720 601C660 601 600 601 540 601C480 601 420 601 360 601C300 601 240 601 180 601C120 601 60 601 30 601L0 601Z"
                                fill="var(--primary-500)"
                                strokeLinecap="round"
                                strokeLinejoin="miter"
                            ></path>
                        </svg>
                        <div class="z-2 relative text-white">
                            <div class="text-xl font-semibold mb-3">Debit Card</div>
                            <div class="mb-1 font-semibold">Balance</div>
                            <div class="text-2xl mb-5 font-bold">$2.000,00</div>
                            <div class="flex align-items-center justify-content-between">
                                <span class="text-lg">**** **** **** 1412</span>
                                <span class="font-medium text-lg">12/26</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 md:col-6 xl:col-4">
                    <div class="card h-full">
                        <div class="flex align-items-center justify-content-between mb-3">
                            <div class="text-900 text-xl font-semibold">Credit Card</div>
                            <img alt="avatar" src="/demo/images/banking/visa.svg" class="h-1rem" />
                        </div>
                        <div class="text-600 mb-1 font-semibold">Debt</div>
                        <div class="text-900 text-2xl text-primary mb-5 font-bold">$1.500,00</div>
                        <div class="flex align-items-center justify-content-between">
                            <span class="text-900 text-lg">**** **** **** 1231</span>
                            <span class="text-600 font-medium text-lg">12/24</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 md:col-6 xl:col-2">
                    <div class="card h-full flex flex-column align-items-center justify-content-center">
                        <i class="pi pi-dollar text-primary text-4xl mb-4"></i>
                        <span class="text-900 text-lg mb-4 font-medium">Primary</span>
                        <span class="text-900 text-2xl text-primary font-bold">$24,345.21</span>
                    </div>
                </div>
                <div class="col-12 md:col-6 xl:col-2">
                    <div class="card h-full flex flex-column align-items-center justify-content-center">
                        <i class="pi pi-euro text-primary text-4xl mb-4"></i>
                        <span class="text-900 text-lg mb-4 font-medium">Currency</span>
                        <span class="text-900 text-2xl text-primary font-bold">$10,416.11</span>
                    </div>
                </div>

                <div class="col-12 xl:col-4">
                    <div class="card">
                        <div class="text-900 text-xl font-semibold mb-3">Recent Transactions</div>
                        <ul class="list-none p-0 m-0">
                            <li class="flex align-items-center p-3 mb-3 border-bottom-1 surface-border">
                                <img alt="brands" src="/demo/images/banking/airbnb.png" class="w-3rem flex-shrink-0 mr-3" />
                                <div class="flex flex-column">
                                    <span class="text-xl font-medium text-900 mb-1">Airbnb</span>
                                    <span>05/23/2022</span>
                                </div>
                                <span class="text-xl text-900 ml-auto font-semibold">$250.00</span>
                            </li>
                            <li class="flex align-items-center p-3 mb-3 border-bottom-1 surface-border">
                                <img alt="brands" src="/demo/images/banking/amazon.png" class="w-3rem flex-shrink-0 mr-3" />
                                <div class="flex flex-column">
                                    <span class="text-xl font-medium text-900 mb-1">Amazon</span>
                                    <span>04/12/2022</span>
                                </div>
                                <span class="text-xl text-900 ml-auto font-semibold">$50.00</span>
                            </li>
                            <li class="flex align-items-center p-3 mb-3 border-bottom-1 surface-border">
                                <img alt="brands" src="/demo/images/banking/nike.svg" class="w-3rem flex-shrink-0 mr-3 border-circle" />
                                <div class="flex flex-column">
                                    <span class="text-xl font-medium text-900 mb-1">Nike Store</span>
                                    <span>04/29/2022</span>
                                </div>
                                <span class="text-xl text-900 ml-auto font-semibold">$60.00</span>
                            </li>
                            <li class="flex align-items-center p-3 mb-3 border-bottom-1 surface-border">
                                <img alt="brands" src="/demo/images/banking/starbucks.svg" class="w-3rem flex-shrink-0 mr-3" />
                                <div class="flex flex-column">
                                    <span class="text-xl font-medium text-900 mb-1">Starbucks</span>
                                    <span>04/15/2022</span>
                                </div>
                                <span class="text-xl text-900 ml-auto font-semibold">$15.24</span>
                            </li>
                            <li class="flex align-items-center p-3 mb-3">
                                <img alt="brands" src="/demo/images/banking/amazon.png" class="w-3rem flex-shrink-0 mr-3" />
                                <div class="flex flex-column">
                                    <span class="text-xl font-medium text-900 mb-1">Amazon</span>
                                    <span>04/12/2022</span>
                                </div>
                                <span class="text-xl text-900 ml-auto font-semibold">$12.50</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 xl:col-8">
                    <div class="card h-full">
                        <div class="text-900 text-xl font-semibold mb-3">Overview</div>
                        <Chart type="line" :data="chartData" :options="chartOptions"></Chart>
                    </div>
                </div>

                <div class="col-12 lg:col-6">
                    <div class="card h-full">
                        <div class="flex align-items-center justify-content-between mb-3">
                            <div class="text-900 text-xl font-semibold">Recent Transactions</div>
                            <Button type="button" icon="pi pi-plus" label="Add New" size="small" outlined></Button>
                        </div>
                        <div class="flex flex-column row-gap-3">
                            <div class="flex flex-column lg:flex-row gap-3">
                                <div class="w-full lg:w-6 p-3 border-1 border-round surface-border flex align-items-center hover:surface-100 cursor-pointer border-radius">
                                    <img alt="avatar" src="/demo/images/avatar/circle/avatar-f-1.png" class="w-2rem flex-shrink-0 mr-2" />
                                    <span class="text-900 text-lg font-medium">Aisha Williams</span>
                                </div>
                                <div class="w-full lg:w-6 p-3 border-1 border-round surface-border flex align-items-center hover:surface-100 cursor-pointer border-radius">
                                    <img alt="avatar" src="/demo/images/avatar/circle/avatar-f-2.png" class="w-2rem flex-shrink-0 mr-2" />
                                    <span class="text-900 text-lg font-medium">Jane Watson</span>
                                </div>
                            </div>
                            <div class="flex flex-column lg:flex-row gap-3">
                                <div class="w-full lg:w-6 p-3 border-1 border-round surface-border flex align-items-center hover:surface-100 cursor-pointer border-radius">
                                    <img alt="avatar" src="/demo/images/avatar/circle/avatar-m-1.png" class="w-2rem flex-shrink-0 mr-2" />
                                    <span class="text-900 text-lg font-medium">Brad Curry</span>
                                </div>
                                <div class="w-full lg:w-6 p-3 border-1 border-round surface-border flex align-items-center hover:surface-100 cursor-pointer border-radius">
                                    <img alt="avatar" src="/demo/images/avatar/circle/avatar-f-3.png" class="w-2rem flex-shrink-0 mr-2" />
                                    <span class="text-900 text-lg font-medium">Claire Dunphy</span>
                                </div>
                            </div>
                            <div class="flex flex-column lg:flex-row gap-3">
                                <div class="w-full lg:w-6 p-3 border-1 border-round surface-border flex align-items-center hover:surface-100 cursor-pointer border-radius">
                                    <img alt="avatar" src="/demo/images/avatar/circle/avatar-m-2.png" class="w-2rem flex-shrink-0 mr-2" />
                                    <span class="text-900 text-lg font-medium">Kevin James</span>
                                </div>
                                <div class="w-full lg:w-6 p-3 border-1 border-round surface-border flex align-items-center hover:surface-100 cursor-pointer">
                                    <img alt="avatar" src="/demo/images/avatar/circle/avatar-f-4.png" class="w-2rem flex-shrink-0 mr-2" />
                                    <span class="text-900 text-lg font-medium">Sarah McTamish</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-column sm:flex-row gap-3 mt-5">
                            <div class="flex-1 p-fluid">
                                <InputNumber type="text" :value="price" mode="currency" currency="USD" locale="en-US"></InputNumber>
                            </div>
                            <Button type="button" label="Send"></Button>
                        </div>
                    </div>
                </div>

                <div class="col-12 lg:col-6">
                    <div class="card">
                        <div class="text-900 text-xl font-semibold mb-3">Monthly Payments</div>

                        <DataTable ref="dt" :value="payments" dataKey="id" :rows="5">
                            <template #empty> No products found.</template>
                            <Column field="name" header="Name" class="white-space-nowrap w-4"> </Column>
                            <Column field="price" header="Amount" class="white-space-nowrap w-4">
                                <template #body="{ data }">
                                    {{ formatCurrency(data.amount) }}
                                </template>
                            </Column>
                            <Column field="date" header="Date" class="white-space-nowrap w-4"> </Column>
                            <Column field="inventoryStatus" header="Status" class="white-space-nowrap w-4 text-right">
                                <template #body="{ data }">
                                    <Tag v-if="data.paid" value="COMPLETED" severity="success"></Tag>
                                    <Tag v-else value="PENDING" severity="warning"></Tag>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div> -->
            </div>
        </template>
    </AppPageWrapper>
</template>