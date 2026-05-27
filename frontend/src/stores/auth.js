import { defineStore } from 'pinia'
import { ref } from 'vue'
import api, { csrfApi } from '../lib/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(JSON.parse(localStorage.getItem('user') || 'null'))
  // Token state is completely removed. We rely on the HttpOnly cookie.

  const setAuth = (userData) => {
    user.value = userData
    localStorage.setItem('user', JSON.stringify(userData))
  }

  const clearAuth = () => {
    user.value = null
    localStorage.removeItem('user')
    // Remove old token if it exists from previous version
    localStorage.removeItem('auth_token') 
  }

  const initializeCsrf = async () => {
    // Fetch the CSRF cookie before making login/register requests
    await csrfApi.get('/sanctum/csrf-cookie')
  }

  const logout = async () => {
    try {
      await api.post('/logout')
    } catch (e) {
      console.error('Logout failed on server, but clearing local state.', e)
    } finally {
      clearAuth()
    }
  }

  return { user, setAuth, clearAuth, initializeCsrf, logout }
})
