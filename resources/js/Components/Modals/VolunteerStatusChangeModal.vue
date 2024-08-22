<script setup>
	import { reactive, computed } from 'vue';
    import BaseTextInput from '@/Components/Base/BaseTextInput.vue';

    import BaseInfoText from '@/Components/Base/BaseInfoText.vue';
    import BaseToggleSwitchInput from '@/Components/Base/BaseToggleSwitchInput.vue';

    const emit = defineEmits([
        'update:isOpen', 
        'change'
    ]);

    // const display = ref(false)
    const props = defineProps({
        volunteerId: {
            type: Number,
            required: true
        },
        isOpen: {
            type: Boolean,
            required: true,
            default: false
        }
    })
    const display = computed({
        get: () => props.isOpen,
        set: (value) => emit('update:isOpen', value)
    });

    const form = reactive({
        reason: '',
        sendEmail: false
    });

    function closeModal() {
        emit('update:isOpen', false);

        form.sendEmail = false;
        form.reason = '';
    }

    function submit() {
        emit('change', form );
        emit('update:isOpen', false);

        form.sendEmail = false;
        form.reason = '';
    }

    function toggleSendEmailOption() {
        form.sendEmail = !form.sendEmail;
    }

    // const open = () => {
    //     display.value = true;
    // };
</script>

<template>
    <Dialog header="Αλλαγή Κατάστασης Εθελοντή" v-model:visible="display" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
        <form @submit.prevent="submit">
            <BaseTextInput
                v-model="form.reason"
                accessKey="name"
                label="Σχόλια"
                type="text"
                :errors="''"
            />

            <BaseInfoText
                type="info"
            >
                Στα σχόλια μπορείτε να αφήσετε τον λόγο αλλαγής της κατάστασης του εθελοντή.
            </BaseInfoText>
        
            <BaseToggleSwitchInput
                v-model="form.sendEmail"
                :label="'Αποστολή email ενημέρωσης στον εθελοντή'"
            />

            <BaseInfoText
                v-if="form.sendEmail"
                type="info"
            >
                Ενεργοποιώντας αυτή την επιλογή ο εθελοντής θα λάβει ένα αυτοματοποιημένο email ενημέρωσης για την αλλαγή κατάστασης του λογαριασμού του όπως αυτό έχει οριστεί στα πρότυπα email.
            </BaseInfoText>
        </form>

        <template #footer>
            <Button
                @click="submit" 
                label="Αποθήκευση" 
                icon="pi pi-check"
            />
        </template>
    </Dialog>

    <!-- <Button label="Αλλαγή Κατάστασης" class="m-1" icon="pi pi-external-link" style="width: auto" @click="open" /> -->
</template>