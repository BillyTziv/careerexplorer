<template>
    <PublicPageLayout>
        <Notification2 v-if="$page.props.flash.status" :response="$page.props.flash" />
       
        <BaseFormTitle>
            <template v-slot:title>
                <div>
                    <img style="max-height: 100px;" src="/images/tedx-logo-dark-crop.png" alt="TEDx" />
                </div>
                <span style="color: white;">Αίτηση Εγγραφής Εθελοντή</span>
            </template>
            <template v-slot:subtitle>
                <span style="color: rgb(241, 241, 241);">
                    Συμπλήρωσε τα στοιχεία σου, για να δηλώσεις το ενδιαφέρον σου για εθελοντική συμμετοχή.
                </span>
                <span style="color: rgb(241, 241, 241);">
                    Εάν θέλεις και εσύ να προσφέρεις, να ζήσεις έντονες στιγμές και να αποκτήσεις νέες εμπειρίες, τι περιμένεις;<br/>
                    Γίνε και εσύ εθελοντής στο TEDx Mavili Square 2024.<br/>
                    Ανυπομονούμε να σε γνωρίσουμε και να μοιραστούμε αυτή τη μοναδική TEDx εμπειρία μαζί σου!<br/>
                    Συμπλήρωσε την παρακάτω φόρμα και έλα στην ομάδα μας, για να διαδώσουμε μαζί ιδέες που αξίζει να διαδοθούν!<br/><br/>
                    Ευχαριστούμε,<br/>
                    Η Οργανωτική Επιτροπή του TEDx Mavili Square 2024<br/>
                    #tedxmavilisquare #TEDxMaviliSq #beamavilian #bestmavilianyear<br/>
                </span>
            </template>
        </BaseFormTitle>
        
        <form @submit.prevent="submit">



            <!------------------------------------------------------------------------------------------>
            <!-- PERSONAL INFORMATION -->
            <!------------------------------------------------------------------------------------------>
            <section id="personal_information" class="mt-5">
                <BaseInput
                    v-model="form.personalInfo.firstname"
                    label="Όνομα"
                    access-key="firstname"
                    type="text"
                    :required="true"
                    :errors="errors['personalInfo.firstname']"
                />

                <BaseInput
                    v-model="form.personalInfo.lastname"
                    label="Επώνυμο"
                    access-key="lastname"
                    type="text"
                    :required="true"
                    :errors="errors['personalInfo.lastname']"
                />

                <BaseInput
                    v-model="form.contactInfo.phone"
                    label="Τηλέφωνο"
                    access-key="phone"
                    type="number"
                    :required="true"
                    :errors="errors['contactInfo.phone']"
                />

                <BaseInput
                    v-model="form.contactInfo.email"
                    label="Email"
                    access-key="email"
                    type="email"
                    :required="true"
                    :errors="errors['contactInfo.email']"
                />
            </section>

            <!------------------------------------------------------------------------------------------>
            <!-- CV -->
            <!------------------------------------------------------------------------------------------>
            <label class="block mb-2 text-sm font-medium text-sm text-gray-500 dark:text-slate-300" for="file_input">
                Επίσύναψε το βιογραφικό σου (.PDF μορφή)
            </label>
           
            <template v-if="form.personalInfo.cv">
                <img height="80px" src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1667px-PDF_file_icon.svg.png" class="w-20 h-20 rounded-lg" alt="CV" />
                <h4 class="dark:text-slate-300">{{ fileName }}</h4>
                <BaseClickButton
                    @click.prevent="removeCV"
                    :svg-path="[]"
                    :label="'Αφαίρεση Αρχείου'"
                />
            </template>

            <template v-else>
                <div class="flex items-center justify-center w-full" v-if="!form.personalInfo.cv">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                        </div>

                        <input @change="uploadCV" id="dropzone-file" type="file" class="hidden" accept="application/pdf" />
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ fileName }}</p>
                    </label>
                </div> 
            </template>
          

            <!------------------------------------------------------------------------------------------>
            <!-- ROLE -->
            <!------------------------------------------------------------------------------------------>
            <BaseSingleSelect
                v-model="form.volunteering.role"
                :options="roleDropdownList"
                label="Τι ρόλο θα ήθελες να έχεις; *"
                accessKey="role"
                placeholder="Επίλεξε έναν ρόλο"
                :errors="errors['volunteering.role']"
            />

            <br />
            <Checkbox /><span style="padding-left: 10px; color: white;">
                Συναινώ στη συλλογή του βιογραφικού σημειώματος μου για μελλοντική επικοινωνία σχετικά με περαιτέρω ευκαιρίες καριέρας από Συνεργάτες του TEDxMaviliSquare.
            </span>
            <br /> <br />

            <button
                class="dark:bg-slate-100 dark:text-slate-900 dark:bg-opacity-90 hover:dark:bg-opacity-80 inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-900 focus:outline-none border border-lime-800 focus:border-slate-200 transition  duration-150 ease-in-out hover:dark:bg-slate-100 rounded-md"
            >
                <span class="ml-1">
                    Αποστολή Αίτησης
                </span>
            </button>
        </form>
    </PublicPageLayout>
</template>

<script setup>
    import { ref, reactive, computed } from 'vue'
    import { Inertia } from '@inertiajs/inertia'

    /* Layout */
    import PublicPageLayout from '@/Components/Layouts/PublicPageLayout.vue';

    /* Form Components */
    import BaseInput from '@/Pages/Common/UI/Form/BaseInput.vue';
    import BaseSingleSelect from '../Common/UI/Form/BaseSingleSelect.vue';
    import BaseFormSubmitButton from '../Common/UI/Form/BaseFormSubmitButton.vue';
    import BaseInfoText from '../Common/UI/Form/BaseInfoText.vue';
    import BaseFormTitle from '../Common/UI/Form/BaseFormTitle.vue';
    import Notification2 from '../Common/Notification2.vue';
    import BaseClickButton from '@/Pages/Common/UI/Buttons/BaseClickButton.vue';
    import Checkbox from '@/Components/Checkbox.vue';


    let props = defineProps({
        roles: Object,
        response: Object,
        errors: Object
    });

    const form = reactive({
        volunteering: {
            role: null
        },
        contactInfo: {
            phone: "",
            email: ""
        },
        personalInfo: {
            firstname: "",
            lastname: "",
            cv: ""
        }
    })

    const fileName = ref('');

    const roleDropdownList = computed( () => {
        return props.roles.map(role => ({
            id: role.id,
            label: role.name
        }));
    })

    function uploadCV( event ) {
        const file = event.target.files[0];
        const reader = new FileReader();
        fileName.value = file.name;
 
        reader.readAsDataURL(file);
        reader.onload = () => {
            form.personalInfo.cv = reader.result;
        };
    }
    
    function removeCV() {
        form.personalInfo.cv = null;
        fileName.value = '';
    }

    function submit() {
        Inertia.post('/volunteers/apply', form);
    }
</script>