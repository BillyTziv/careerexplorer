<script setup>
const props = defineProps({
    title: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true,
        default: 'Undefined Dataset Label'
    },
    data: {
        type: Array,
        required: true,
        default: () => []
    },
    labels: {
        type: Array,
        required: true,
        default: () => [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ]
    },
    color: {
        type: String,
        default: '#323232',
    }
});

function transparentize(color, opacity) {
    const alpha = 1 - opacity;
    return color.replace('rgb', 'rgba').replace(')', `, ${alpha})`);
}

const chartData = {
    labels: props.labels,
    datasets: [
        {
            label: props.label,
            data: props.data,
            borderColor: props.color,
            backgroundColor: transparentize(props.color, 0.5),
        }
    ]
};

const chartOptions = {
    animation: {
        duration: 1500
    },
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            labels: {
                color: '#323232',
                usePointStyle: true,
                font: {
                    weight: 700
                },
                padding: 25
            },
            position: 'top'
        }
    },
    scales: {
        x: {
            ticks: {
                color: '#323232',   // labels on X axis
                font: {
                    weight: 500
                }
            },
            grid: {
                display: false,
                drawBorder: false
            }
        },
        y: {
            ticks: {
                color: '#323232'    // labels on Y axis
            },
            grid: {
                color: '#dbdbdb',   // grid color
                drawBorder: true
            }
        }
    }
};

function previousPage() {
    console.log('Previous Page');
    emit('previousPage');
};

function nextPage() {
    console.log('Next Page');
    emit('nextPage');
};
</script>


<template>
    <div class="card">
        <div class="flex align-items-start justify-content-between mb-6">
            <span class="text-900 text-xl font-semibold">
                {{ title }}
            </span>
        </div>

        <Chart type="bar" :height="300" :data="chartData" :options="chartOptions" />

        <div class="flex justify-content-between">
            <Button @click="previousPage()" label="Προηγούμενο" icon="pi pi-arrow-left" iconPos="left" outlined />

            <Button @click="nextPage()" label="Επόμενο" icon="pi pi-arrow-right" iconPos="right" outlined />
        </div>
    </div>
</template>
