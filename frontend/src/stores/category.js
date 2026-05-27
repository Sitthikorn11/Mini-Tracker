import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../lib/api'

export const useCategoryStore = defineStore('category', () => {
  const categories = ref([])
  const loading = ref(false)
  const fetchedOnce = ref(false)

  const fetchCategories = async (forceRefresh = false) => {
    if (fetchedOnce.value && !forceRefresh) {
      return categories.value
    }
    
    loading.value = true
    try {
      const res = await api.get('/categories')
      categories.value = res.data
      fetchedOnce.value = true
      return categories.value
    } catch (e) {
      console.error('Failed to fetch categories:', e)
      return []
    } finally {
      loading.value = false
    }
  }

  const clearCache = () => {
    categories.value = []
    fetchedOnce.value = false
  }

  return { categories, loading, fetchedOnce, fetchCategories, clearCache }
})
