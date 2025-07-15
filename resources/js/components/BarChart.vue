<template>
    <Bar
      id="myChart"
      :options="chartOptions"
      :data="chartData"
    />
  </template>

  <script>
  import { Bar } from 'vue-chartjs'
  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale,Filler  } from 'chart.js'

  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale,Filler)

  export default {
    name: 'BarChart',
    components: { Bar },
    data() {
    return {
        chartData: {
        labels: [ 'January', 'February', 'March' ],
        datasets: [ { data: [40, 20, 12] } ]
        },
        chartOptions: {
        responsive: true
        }
    }
    }
  }
  </script>
