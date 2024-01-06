<template>
    <Scatter class="scatter_graph" :data="data" :options="options" />
</template>

<script>
import { computed } from 'vue';
import {
  Chart as ChartJS,
  LinearScale,
  PointElement,
  LineElement,
  Tooltip,
  Legend
} from 'chart.js'
import { Scatter } from 'vue-chartjs'

ChartJS.register(LinearScale, PointElement, LineElement, Tooltip, Legend);

export default {
    name: 'App',
    components: {
        Scatter
    },
    props: {
        chartLabel: {
            type: Array,
            default: []
        },
        chartElemLabel: {
            type: Array,
            default: []
        },
        chartData: {
            type: Array,
            default: []
        },
        backgroundColor: {
            type: String,
            default: '#E2E2E2'
        }
    },
    setup(props) {
        const data = computed(() => {
            console.log('props.chartData')
            console.log(props.chartData)
            let datas = [];
            for(let i=0;i<props.chartData.length;i++){
                datas.push({
                    data: props.chartData[i],
                    fill: false,
                    showLine: true,
                    label: "放物運動",
                    borderColor: '#f87979',
                    backgroundColor:'#f87979',
                });
            }
            console.log('datas')
            console.log(datas)
            return {
                // labels: props.chartLabel,
                datasets: datas
            }
        });

        const options = computed(() => {
            return {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        // display: props.title ? true : false,
                        align: 'start',
                        labels: {
                            boxWidth: 0
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tootipItem) {

                                return "x移動距離：" + tootipItem.dataset.data[tootipItem.dataIndex].x　+ '　|　' + 'y移動距離：' +tootipItem.dataset.data[tootipItem.dataIndex].y;
                            },
                            footer: function(tootipItem) {
                                // console.log(tootipItem[0].dataset.data)
                                return "フッターツールチップ" + tootipItem[0];
                            }
                        }
                    }
                },
                // scales: {
                //     y: {
                //         ticks: {
                //             stepSize: 1
                //         }
                //     }
                // }
            }
        });

        return {
            data,
            options,
        }
    },
}
</script>

<style scoped>
.scatter_graph {
    margin: 40px;
}
</style>
