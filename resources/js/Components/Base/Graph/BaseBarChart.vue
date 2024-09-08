<script setup>
    import { ref, watch } from 'vue';
    
    const props = defineProps({
        title: {
            type: String,
            required: true
        }
    });
    
    // Refs for bar chart data and options
    const barData = ref({});
    const barOptions = ref({});
    const documentStyle = getComputedStyle(document.documentElement);
    
    // Dynamic theme colors
    const textColor = documentStyle.getPropertyValue('--text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');
    
    
    
    // Bar chart options
    barOptions.value = {
        animation: {
            duration: 0
        },
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: textColor,
                    usePointStyle: true,
                    font: {
                        weight: 700
                    },
                    padding: 28
                },
                position: 'bottom'
            }
        },
        scales: {
            x: {
                ticks: {
                color: textColorSecondary,
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
                color: textColorSecondary
                },
                grid: {
                color: surfaceBorder,
                drawBorder: false
                }
            }
        }
    };

</script>


<template>
    <div class="card ">
        <div class="flex align-items-start justify-content-between mb-6">
            <span class="text-900 text-xl font-semibold">
                {{ title }}
            </span>
        </div>

        <Chart
            type="bar"
            :height="300"
            :data="barData"
            :options="barOptions"
        />
    </div>
</template>
