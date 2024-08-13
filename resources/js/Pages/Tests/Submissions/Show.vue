<template>
    <CustomNavbar :user="user">

        <template #page-title>
            Αναλυτική Προβολή Απάντησης
        </template>

        <template #page-content>
            <canvas height= "330px" id="riasecChartPlaceholder" class="mt-4" 
                style="color: white !important; max-height: 330px; min-height: 330px;">
            </canvas>

            <table class="table vt-table mt-5" style="color: white;">
                <thead>
                    <tr class="tableHeaderRow">
                        <th scope="col">#</th>
                        <th scope="col">Τύπος</th>
                        <th scope="col">Απαντηση</th>
                        
                        <th scope="col">Ερωτηση</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(ans, index) in answers" :key="ans.title" class="tableDataRow">
                        <td>{{ index }} </td>
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

    </CustomNavbar>
</template>


<script setup>
    import { onBeforeMount, ref, computed } from 'vue';
    import CustomNavbar from '@/Pages/Common/CustomNavbar.vue';

    let props = defineProps({
        user: Object,
        answers: Object,
        riasec: Object  
    });

    const riasecChartData = ref({});
    const riasecChartOptions = ref({});

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

    onBeforeMount(() => {
        riasecChartOptions.value = {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#FFFFFF'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#FFFFFF'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#FFFFFF'
                    }
                }
            }
        };

        riasecChartData.value = {
            labels: [
                'Πρακτικός',
                'Ερευνητικός',
                'Καλλιτεχνικός',
                'Κοινωνικός',
                'Επιχειρηματικός',
                'Οργανωτικός'
            ],
            datasets: [{
                label: [],
                data: getGRaphData,
                fill: true,
                tension: 0.1,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 205, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)'
                ],
            }]
        };
    });

    setTimeout(() => {
        var riasec = new Chart(document.getElementById('riasecChartPlaceholder'), {
            type: 'bar',
            data: riasecChartData.value,
            options: riasecChartOptions.value
        });
    }, 500);
</script>