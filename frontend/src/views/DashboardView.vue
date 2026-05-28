<template>
  <div class="p-6 max-w-7xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>

    <!-- Summary Cards -->
    <div v-if="!loading" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
        <div>
          <p class="text-sm font-medium text-slate-500 mb-1">Balance</p>
          <p class="text-3xl font-bold text-slate-800">฿{{ formatCurrency(data.summary.balance) }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
        <div>
          <p class="text-sm font-medium text-slate-500 mb-1">Total Income</p>
          <p class="text-3xl font-bold text-income">฿{{ formatCurrency(data.summary.income) }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
        <div>
          <p class="text-sm font-medium text-slate-500 mb-1">Total Expense</p>
          <p class="text-3xl font-bold text-expense">฿{{ formatCurrency(data.summary.expense) }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
        </div>
      </div>
    </div>
    
    <!-- Skeleton loader for cards -->
    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-pulse">
      <div class="bg-white rounded-2xl border border-slate-100 h-28"></div>
      <div class="bg-white rounded-2xl border border-slate-100 h-28"></div>
      <div class="bg-white rounded-2xl border border-slate-100 h-28"></div>
    </div>

    <!-- Charts Section -->
    <div v-if="!loading" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Bar Chart -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Income vs Expense (6 Months)</h3>
        <div class="h-64 relative">
          <Bar :data="barChartData" :options="barChartOptions" />
        </div>
      </div>
      
      <!-- Pie Chart -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Expense Breakdown</h3>
        <div v-if="data.expense_by_category.length > 0" class="h-64 relative flex justify-center">
          <Pie :data="pieChartData" :options="pieChartOptions" />
        </div>
        <div v-else class="h-64 flex items-center justify-center text-slate-400">
          No expenses recorded this month.
        </div>
      </div>
    </div>

    <!-- Recent Transactions Table -->
    <div v-if="!loading" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
      <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-800">Recent Transactions</h3>
        <RouterLink to="/transactions" class="text-sm font-medium text-primary hover:underline">View All</RouterLink>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <tbody class="divide-y divide-slate-100">
            <tr v-if="data.latest_transactions.length === 0" class="text-center">
              <td colspan="4" class="p-8 text-slate-500">No recent transactions.</td>
            </tr>
            <tr v-for="t in data.latest_transactions" :key="t.id" class="hover:bg-slate-50 transition-colors">
              <td class="p-4 text-slate-500 text-sm whitespace-nowrap">{{ formatDate(t.transaction_date) }}</td>
              <td class="p-4 text-slate-800 font-medium">{{ t.description || t.category?.name || 'Unknown' }}</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium gap-1.5" :style="{ backgroundColor: (t.category?.color || '#cbd5e1') + '20', color: t.category?.color || '#475569' }">
                  <component v-if="t.category?.icon" :is="getIconComponent(t.category.icon)" class="w-3.5 h-3.5" />
                  {{ t.category?.name || 'Unknown' }}
                </span>
              </td>
              <td class="p-4 text-right font-bold" :class="t.type === 'income' ? 'text-income' : 'text-expense'">
                {{ t.type === 'income' ? '+' : '-' }}฿{{ formatCurrency(t.amount) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import api from '../lib/api'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js'
import { Bar, Pie } from 'vue-chartjs'
import { getIconComponent } from '../lib/icons'

// Register ChartJS components
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, ArcElement)

const loading = ref(true)
const data = ref({
  summary: { income: 0, expense: 0, balance: 0 },
  expense_by_category: [],
  monthly_trend: [],
  latest_transactions: []
})

const fetchData = async () => {
  try {
    const res = await api.get('/dashboard/summary')
    data.value = res.data
  } catch (error) {
    console.error('Failed to fetch dashboard data', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchData()
})

const formatCurrency = (value) => {
  return Number(value).toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' }
  return new Date(dateString).toLocaleDateString('th-TH', options)
}

// Bar Chart Config
const barChartData = computed(() => {
  const trend = data.value.monthly_trend
  return {
    labels: trend.map(t => t.month_label),
    datasets: [
      {
        label: 'Income',
        backgroundColor: '#10B981',
        borderRadius: 4,
        data: trend.map(t => t.income)
      },
      {
        label: 'Expense',
        backgroundColor: '#EF4444',
        borderRadius: 4,
        data: trend.map(t => t.expense)
      }
    ]
  }
})

const barChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { position: 'top' },
  },
  scales: {
    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
    x: { grid: { display: false } }
  }
}

// Pie Chart Config
const pieChartData = computed(() => {
  const expCat = data.value.expense_by_category
  return {
    labels: expCat.map(c => c.name),
    datasets: [
      {
        backgroundColor: expCat.map(c => c.color || '#94a3b8'),
        borderWidth: 2,
        borderColor: '#ffffff',
        hoverOffset: 4,
        data: expCat.map(c => c.total)
      }
    ]
  }
})

const pieChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { position: 'right' }
  }
}
</script>
