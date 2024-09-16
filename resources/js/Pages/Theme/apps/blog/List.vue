<script setup>
import { ref } from 'vue';


</script>

<template>
    <div class="card">
        <DataView :value="totalBlogs" paginator :rows="3" layout="grid" :sortOrder="sortOrder" :sortField="sortField">
            <template #header>
                <div class="flex flex-column sm:flex-row sm:align-items-center sm:justify-content-between gap-3">
                    <span class="text-xl text-900 font-semibold">Articles</span>
                    <Dropdown v-model="sortKey" :options="sortOptions" optionLabel="label" placeholder="Sort By" class="w-full md:w-15rem" @change="onSortChange($event)" />
                </div>
            </template>
            <template #grid="slotProps">
                <div  class="grid grid-nogutter">
                    <div v-for="(item, index) in slotProps.items" :key="index" class="col-12 md:col-4">
                        <div class="p-3">
                            <div class="surface-100 cursor-pointer z-index border-round">
                                <div class="relative">
                                    <img :src="item.coverImage" class="w-full" :alt="item.description.split(' ', 1)" />
                                    <img :src="item.profile" class="flex absolute w-4rem h-4rem" :style="{ bottom: '-1.5rem', right: '1.5rem' }" :alt="item.description.split(' ', 1)" />
                                </div>
                                <div class="p-3">
                                    <div class="text-900 font-semibold text-xl mb-3">{{ item.title }}</div>
                                    <p class="text-700 text-lg mt-0 mb-5">{{ item.description }}</p>
    
                                    <div class="flex flex-wrap gap-2 align-items-center justify-content-between">
                                        <span class="flex align-items-center text-900">
                                            <i class="pi pi-comment mr-2"></i>
                                            <span class="font-semibold">{{ item.comment }}</span>
                                        </span>
                                        <span class="flex align-items-center text-900">
                                            <i class="pi pi-share-alt mr-2"></i>
                                            <span class="font-semibold">{{ item.share }}</span>
                                        </span>
                                        <span class="flex align-items-center text-900">
                                            <i class="pi pi-clock mr-2"></i>
                                            <span class="font-semibold mr-1">{{ item.day }}</span>
                                            <span class="font-semibold">{{ item.month }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </DataView>
    </div>

    <div class="card">
        <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
            <div class="font-bold text-5xl text-900 mb-3">Recent Articles</div>
            <div class="text-700 text-xl line-height-3 mb-5">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
            <div class="grid nogutter">
                <div class="col-12 lg:col-4 p-4">
                    <div class="border-top-3 border-blue-600"></div>
                    <div class="text-blue-600 font-medium my-2">Animals</div>
                    <div class="text-900 font-medium text-xl line-height-3 mb-4">Why Earth&lsquo;s most beloved creatures are headed toward extinction</div>
                    <div class="font-sm text-700 line-height-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
                    <div class="flex mt-4">
                        <Avatar image="/demo/images/avatar/circle/avatar-f-1.png" shape="circle"></Avatar>
                        <div class="ml-2">
                            <div class="text-xs font-bold text-900 mb-1">Anna Miles</div>
                            <div class="text-xs flex align-items-center text-700">
                                <i class="pi pi-calendar mr-1 text-xs"></i>
                                <span>Apr 9, 2021</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 lg:col-4 p-4">
                    <div class="border-top-3 border-pink-600"></div>
                    <div class="text-pink-600 font-medium my-2">Oxygen</div>
                    <div class="text-900 font-medium text-xl line-height-3 mb-4">Only one-third of tropical rainforests remain intact, study says</div>
                    <div class="font-sm text-700 line-height-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
                    <div class="flex mt-4">
                        <Avatar image="/demo/images/avatar/circle/avatar-f-2.png" shape="circle"></Avatar>
                        <div class="ml-2">
                            <div class="text-xs font-bold text-900 mb-1">Arlene Miles</div>
                            <div class="text-xs flex align-items-center text-700">
                                <i class="pi pi-calendar mr-1 text-xs"></i>
                                <span>Apr 9, 2021</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 lg:col-4 p-4">
                    <div class="border-top-3 border-orange-600"></div>
                    <div class="text-orange-600 font-medium my-2">Nature</div>
                    <div class="text-900 font-medium text-xl line-height-3 mb-4">Does planting a tree really offset your carbon footprint?</div>
                    <div class="font-sm text-700 line-height-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
                    <div class="flex mt-4">
                        <Avatar image="/demo/images/avatar/circle/avatar-f-3.png" shape="circle"></Avatar>
                        <div class="ml-2">
                            <div class="text-xs font-bold text-900 mb-1">Diane Miles</div>
                            <div class="text-xs flex align-items-center text-700">
                                <i class="pi pi-calendar mr-1 text-xs"></i>
                                <span>Apr 9, 2021</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
