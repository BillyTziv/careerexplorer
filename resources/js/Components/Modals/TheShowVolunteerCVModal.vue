<script setup>
    import { ref, computed } from 'vue';

    const props = defineProps({
        cv: {
            type: String,
            default: '',
            required: true
        }
    })

    const display = ref(false);

    const open = () => {
        display.value = true;
    };

    const close = () => {
        display.value = false;
    };

    const hasCV = computed(() => {
        return props.cv !== '';
    });

    // Function to download images
    function downloadImage(dataURI) {
        const link = document.createElement("a");
        link.href = dataURI;
        link.download = "downloaded-image.jpeg"; // Set the desired image file name
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Function to download PDFs
    function downloadPDF(dataURI) {
        const link = document.createElement("a");
        link.href = dataURI;
        link.download = "downloaded-file.pdf"; // Set the desired PDF file name
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

<template>
    <Dialog header="Βιογραφικό" v-model:visible="display" maximizable :breakpoints="{ '960px': '75vw' }" :style="{ width: '100vw' }" :modal="true">
        <div :style="{ width: '100vw', height: '100vh' }">
            <embed :src="cv" type="application/pdf" width="100%" height="100%">
        </div>

        <template #footer>
            <Button label="Κλείσιμο" @click="close" icon="pi pi-check" outlined />
        </template>
    </Dialog>

    <button @click="downloadImage(imageData)">Download Image</button>
    <button @click="downloadPDF(pdfData)">Download PDF</button>
    <Button v-if="hasCV" label="Προβολή Βιογραφικού" icon="pi pi-external-link" style="width: auto" @click="open" />
</template>
  
