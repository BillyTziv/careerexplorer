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
            default: () => []
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

    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    const randomColors = props.data.map(() => getRandomColor());

    const chartData = {
        labels: props.labels,
        datasets: [
            {
                label: props.label,
                data: props.data,
                backgroundColor: randomColors.map(color => transparentize(color, 0.5)),

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
                position: 'bottom'
            }
        }
    };
</script>


<template>
    <div class="card">
        <div class="flex align-items-start justify-content-between mb-6">
            <span class="text-900 text-xl font-semibold">
                {{ title }}
            </span>
        </div>
      
        <Chart
            type="pie"
            :height="300"
            :data="chartData"
            :options="chartOptions"
        />
    </div>
</template>
