<script setup>

</script>

<template>
    <div class="relative overflow-hidden flex flex-column justify-content-center">
        <div class="bg-circle opacity-50" :style="{ top: '-200px', left: '-700px' }"></div>
        <div class="bg-circle hidden lg:flex" :style="{ top: '50px', right: '-800px', transform: 'rotate(60deg)' }"></div>
        <div class="landing-wrapper">
            <slot></slot>
        </div>
    </div>
</template>

<style scoped>
    .bg-circle {
        width: 1000px;
        height: 1000px;
        border-radius: 50%;
        background-image: linear-gradient(
            140deg,
            var(--primary-color),
            var(--surface-ground) 80%
        );
        position: absolute;
        opacity: 0.25;
        z-index: -1;
    }
</style>