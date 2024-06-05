<script setup>
    import { ref } from 'vue';
    const value = ref(null);
    const confirmed = ref(false);
</script>

<template>
    <div class="px-5 min-h-screen flex justify-content-center align-items-center">
        <div class="border-1 surface-border surface-card border-round py-7 px-4 md:px-7 z-1">
            <div class="mb-4">
                <div class="text-900 text-xl font-bold mb-2">Register</div>
                <span class="text-600 font-medium">Let&lsquo;s get started</span>
            </div>
            <div class="flex flex-column">
                <IconField iconPosition="left" class="w-full mb-4">
                    <InputIcon class="pi pi-user" />
                    <InputText id="username" type="text" class="w-full md:w-25rem" placeholder="Username" />
                </IconField>
                <IconField iconPosition="left" class="w-full mb-4">
                    <InputIcon class="pi pi-envelope" />
                    <InputText id="email" type="text" class="w-full md:w-25rem" placeholder="Email" />
                </IconField>
                <IconField iconPosition="left" class="w-full mb-4">
                    <InputIcon class="pi pi-lock z-2" />
                    <Password id="password" placeholder="Password" v-model="value" class="w-full" :inputStyle="{ paddingLeft: '2.5rem' }" inputClass="w-full md:w-25rem" toggleMask></Password>
                </IconField>
                <div class="mb-4 flex flex-wrap">
                    <Checkbox name="checkbox" v-model="confirmed" class="mr-2" binary></Checkbox>
                    <label for="checkbox" class="text-900 font-medium mr-2"> I have read the </label>
                    <a class="text-600 cursor-pointer hover:text-primary cursor-pointer">Terms and Conditions</a>
                </div>
                <Button label="Sign Up" class="w-full mb-4"></Button>
                <span class="font-medium text-600"> Already have an account? <a class="font-semibold cursor-pointer text-900 hover:text-primary transition-colors transition-duration-300" >Login</a> </span>
            </div>
        </div>
    </div>
</template>
