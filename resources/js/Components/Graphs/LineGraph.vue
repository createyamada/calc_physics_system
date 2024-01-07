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
import { Scatter } from 'vue-chartjs';
import Const from "@/Utils/Const";

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
        }
    },
    setup(props) {
        const data = computed(() => {
            let colors = Const.GRAPH_COLORS;
            let datas = [];
            for(let i=0;i<props.chartData.length;i++){
                // 色８色ループ
                let index = i % 8;
                datas.push({
                    data: props.chartData[i],
                    fill: true,
                    showLine: true,
                    label: "放物運動",
                    borderColor: colors[index],
                    backgroundColor: colors[index],
                });
            }

            return {
                datasets: datas
            }
        });

        const options = computed(() => {
            return {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        align: 'start',
                        labels: {
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tootipItem) {
                                return "x移動距離：" + tootipItem.dataset.data[tootipItem.dataIndex].x　+ '　|　' + 'y移動距離：' +tootipItem.dataset.data[tootipItem.dataIndex].y;
                            },
                            footer: function(tootipItem) {
                                // return "フッターツールチップ" + tootipItem[0];
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
