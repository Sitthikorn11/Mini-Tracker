<template>
  <div class="p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-3 mb-6">
      <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Profile Settings</h1>
    </div>

    <!-- Personal Information Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden transition-colors duration-300">
      <div class="p-6 border-b border-slate-100 dark:border-slate-700/50">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Personal Information</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Update your account name. Email cannot be changed.</p>
      </div>
      
      <form @submit.prevent="updateInfo" class="p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
            <input v-model="infoForm.name" type="text" required
              class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-800 dark:text-white" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email Address</label>
            <input v-model="infoForm.email" type="email" disabled
              class="w-full px-4 py-2 bg-slate-100 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-700/50 text-slate-500 dark:text-slate-400 rounded-lg cursor-not-allowed" />
          </div>
        </div>
        
        <div class="pt-4 flex justify-end">
          <button type="submit" :disabled="infoLoading" class="px-6 py-2 bg-primary hover:bg-emerald-700 text-white rounded-lg font-medium shadow-sm transition-colors flex items-center">
            <span v-if="infoLoading">Saving...</span>
            <span v-else>Save Changes</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Security (Password) Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden transition-colors duration-300">
      <div class="p-6 border-b border-slate-100 dark:border-slate-700/50">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Security</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Update your password to keep your account secure.</p>
      </div>
      
      <form @submit.prevent="updatePassword" class="p-6 space-y-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Current Password</label>
          <input v-model="passwordForm.current_password" type="password" required
            class="w-full md:w-1/2 px-4 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-800 dark:text-white" />
        </div>
        
        <div class="border-t border-slate-100 dark:border-slate-700/50 my-4"></div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">New Password</label>
            <input v-model="passwordForm.new_password" type="password" required minlength="8"
              class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-800 dark:text-white" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Confirm New Password</label>
            <input v-model="passwordForm.new_password_confirmation" type="password" required minlength="8"
              class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-800 dark:text-white" />
          </div>
        </div>
        
        <div class="pt-4 flex justify-end">
          <button type="submit" :disabled="passwordLoading" class="px-6 py-2 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 text-white rounded-lg font-medium shadow-sm transition-colors flex items-center">
            <span v-if="passwordLoading">Updating...</span>
            <span v-else>Update Password</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { toast } from 'vue3-toastify'
import api from '../lib/api'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()

const infoLoading = ref(false)
const passwordLoading = ref(false)

const infoForm = reactive({
  name: '',
  email: ''
})

const passwordForm = reactive({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

onMounted(() => {
  if (authStore.user) {
    infoForm.name = authStore.user.name
    infoForm.email = authStore.user.email
  }
})

const updateInfo = async () => {
  infoLoading.value = true
  try {
    const res = await api.put('/profile/info', { name: infoForm.name })
    // Update local store
    authStore.setAuth(res.data)
    toast.success('Profile updated successfully!')
  } catch (error) {
    console.error(error)
    toast.error(error.response?.data?.message || 'Failed to update profile.')
  } finally {
    infoLoading.value = false
  }
}

const updatePassword = async () => {
  if (passwordForm.new_password !== passwordForm.new_password_confirmation) {
    toast.error('New passwords do not match.')
    return
  }
  
  passwordLoading.value = true
  try {
    await api.put('/profile/password', passwordForm)
    toast.success('Password updated successfully!')
    
    // Clear form
    passwordForm.current_password = ''
    passwordForm.new_password = ''
    passwordForm.new_password_confirmation = ''
  } catch (error) {
    console.error(error)
    if (error.response?.data?.errors?.current_password) {
      toast.error(error.response.data.errors.current_password[0])
    } else {
      toast.error(error.response?.data?.message || 'Failed to update password.')
    }
  } finally {
    passwordLoading.value = false
  }
}
</script>
