<script setup>
    import { ref } from 'vue';

    const props = defineProps({
        cv: {
            type: String,
            required: true
        }
    })

    const isCVModalOpen = ref(false);

    const toggleCVModal = () => {
        isCVModalOpen.value = !isCVModalOpen.value;
    };

    // Function to download PDFs
    function downloadPDF() {
        const link = document.createElement("a");
        link.href = props.cv;
        link.download = "downloaded-file.pdf"; // Set the desired PDF file name
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

<template>
    <Button
        @click="toggleCVModal" 
        label="Προβολή CV" 
        icon="pi pi-search"
        primary
    />

    <Button
        @click="downloadPDF()"
        label="Λήψη CV" 
        icon="pi pi-download" 
        outlined
        class="ml-0 md:ml-2"
    />

    <Dialog
        header="Βιογραφικό Σημείωμα (CV)" 
        v-model:visible="isCVModalOpen" 
        maximizable 
        :breakpoints="{ '960px': '75vw' }" 
        :style="{ width: '100vw' }" 
        :modal="true"
    >
        <div :style="{ width: '100vw', height: '100vh' }">
            <embed :src="cv" type="application/pdf" width="100%" height="100%">
        </div>

        <template #footer>
            <Button
                @click="toggleCVModal"
                label="Κλείσιμο" 
                icon="pi pi-check" 
                outlined 
            />
        </template>
    </Dialog>
</template>
  
