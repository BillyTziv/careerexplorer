<script setup>
    import { ref, watch, computed } from 'vue';
    import { router } from '@inertiajs/vue3'

    /* Layouts */
    import AppPageWrapper from '@/Layouts/AppPageWrapper.vue';
    import { useFormatDate } from '@/Composables/useFormatDate';
    import BaseBarChart from '@/Components/Base/Graph/BaseBarChart.vue';

    /* Component Properties */
    let props = defineProps({
        user: Object,
        submission: {
            type: Object,
            required: true,
        }, 
        riasec: Object,
        filters: Object,
    });

    const getGRaphData = computed(() => {

        const labels = [
            'Πρακτικός',
            'Ερευνητικός',
            'Καλλιτεχνικός',
            'Κοινωνικός',
            'Επιχειρηματικός',
            'Οργανωτικός'
        ];

        const data = labels.map(label => {
            const item = Object.values(props.riasec).find(item => item.name.includes(label));

            return item ? item.value*2 : 0;
        });

        return data;
    });
</script>

<template>
    <AppPageWrapper>
        <template #page-title>
            Προβολή Καταχώρησης
        </template>

        <template #page-content>
            <BaseBarChart
                title="Καταχωρήσεις" 
                label="Πλήθος Αιτήσεων"
                :data="getGRaphData"
                :labels="['R', 'I', 'A', 'S', 'E', 'C']"
                color="#6366F1"
            />

            <table >
                <thead>
                    <tr class="tableHeaderRow">
                        <th scope="col">Τύπος</th>
                        <th scope="col">Απαντηση</th>
                        
                        <th scope="col">Ερωτηση</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(ans, index) in submission" :key="ans.title" class="tableDataRow">
                        <td>
                            <div class="clrBox br-7" v-bind:class="{
                                'rType': ans.type==1,
                                'iType': ans.type==2,
                                'aType': ans.type==3,
                                'sType': ans.type==4,
                                'eType': ans.type==5,
                                'cType': ans.type==6}">
                                <span v-if=" ans.type==1">R</span>
                                <span v-if=" ans.type==2">I</span>
                                <span v-if=" ans.type==3">A</span>
                                <span v-if=" ans.type==4">S</span>
                                <span v-if=" ans.type==5">E</span>
                                <span v-if=" ans.type==6">C</span>
                            </div>
                            
                        </td>
                        <td>
                            <div class="clrBox br-50" v-bind:class="{'tableAnsYes': ans.answer=='yes', 'tableAnsNo': ans.answer=='no'}">
                                {{ ans.answer }}
                            </div>
                        </td>
                        <td>{{ ans.title }}</td>
                    </tr>
                </tbody>
            </table>


           
        </template>
    </AppPageWrapper>
</template>

