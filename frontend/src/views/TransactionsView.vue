<template>
  <div class="p-6 max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-slate-800">Transactions</h1>
      <button @click="openModal()" class="bg-primary hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition-colors flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New
      </button>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col lg:flex-row gap-5 items-end">
      <!-- Search -->
      <div class="flex-1 w-full lg:w-auto">
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Search</label>
        <div class="relative">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input v-model="filters.search" @keyup.enter="applyFilters" type="text" placeholder="Search transactions..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm" />
        </div>
      </div>
      
      <!-- Type Filter -->
      <div class="w-full lg:w-48">
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Type</label>
        <select v-model="filters.type" @change="applyFilters" class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm cursor-pointer">
          <option value="">All Transactions</option>
          <option value="income">Income Only</option>
          <option value="expense">Expense Only</option>
        </select>
      </div>

      <!-- Date Range -->
      <div class="w-full lg:w-auto flex gap-3">
        <div class="flex-1">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Start Date</label>
          <input v-model="filters.date_from" @change="applyFilters" type="date" class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm text-slate-600" />
        </div>
        <div class="flex-1">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">End Date</label>
          <input v-model="filters.date_to" @change="applyFilters" type="date" class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm text-slate-600" />
        </div>
      </div>

      <!-- Reset Button -->
      <button @click="resetFilters" class="w-full lg:w-auto px-5 py-2.5 bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-800 rounded-xl transition-colors font-medium flex items-center justify-center gap-2 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Reset
      </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-sm">
              <th class="p-4 font-medium">Date</th>
              <th class="p-4 font-medium">Description</th>
              <th class="p-4 font-medium">Category</th>
              <th class="p-4 font-medium text-right">Amount</th>
              <th class="p-4 font-medium text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-if="loading" class="text-center">
              <td colspan="5" class="p-8 text-slate-500">Loading transactions...</td>
            </tr>
            <tr v-else-if="transactions.length === 0" class="text-center">
              <td colspan="5" class="p-8 text-slate-500">No transactions found.</td>
            </tr>
            <tr v-for="t in transactions" :key="t.id" class="hover:bg-slate-50 transition-colors">
              <td class="p-4 text-slate-600">{{ formatDate(t.transaction_date) }}</td>
              <td class="p-4 text-slate-800">{{ t.description || '-' }}</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium gap-1.5" :style="{ backgroundColor: (t.category?.color || '#cbd5e1') + '20', color: t.category?.color || '#475569' }">
                  <component v-if="t.category?.icon" :is="getIconComponent(t.category.icon)" class="w-3.5 h-3.5" />
                  {{ t.category?.name || 'Unknown' }}
                </span>
              </td>
              <td class="p-4 text-right font-medium" :class="t.type === 'income' ? 'text-income' : 'text-expense'">
                {{ t.type === 'income' ? '+' : '-' }}{{ formatCurrency(t.amount) }}
              </td>
              <td class="p-4 text-center">
                <button @click="openModal(t)" class="text-blue-500 hover:text-blue-700 mr-3 transition-colors">Edit</button>
                <button @click="deleteTransaction(t.id)" class="text-red-500 hover:text-red-700 transition-colors">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Controls -->
      <div v-if="pagination.last_page > 1" class="p-4 border-t border-slate-100 flex justify-between items-center bg-slate-50">
        <span class="text-sm text-slate-500">
          Showing <span class="font-medium text-slate-800">{{ pagination.from || 0 }}</span> to <span class="font-medium text-slate-800">{{ pagination.to || 0 }}</span> of <span class="font-medium text-slate-800">{{ pagination.total }}</span> results
        </span>
        <div class="flex gap-2">
          <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="px-3 py-1 rounded-md border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
          
          <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="px-3 py-1 rounded-md border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
        </div>
      </div>
    </div>

    <!-- Modal Pop-up -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
          <h3 class="text-lg font-bold text-slate-800">{{ isEditing ? 'Edit Transaction' : 'Add New Transaction' }}</h3>
          <button @click="closeModal" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
        </div>
        
        <form @submit.prevent="saveTransaction" class="p-6 space-y-4">
          <!-- Type -->
          <div class="flex gap-4">
            <label class="flex-1 cursor-pointer">
              <input type="radio" v-model="form.type" value="expense" class="peer sr-only" @change="handleTypeChange" />
              <div class="text-center p-3 rounded-lg border border-slate-200 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-colors font-medium">Expense</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" v-model="form.type" value="income" class="peer sr-only" @change="handleTypeChange" />
              <div class="text-center p-3 rounded-lg border border-slate-200 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition-colors font-medium">Income</div>
            </label>
          </div>

          <!-- Amount -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Amount</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">฿</span>
              <input v-model.number="form.amount" type="number" step="0.01" min="0.01" required
                class="w-full pl-8 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="0.00" />
            </div>
          </div>

          <!-- Category -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
            <select v-model="form.category_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
              <option value="" disabled>Select a category</option>
              <option v-for="cat in filteredCategories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>

          <!-- Date -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
            <input v-model="form.transaction_date" type="date" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Description (Optional)</label>
            <input v-model="form.description" type="text" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="e.g. Lunch with team" />
          </div>

          <!-- Actions -->
          <div class="pt-4 flex gap-3">
            <button type="button" @click="closeModal" class="flex-1 px-4 py-2 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 font-medium transition-colors">Cancel</button>
            <button type="submit" :disabled="saving" class="flex-1 px-4 py-2 bg-primary hover:bg-emerald-700 text-white rounded-lg font-medium flex justify-center items-center transition-colors">
              <span v-if="saving">Saving...</span>
              <span v-else>Save</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { toast } from 'vue3-toastify'
import api from '../lib/api'
import { useCategoryStore } from '../stores/category'
import { getIconComponent } from '../lib/icons'

const categoryStore = useCategoryStore()

const transactions = ref([])
const loading = ref(true)
const saving = ref(false)
const isModalOpen = ref(false)
const isEditing = ref(false)

const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0,
  from: 0,
  to: 0
})

const filters = reactive({
  search: '',
  type: '',
  date_from: '',
  date_to: ''
})

const form = reactive({
  id: null,
  type: 'expense',
  amount: '',
  category_id: '',
  transaction_date: new Date().toISOString().split('T')[0],
  description: ''
})

const filteredCategories = computed(() => {
  return categoryStore.categories.filter(cat => cat.type === form.type)
})

const handleTypeChange = () => {
  form.category_id = ''
}

const applyFilters = () => {
  pagination.value.current_page = 1 // reset to first page on search
  fetchTransactions()
}

const resetFilters = () => {
  filters.search = ''
  filters.type = ''
  filters.date_from = ''
  filters.date_to = ''
  applyFilters()
}

const changePage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    pagination.value.current_page = page
    fetchTransactions()
  }
}

const fetchTransactions = async () => {
  loading.value = true
  try {
    const res = await api.get('/transactions', {
      params: {
        page: pagination.value.current_page,
        search: filters.search,
        type: filters.type,
        date_from: filters.date_from,
        date_to: filters.date_to
      }
    })
    
    transactions.value = res.data.data
    pagination.value.current_page = res.data.current_page
    pagination.value.last_page = res.data.last_page
    pagination.value.total = res.data.total
    pagination.value.from = res.data.from
    pagination.value.to = res.data.to
  } catch (error) {
    console.error('Failed to fetch transactions', error)
    toast.error('Failed to load transactions')
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  fetchTransactions()
  await categoryStore.fetchCategories()
})

const openModal = (transaction = null) => {
  if (transaction) {
    isEditing.value = true
    form.id = transaction.id
    form.type = transaction.type
    form.amount = transaction.amount
    form.category_id = transaction.category_id
    form.transaction_date = transaction.transaction_date
    form.description = transaction.description || ''
  } else {
    isEditing.value = false
    form.id = null
    form.type = 'expense'
    form.amount = ''
    form.category_id = ''
    form.transaction_date = new Date().toISOString().split('T')[0]
    form.description = ''
  }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const saveTransaction = async () => {
  saving.value = true
  try {
    const payload = {
      type: form.type,
      amount: form.amount,
      category_id: form.category_id,
      transaction_date: form.transaction_date,
      description: form.description
    }

    if (isEditing.value) {
      await api.put(`/transactions/${form.id}`, payload)
      toast.success('Transaction updated successfully!')
    } else {
      await api.post('/transactions', payload)
      toast.success('Transaction added successfully!')
    }
    
    closeModal()
    await fetchTransactions()
  } catch (error) {
    console.error('Failed to save transaction', error)
    toast.error(error.response?.data?.message || 'Failed to save transaction.')
  } finally {
    saving.value = false
  }
}

const deleteTransaction = async (id) => {
  if (!confirm('Are you sure you want to delete this transaction?')) return
  
  try {
    await api.delete(`/transactions/${id}`)
    toast.info('Transaction deleted.')
    await fetchTransactions() // Refresh page to handle pagination correctly
  } catch (error) {
    console.error('Failed to delete transaction', error)
    toast.error('Failed to delete transaction.')
  }
}

const formatCurrency = (value) => {
  return Number(value).toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' }
  return new Date(dateString).toLocaleDateString('th-TH', options)
}
</script>
