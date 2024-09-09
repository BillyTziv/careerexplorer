<script setup>
import { ref } from 'vue';

defineProps({
    user: {
        type: Object,
        required: true
    }
});

const defaultUserId = ref(123);
const emit = defineEmits(['send:message']);
const op = ref(null);
const textContent = ref('');

const emojis = [
    '😀',
    '😃',
    '😄',
    '😁',
    '😆',
    '😅',
    '😂',
    '🤣',
    '😇',
    '😉',
    '😊',
    '🙂',
    '🙃',
    '😋',
    '😌',
    '😍',
    '🥰',
    '😘',
    '😗',
    '😙',
    '😚',
    '🤪',
    '😜',
    '😝',
    '😛',
    '🤑',
    '😎',
    '🤓',
    '🧐',
    '🤠',
    '🥳',
    '🤗',
    '🤡',
    '😏',
    '😶',
    '😐',
    '😑',
    '😒',
    '🙄',
    '🤨',
    '🤔',
    '🤫',
    '🤭',
    '🤥',
    '😳',
    '😞',
    '😟',
    '😠',
    '😡',
    '🤬',
    '😔',
    '😟',
    '😠',
    '😡',
    '🤬',
    '😔',
    '😕',
    '🙁',
    '😬',
    '🥺',
    '😣',
    '😖',
    '😫',
    '😩',
    '🥱',
    '😤',
    '😮',
    '😱',
    '😨',
    '😰',
    '😯',
    '😦',
    '😧',
    '😢',
    '😥',
    '😪',
    '🤤'
];

const parseDate = (timestamp) => {
    return new Date(timestamp).toTimeString().split(':').slice(0, 2).join(':');
};

const sendMessage = () => {
    if (textContent.value == '' || textContent.value === ' ') {
        return;
    }
    let message = {
        text: textContent.value,
        ownerId: 123,
        createdAt: new Date().getTime()
    };

    emit('send:message', message);
    textContent.value = '';
};

const addEmoji = (emoji) => {
    textContent.value = textContent.value + emoji;
    op.value.hide();
};
</script>

<template>
    <div class="flex flex-column h-full">
        <div class="flex align-items-center border-bottom-1 surface-border p-3 lg:p-6">
            
        </div>
        <div class="user-message-container p-3 md:px-4 lg:px-6 lg:py-4 mt-2 overflow-y-auto" style="max-height: 53vh">
          
        </div>
        <div class="p-3 md:p-4 lg:p-6 flex flex-column sm:flex-row align-items-center mt-auto border-top-1 surface-border gap-3">
            <InputText id="message" type="text" placeholder="Type a message" class="flex-1 w-full sm:w-auto border-round" v-model="textContent" @keydown.enter="sendMessage()" />
            <div class="flex w-full sm:w-auto gap-3">
                <Button class="p-button w-full sm:w-auto justify-content-center text-xl" severity="secondary" @click="(event) => $refs.op.toggle(event)">😀</Button>
                <Button label="Send" icon="pi pi-send" class="w-full sm:w-auto" @click="sendMessage()"></Button>
            </div>
        </div>
    </div>

    <OverlayPanel ref="op" class="w-full sm:w-30rem">
        <Button v-for="emoji in emojis" :key="emoji" @click="addEmoji(emoji)" type="button" :label="emoji" class="p-2 text-2xl" text></Button>
    </OverlayPanel>
</template>
