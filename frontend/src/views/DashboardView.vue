<template>
  <div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Dashboard</h1>
      
      <!-- Date Filter -->
      <div class="flex items-center space-x-3 bg-white dark:bg-slate-800 p-2 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700/50 transition-colors duration-300">
        <select v-model="selectedMonth" @change="fetchData" class="bg-transparent text-sm font-medium text-slate-700 dark:text-slate-300 focus:outline-none cursor-pointer border-r border-slate-200 dark:border-slate-700 pr-3">
          <option v-for="(month, index) in months" :key="index" :value="index + 1" class="bg-white dark:bg-slate-800">{{ month }}</option>
        </select>
        <select v-model="selectedYear" @change="fetchData" class="bg-transparent text-sm font-medium text-slate-700 dark:text-slate-300 focus:outline-none cursor-pointer pl-1">
          <option v-for="year in years" :key="year" :value="year" class="bg-white dark:bg-slate-800">{{ year }}</option>
        </select>
      </div>
    </div>

    <!-- CONTENT SECTION (Shows when data is loaded) -->
    <template v-if="!loading">
      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Balance Card -->
      <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-3xl shadow-sm border border-blue-100 dark:border-slate-700/50 p-6 flex items-center justify-between transition-all hover:-translate-y-1 hover:shadow-md hover:shadow-blue-500/10 dark:hover:shadow-black/20 duration-300 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute -right-4 -bottom-4 w-32 h-32 text-blue-500 opacity-5 dark:opacity-10 group-hover:scale-110 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
        <div class="relative z-10">
          <p class="text-sm font-semibold text-blue-600/80 dark:text-blue-400/80 mb-1 uppercase tracking-wider">Balance</p>
          <p class="text-3xl font-bold text-slate-800 dark:text-white">฿{{ formatCurrency(data.summary.balance) }}</p>
        </div>
        <div class="relative z-10 w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/40">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
        </div>
      </div>

      <!-- Income Card -->
      <div class="relative overflow-hidden bg-gradient-to-br from-emerald-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-3xl shadow-sm border border-emerald-100 dark:border-slate-700/50 p-6 flex items-center justify-between transition-all hover:-translate-y-1 hover:shadow-md hover:shadow-emerald-500/10 dark:hover:shadow-black/20 duration-300 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute -right-4 -bottom-4 w-32 h-32 text-emerald-500 opacity-5 dark:opacity-10 group-hover:scale-110 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
        <div class="relative z-10">
          <p class="text-sm font-semibold text-emerald-600/80 dark:text-emerald-400/80 mb-1 uppercase tracking-wider">Total Income</p>
          <p class="text-3xl font-bold text-slate-800 dark:text-white">฿{{ formatCurrency(data.summary.income) }}</p>
        </div>
        <div class="relative z-10 w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/40">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
        </div>
      </div>

      <!-- Expense Card -->
      <div class="relative overflow-hidden bg-gradient-to-br from-rose-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-3xl shadow-sm border border-rose-100 dark:border-slate-700/50 p-6 flex items-center justify-between transition-all hover:-translate-y-1 hover:shadow-md hover:shadow-rose-500/10 dark:hover:shadow-black/20 duration-300 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute -right-4 -bottom-4 w-32 h-32 text-rose-500 opacity-5 dark:opacity-10 group-hover:scale-110 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
        <div class="relative z-10">
          <p class="text-sm font-semibold text-rose-600/80 dark:text-rose-400/80 mb-1 uppercase tracking-wider">Total Expense</p>
          <p class="text-3xl font-bold text-slate-800 dark:text-white">฿{{ formatCurrency(data.summary.expense) }}</p>
        </div>
        <div class="relative z-10 w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-white shadow-lg shadow-rose-500/40">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
        </div>
      </div>
      </div>
      <!-- Budget Progress Bar -->
      <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 flex flex-col justify-center transition-colors duration-300">
        <div class="flex justify-between items-center mb-2">
          <div class="flex items-center space-x-2">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Monthly Budget</h2>
            <button @click="openBudgetModal" class="p-1.5 text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg transition-colors" title="Edit Budget">
              <!-- SVG Icon, no emojis -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
            </button>
          </div>
          <span class="text-sm font-medium text-slate-500 dark:text-slate-400">
            ฿{{ formatCurrency(data.summary.expense) }} / <span class="font-bold text-slate-700 dark:text-slate-300">฿{{ formatCurrency(data.summary.monthly_budget) }}</span>
          </span>
        </div>
        
        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-4 mb-2 overflow-hidden shadow-inner">
          <div class="h-4 rounded-full transition-all duration-1000 ease-out" :class="budgetColorClass" :style="{ width: budgetPercentage + '%' }"></div>
        </div>
        <div class="flex justify-between text-xs text-slate-400 dark:text-slate-500 font-medium">
          <span>0%</span>
          <span :class="{'text-rose-500': budgetPercentage > 90, 'text-amber-500': budgetPercentage >= 75 && budgetPercentage <= 90}">{{ budgetPercentage }}% Used</span>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Bar Chart -->
      <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 transition-colors duration-300">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Income vs Expense (6 Months)</h3>
        <div class="h-64 relative">
          <Bar :data="barChartData" :options="barChartOptions" />
        </div>
      </div>
      
      <!-- Doughnut Chart -->
      <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 flex flex-col transition-colors duration-300">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Expense Breakdown</h3>
        <div v-if="data.expense_by_category.length > 0" class="flex-1 flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8 mt-2">
          <!-- Chart Container -->
          <div class="relative w-48 h-48 md:w-56 md:h-56 flex-shrink-0">
            <Doughnut :data="pieChartData" :options="pieChartOptions" />
            <!-- Absolute centered text for Doughnut inner hole -->
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
              <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Total</span>
              <span class="text-lg md:text-xl font-bold text-slate-800 dark:text-white leading-none mt-1">฿{{ formatCurrency(data.summary.expense) }}</span>
            </div>
          </div>
          <!-- Custom HTML Legend -->
          <div class="w-full md:w-auto flex flex-col justify-center space-y-2.5">
            <div v-for="(cat, index) in data.expense_by_category" :key="index" class="flex items-center group cursor-default">
              <div class="w-3.5 h-3.5 rounded-full mr-3 shadow-sm transition-transform group-hover:scale-125" :style="{ backgroundColor: cat.color || '#94a3b8' }"></div>
              <div class="text-sm font-medium text-slate-600 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors" :title="cat.name">{{ cat.name }}</div>
            </div>
          </div>
        </div>
        <div v-else class="h-64 flex items-center justify-center text-slate-400">
          No expenses recorded this month.
        </div>
      </div>
      </div>

      <!-- Recent Transactions Table -->
      <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden transition-colors duration-300">
      <div class="p-6 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Recent Transactions</h3>
        <RouterLink to="/transactions" class="text-sm font-medium text-primary hover:underline">View All</RouterLink>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            <tr v-if="data.latest_transactions.length === 0">
              <td colspan="4" class="p-16">
                <div class="flex flex-col items-center justify-center text-center space-y-4 animate-pulse">
                  <div class="w-20 h-20 bg-slate-50 dark:bg-slate-700/30 rounded-3xl flex items-center justify-center text-slate-300 dark:text-slate-500 transform -rotate-6 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                  </div>
                  <div>
                    <h4 class="text-slate-700 dark:text-slate-300 font-semibold text-lg">No transactions yet</h4>
                    <p class="text-slate-400 dark:text-slate-500 text-sm mt-1 max-w-xs">Your recent activity will appear here once you start tracking your finances.</p>
                  </div>
                  <RouterLink to="/transactions" class="inline-flex items-center justify-center px-4 py-2 mt-2 bg-primary/10 dark:bg-primary/20 text-primary hover:bg-primary hover:text-white rounded-xl font-medium text-sm transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add Transaction
                  </RouterLink>
                </div>
              </td>
            </tr>
            <tr v-for="t in data.latest_transactions" :key="t.id" class="group hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all duration-300 border-l-2 border-transparent hover:border-primary">
              <td class="p-4 text-slate-500 dark:text-slate-400 text-sm whitespace-nowrap group-hover:pl-5 transition-all duration-300">{{ formatDate(t.transaction_date) }}</td>
              <td class="p-4 text-slate-800 dark:text-slate-200 font-medium group-hover:text-primary dark:group-hover:text-primary transition-colors duration-300">{{ t.description || t.category?.name || 'Unknown' }}</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium gap-1.5 transition-transform duration-300 group-hover:scale-105" :style="{ backgroundColor: (t.category?.color || '#cbd5e1') + (isDarkMode ? '20' : '15'), color: t.category?.color || (isDarkMode ? '#94a3b8' : '#475569') }">
                  <component v-if="t.category?.icon" :is="getIconComponent(t.category.icon)" class="w-3.5 h-3.5" />
                  {{ t.category?.name || 'Unknown' }}
                </span>
              </td>
              <td class="p-4 text-right font-bold transition-transform duration-300 group-hover:-translate-x-1" :class="t.type === 'income' ? 'text-income' : 'text-expense'">
                {{ t.type === 'income' ? '+' : '-' }}฿{{ formatCurrency(t.amount) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      </div>
    </template>

    <!-- LOADING SKELETON SECTION -->
    <template v-else>
      <!-- Cards Skeleton -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="i in 3" :key="'card'+i" class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 flex items-center justify-between animate-pulse">
          <div class="space-y-4">
            <div class="h-3 w-20 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
            <div class="h-8 w-32 bg-slate-100 dark:bg-slate-700/50 rounded-xl"></div>
          </div>
          <div class="w-14 h-14 rounded-2xl bg-slate-50 dark:bg-slate-700 border border-slate-100 dark:border-slate-600"></div>
        </div>
      </div>

      <!-- Charts Skeleton -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Bar Chart Skeleton -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 animate-pulse">
          <div class="h-5 w-48 bg-slate-200 dark:bg-slate-700 rounded-lg mb-8"></div>
          <div class="h-56 flex items-end space-x-4 pt-4">
            <div class="w-full bg-slate-100/80 dark:bg-slate-700/50 rounded-t-md h-[40%]"></div>
            <div class="w-full bg-slate-100/80 dark:bg-slate-700/50 rounded-t-md h-[70%]"></div>
            <div class="w-full bg-slate-100/80 dark:bg-slate-700/50 rounded-t-md h-[45%]"></div>
            <div class="w-full bg-slate-100/80 dark:bg-slate-700/50 rounded-t-md h-[90%]"></div>
            <div class="w-full bg-slate-100/80 dark:bg-slate-700/50 rounded-t-md h-[60%]"></div>
            <div class="w-full bg-slate-100/80 dark:bg-slate-700/50 rounded-t-md h-[30%]"></div>
          </div>
        </div>
        <!-- Doughnut Chart Skeleton -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 animate-pulse flex flex-col">
          <div class="h-5 w-48 bg-slate-200 dark:bg-slate-700 rounded-lg mb-8"></div>
          <div class="flex-1 flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8">
            <div class="w-48 h-48 md:w-56 md:h-56 rounded-full border-[28px] md:border-[36px] border-slate-50 dark:border-slate-700/30 flex-shrink-0"></div>
            <div class="w-full md:w-auto flex flex-col justify-center space-y-4">
              <div v-for="k in 4" :key="'pie'+k" class="flex items-center">
                <div class="w-3.5 h-3.5 rounded-full bg-slate-200 dark:bg-slate-600 mr-3"></div>
                <div class="h-3 w-20 bg-slate-100 dark:bg-slate-700 rounded-full"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Table Skeleton -->
      <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden mt-6 animate-pulse">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
          <div class="h-5 w-40 bg-slate-200 dark:bg-slate-700 rounded-lg"></div>
          <div class="h-4 w-16 bg-slate-100 dark:bg-slate-700/50 rounded-full"></div>
        </div>
        <div class="p-2">
          <div v-for="l in 4" :key="'row'+l" class="flex justify-between items-center p-4 border-b border-slate-50 dark:border-slate-700/30 last:border-0">
            <div class="h-3 w-20 bg-slate-100 dark:bg-slate-700/50 rounded-full"></div>
            <div class="h-4 w-32 bg-slate-200 dark:bg-slate-600 rounded-full flex-1 mx-8"></div>
            <div class="h-6 w-24 bg-slate-50 dark:bg-slate-700 rounded-full"></div>
            <div class="h-4 w-16 bg-slate-200 dark:bg-slate-600 rounded-full ml-8"></div>
          </div>
        </div>
      </div>
    </template>

    <!-- Edit Budget Modal -->
    <div v-if="isBudgetModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isBudgetModalOpen = false"></div>
      
      <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-sm relative z-10 overflow-hidden border border-slate-100 dark:border-slate-700">
        <!-- Header -->
        <div class="bg-slate-50 dark:bg-slate-800/80 px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
          <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Set Monthly Budget
          </h3>
          <button @click="isBudgetModalOpen = false" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
          </button>
        </div>

        <!-- Body -->
        <div class="p-6">
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Target Spending Limit</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <span class="text-slate-500 dark:text-slate-400">฿</span>
            </div>
            <input type="number" v-model="editBudgetAmount" min="0" step="100" class="pl-8 block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="e.g. 15000">
          </div>
          <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Set this to help you track your spending limit for the month.</p>
        </div>

        <!-- Footer -->
        <div class="bg-slate-50 dark:bg-slate-800/80 px-6 py-4 flex justify-end space-x-3 border-t border-slate-100 dark:border-slate-700">
          <button @click="isBudgetModalOpen = false" type="button" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Cancel
          </button>
          <button @click="saveBudget" type="button" :disabled="budgetSaving" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 flex items-center">
            <svg v-if="budgetSaving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            Save Budget
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import api from '../lib/api'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js'
import { Bar, Doughnut } from 'vue-chartjs'
import { getIconComponent } from '../lib/icons'
import { useTheme } from '../composables/useTheme'

// Register ChartJS components
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, ArcElement)

const { isDarkMode } = useTheme()
const loading = ref(true)
const data = ref({
  summary: { income: 0, expense: 0, balance: 0, monthly_budget: 0 },
  expense_by_category: [],
  monthly_trend: [],
  latest_transactions: []
})

const now = new Date()
const selectedMonth = ref(now.getMonth() + 1)
const selectedYear = ref(now.getFullYear())

const months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
]
const years = Array.from({ length: 5 }, (_, i) => now.getFullYear() - i)

const fetchData = async () => {
  loading.value = true
  try {
    const res = await api.get('/dashboard/summary', {
      params: {
        month: selectedMonth.value,
        year: selectedYear.value
      }
    })
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

const isBudgetModalOpen = ref(false)
const editBudgetAmount = ref(0)
const budgetSaving = ref(false)

const budgetPercentage = computed(() => {
  const budget = Number(data.value.summary.monthly_budget) || 0
  const expense = Number(data.value.summary.expense) || 0
  if (budget <= 0) return 0
  const pct = (expense / budget) * 100
  return Math.min(Math.round(pct), 100)
})

const budgetColorClass = computed(() => {
  const pct = budgetPercentage.value
  if (pct < 75) return 'bg-emerald-500'
  if (pct < 90) return 'bg-amber-500'
  return 'bg-rose-500'
})

const openBudgetModal = () => {
  editBudgetAmount.value = Number(data.value.summary.monthly_budget) || 0
  isBudgetModalOpen.value = true
}

const saveBudget = async () => {
  budgetSaving.value = true
  try {
    const res = await api.put('/profile/budget', {
      monthly_budget: editBudgetAmount.value
    })
    data.value.summary.monthly_budget = res.data.monthly_budget
    isBudgetModalOpen.value = false
    toast.success('Budget updated successfully!')
  } catch (error) {
    console.error('Failed to update budget', error)
    toast.error('Failed to update budget')
  } finally {
    budgetSaving.value = false
  }
}

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
        borderRadius: 6,
        borderSkipped: false,
        barPercentage: 0.6,
        data: trend.map(t => t.income)
      },
      {
        label: 'Expense',
        backgroundColor: '#EF4444',
        borderRadius: 6,
        borderSkipped: false,
        barPercentage: 0.6,
        data: trend.map(t => t.expense)
      }
    ]
  }
})

const barChartOptions = computed(() => {
  const textColor = isDarkMode.value ? '#cbd5e1' : '#64748b' // slate-300 : slate-500
  const gridColor = isDarkMode.value ? '#334155' : '#f8fafc' // slate-700 : slate-50

  return {
    responsive: true,
    maintainAspectRatio: false,
    color: textColor,
    plugins: {
      legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, padding: 20, color: textColor } },
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: { color: gridColor, drawBorder: false },
        border: { display: false },
        ticks: { color: textColor }
      },
      x: {
        grid: { display: false },
        border: { display: false },
        ticks: { color: textColor }
      }
    }
  }
})

// Pie Chart Config
const pieChartData = computed(() => {
  const expCat = data.value.expense_by_category
  return {
    labels: expCat.map(c => c.name),
    datasets: [
      {
        backgroundColor: expCat.map(c => c.color || '#94a3b8'),
        borderWidth: 2,
        borderColor: isDarkMode.value ? '#1e293b' : '#ffffff',
        hoverOffset: 4,
        data: expCat.map(c => c.total)
      }
    ]
  }
})

const pieChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  cutout: '75%',
  layout: { padding: 0 },
  plugins: {
    legend: { display: false },
    tooltip: {
      padding: 12,
      cornerRadius: 8,
    }
  }
}
</script>
