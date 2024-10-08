<script setup>
import { ProductService } from '@/Services/ProductService';
import { PhotoService } from '@/Services/PhotoService';
import { ref, onMounted } from 'vue';

const products = ref([]);
const images = ref([]);
const galleriaResponsiveOptions = ref([
    {
        breakpoint: '1024px',
        numVisible: 5
    },
    {
        breakpoint: '960px',
        numVisible: 4
    },
    {
        breakpoint: '768px',
        numVisible: 3
    },
    {
        breakpoint: '560px',
        numVisible: 1
    }
]);
const carouselResponsiveOptions = ref([
    {
        breakpoint: '1024px',
        numVisible: 3,
        numScroll: 3
    },
    {
        breakpoint: '768px',
        numVisible: 2,
        numScroll: 2
    },
    {
        breakpoint: '560px',
        numVisible: 1,
        numScroll: 1
    }
]);

const productService = new ProductService();
const photoService = new PhotoService();

onMounted(() => {
    productService.getProductsSmall().then((data) => (products.value = data));
    photoService.getImages().then((data) => (images.value = data));
});
const getBadgeSeverity = (status) => {
    const stockStatus = {
        OUTOFSTOCK: 'danger',
        LOWSTOCK: 'warning',
        INSTOCK: 'success'
    };

    return stockStatus[status];
};
</script>

<template>
    <div class="grid p-fluid">
        <div class="col-12">
            <div class="card">
                <h5>Carousel</h5>
                <Carousel :value="products" :numVisible="3" :numScroll="3" :circular="false" :responsiveOptions="carouselResponsiveOptions">
                    <template #item="product">
                        <div class="border-round m-2 text-center p-2" style="border: 1px solid var(--surface-d)">
                            <div class="my-5">
                                <img :src="'/demo/images/product/' + product.data.image" :alt="product.name" class="shadow-4" style="width: 50%" />
                            </div>
                            <div>
                                <h5 class="mb-1">{{ product.data.name }}</h5>
                                <h6 class="mt-0 mb-3">${{ product.data.price }}</h6>
                                <Tag :severity="getBadgeSeverity(product.data.inventoryStatus)">{{ product.data.inventoryStatus }}</Tag>
                                <div class="my-5">
                                    <Button icon="pi pi-search" class="mr-2" rounded></Button>
                                    <Button icon="pi pi-star-fill" class="mr-2" severity="success" rounded></Button>
                                    <Button icon="pi pi-cog" severity="info" rounded></Button>
                                </div>
                            </div>
                        </div>
                    </template>
                </Carousel>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <h5>Image</h5>
                <div class="flex justify-content-center">
                    <Image :src="'/demo/images/galleria/galleria11.jpg'" alt="Image" width="250" preview />
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <h5>Galleria</h5>
                <Galleria :value="images" :responsiveOptions="galleriaResponsiveOptions" :numVisible="7" :circular="true" containerStyle="max-width: 800px; margin: auto">
                    <template #item="slotProps">
                        <img :src="slotProps.item.itemImageSrc" :alt="slotProps.item.alt" style="width: 100%; display: block" />
                    </template>
                    <template #thumbnail="slotProps">
                        <img :src="slotProps.item.thumbnailImageSrc" :alt="slotProps.item.alt" tyle="width: 100%; display: block;" />
                    </template>
                </Galleria>
            </div>
        </div>
    </div>
</template>
