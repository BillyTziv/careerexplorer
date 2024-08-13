<template>
  <CustomNavbar :user="user">

    <template #page-title>
        Πρότυπο Test
    </template>
    
     <template #page-content>
        <h2 class="text-xl font-bold dark:text-slate-300">{{ test.name }}</h2>
        <span class="text-md dark:text-slate-300">{{ test.description }}</span>
        
     
        <h4 class="mt-4 text-md dark:text-slate-300 font-bold">Λίστα Ερωτήσεων:</h4>
        <ul class="dark:text-slate-300">
            <li v-for="question in test.questions">
                {{ question.id }}: {{ question.title }}</li>
        </ul>
      </template>

  </CustomNavbar>
</template>

<script setup>
    import CustomNavbar from '../../Common/CustomNavbar.vue';

    let props = defineProps({
        user: Object,
        test: Object,
        response: Object
    });

    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
  }
</script>