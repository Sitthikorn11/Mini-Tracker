<template>
  <div class="p-6 max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-slate-800">Categories</h1>
      <button @click="openModal()" class="bg-primary hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition-colors flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Category
      </button>
    </div>

    <div v-if="categoryStore.loading" class="text-center p-8 text-slate-500">
      Loading categories...
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Income Categories -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
        <div class="bg-emerald-50 border-b border-emerald-100 p-4">
          <h2 class="font-semibold text-emerald-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            Income Categories
          </h2>
        </div>
        <div class="p-2">
          <div v-for="cat in incomeCategories" :key="cat.id" class="flex justify-between items-center p-3 hover:bg-slate-50 rounded-lg group transition-colors">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full flex items-center justify-center text-white shadow-sm" :style="{ backgroundColor: cat.color || '#10B981' }">
                <component :is="getIconComponent(cat.icon)" class="w-5 h-5" v-if="cat.icon && getIconComponent(cat.icon)" />
                <span v-else class="text-sm font-bold">{{ cat.name.charAt(0) }}</span>
              </div>
              <span class="text-slate-700 font-medium">{{ cat.name }}</span>
            </div>
            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
              <button @click="openModal(cat)" class="text-blue-500 hover:text-blue-700 p-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
              <button @click="deleteCategory(cat.id)" class="text-red-500 hover:text-red-700 p-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
            </div>
          </div>
          <div v-if="incomeCategories.length === 0" class="text-center p-4 text-sm text-slate-400">
            No income categories yet.
          </div>
        </div>
      </div>

      <!-- Expense Categories -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
        <div class="bg-red-50 border-b border-red-100 p-4">
          <h2 class="font-semibold text-red-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
            </svg>
            Expense Categories
          </h2>
        </div>
        <div class="p-2">
          <div v-for="cat in expenseCategories" :key="cat.id" class="flex justify-between items-center p-3 hover:bg-slate-50 rounded-lg group transition-colors">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full flex items-center justify-center text-white shadow-sm" :style="{ backgroundColor: cat.color || '#EF4444' }">
                <component :is="getIconComponent(cat.icon)" class="w-5 h-5" v-if="cat.icon && getIconComponent(cat.icon)" />
                <span v-else class="text-sm font-bold">{{ cat.name.charAt(0) }}</span>
              </div>
              <span class="text-slate-700 font-medium">{{ cat.name }}</span>
            </div>
            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
              <button @click="openModal(cat)" class="text-blue-500 hover:text-blue-700 p-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
              <button @click="deleteCategory(cat.id)" class="text-red-500 hover:text-red-700 p-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
            </div>
          </div>
          <div v-if="expenseCategories.length === 0" class="text-center p-4 text-sm text-slate-400">
            No expense categories yet.
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Pop-up -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200 flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
          <h3 class="text-lg font-bold text-slate-800">{{ isEditing ? 'Edit Category' : 'Add New Category' }}</h3>
          <button @click="closeModal" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
        </div>
        
        <form @submit.prevent="saveCategory" class="p-6 space-y-5 overflow-y-auto flex-1">
          <!-- Type -->
          <div class="flex gap-4">
            <label class="flex-1 cursor-pointer">
              <input type="radio" v-model="form.type" value="expense" class="peer sr-only" />
              <div class="text-center p-3 rounded-xl border-2 border-slate-100 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-all font-semibold">Expense</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" v-model="form.type" value="income" class="peer sr-only" />
              <div class="text-center p-3 rounded-xl border-2 border-slate-100 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition-all font-semibold">Income</div>
            </label>
          </div>

          <!-- Name -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Category Name</label>
            <input v-model="form.name" type="text" required
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all shadow-sm" 
              placeholder="e.g. Salary, Food, Utilities" />
          </div>

          <!-- Icon Picker -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Select Icon</label>
            <div class="grid grid-cols-5 sm:grid-cols-6 gap-3">
              <button 
                v-for="(iconComponent, iconName) in availableIcons" 
                :key="iconName"
                type="button"
                @click="form.icon = iconName"
                :class="[
                  'p-3 rounded-xl flex items-center justify-center transition-all border-2',
                  form.icon === iconName ? 'bg-primary/10 border-primary text-primary' : 'bg-slate-50 border-slate-100 text-slate-500 hover:bg-slate-100'
                ]"
              >
                <component :is="iconComponent" class="w-6 h-6" />
              </button>
            </div>
          </div>

          <!-- Color Picker -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Category Color</label>
            <div class="flex items-center gap-3">
              <input v-model="form.color" type="color" 
                class="h-12 w-20 rounded-lg cursor-pointer border border-slate-200 p-1 bg-white shadow-sm" />
              <span class="text-sm text-slate-500 font-mono uppercase bg-slate-100 px-3 py-1.5 rounded-lg">{{ form.color }}</span>
            </div>
          </div>

        </form>
        
        <!-- Actions -->
        <div class="p-6 border-t border-slate-100 bg-slate-50 flex gap-3 shrink-0">
          <button type="button" @click="closeModal" class="flex-1 px-4 py-3 border border-slate-200 bg-white text-slate-600 rounded-xl hover:bg-slate-50 font-medium transition-colors shadow-sm">Cancel</button>
          <button type="button" @click="saveCategory" :disabled="saving" class="flex-1 px-4 py-3 bg-primary hover:bg-emerald-700 text-white rounded-xl font-medium flex justify-center items-center transition-colors shadow-md">
            <span v-if="saving">Saving...</span>
            <span v-else>Save Category</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { toast } from 'vue3-toastify'
import api from '../lib/api'
import { useCategoryStore } from '../stores/category'
import { availableIcons, getIconComponent } from '../lib/icons'

const categoryStore = useCategoryStore()

const saving = ref(false)
const isModalOpen = ref(false)
const isEditing = ref(false)

const form = reactive({
  id: null,
  type: 'expense',
  name: '',
  color: '#3b82f6', // Default blue
  icon: 'BanknotesIcon'
})

const incomeCategories = computed(() => {
  return categoryStore.categories.filter(c => c.type === 'income')
})

const expenseCategories = computed(() => {
  return categoryStore.categories.filter(c => c.type === 'expense')
})

onMounted(async () => {
  await categoryStore.fetchCategories()
})

const openModal = (category = null) => {
  if (category) {
    isEditing.value = true
    form.id = category.id
    form.type = category.type
    form.name = category.name
    form.color = category.color || '#3b82f6'
    form.icon = category.icon || 'BanknotesIcon'
  } else {
    isEditing.value = false
    form.id = null
    form.type = 'expense'
    form.name = ''
    form.color = '#ef4444'
    form.icon = 'CakeIcon'
  }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const saveCategory = async () => {
  saving.value = true
  try {
    const payload = {
      type: form.type,
      name: form.name,
      color: form.color,
      icon: form.icon
    }

    if (isEditing.value) {
      await api.put(`/categories/${form.id}`, payload)
      toast.success('Category updated successfully!')
    } else {
      await api.post('/categories', payload)
      toast.success('Category added successfully!')
    }
    
    closeModal()
    // Force refresh the Pinia cache
    await categoryStore.fetchCategories(true)
  } catch (error) {
    console.error('Failed to save category', error)
    toast.error(error.response?.data?.message || 'Failed to save category.')
  } finally {
    saving.value = false
  }
}

const deleteCategory = async (id) => {
  if (!confirm('Are you sure you want to delete this category? (Transactions linked to this might lose their category reference)')) return
  
  try {
    await api.delete(`/categories/${id}`)
    toast.info('Category deleted.')
    // Force refresh the Pinia cache
    await categoryStore.fetchCategories(true)
  } catch (error) {
    console.error('Failed to delete category', error)
    toast.error('Failed to delete category.')
  }
}
</script>
