<script setup>
    import { ref } from 'vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';

    const emit = defineEmits([
        'submitComment'
    ]);

    const comment = ref('');

    function submitComment() {
        emit('submitComment', comment.value);

        comment.value = ""
    }
</script>

<template>
    <BaseTextareaInput
        v-model="comment"
        label="Σχόλιο"
        placeholder="Πληκτρολογήστε το σχόλιο σας εδώ..."
        class="mt-4"
    />

    <Button
        @click="submitComment"
        label="Υποβολή Σχόλίου"
        primary
        class="mx-2"
    />
</template>
  
