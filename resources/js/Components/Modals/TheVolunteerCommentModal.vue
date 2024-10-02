<script setup>
    import { ref } from 'vue';
    import BaseTextareaInput from '@/Components/Base/BaseTextareaInput.vue';

    const emit = defineEmits([
        'submitComment'
    ]);

    const comment = ref('');

    function submitComment() {
        emit('submitComment', comment.value);
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
        label="Υποβολή"
        primary
        class="mt-4"
    />
</template>
  
