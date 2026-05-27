<template>
  <div class="h-screen bg-slate-50 font-sans text-slate-900 flex overflow-hidden">
    <!-- Sidebar Navigation (Only shown when not on login page) -->
    <aside 
      v-if="isAuthenticated" 
      :class="[
        'bg-slate-900 text-white h-full shadow-xl flex flex-col shrink-0 transition-all duration-300 relative',
        isSidebarOpen ? 'w-64' : 'w-20'
      ]"
    >
      <!-- Toggle Button (Floating on the edge) -->
      <button 
        @click="toggleSidebar" 
        class="absolute -right-3 top-8 bg-emerald-500 text-white p-1 rounded-full shadow-md hover:bg-emerald-600 transition-colors z-10"
      >
        <component :is="isSidebarOpen ? ChevronLeftIcon : ChevronRightIcon" class="w-4 h-4" />
      </button>

      <!-- Logo Area -->
      <div class="p-6 border-b border-slate-800 flex items-center h-20" :class="isSidebarOpen ? 'space-x-3' : 'justify-center px-0'">
        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center font-bold text-white shrink-0">
          T
        </div>
        <h1 v-if="isSidebarOpen" class="text-xl font-bold text-white tracking-wide truncate transition-opacity duration-300">Mini Tracker</h1>
      </div>
      
      <!-- Navigation Links -->
      <nav class="flex-1 p-4 space-y-2 mt-2 overflow-y-auto overflow-x-hidden">
        <RouterLink to="/" class="flex items-center px-4 py-3 rounded-xl transition-all font-medium text-slate-300 hover:bg-slate-800 hover:text-white group" exact-active-class="!bg-primary !text-white shadow-md shadow-primary/20" :class="!isSidebarOpen && 'justify-center px-0'">
          <HomeIcon class="w-6 h-6 shrink-0" :class="isSidebarOpen ? 'mr-3' : ''" />
          <span v-if="isSidebarOpen" class="truncate transition-opacity duration-300">Dashboard</span>
          <!-- Tooltip for collapsed state -->
          <div v-if="!isSidebarOpen" class="absolute left-16 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-50">Dashboard</div>
        </RouterLink>
        
        <RouterLink to="/transactions" class="flex items-center px-4 py-3 rounded-xl transition-all font-medium text-slate-300 hover:bg-slate-800 hover:text-white group" exact-active-class="!bg-primary !text-white shadow-md shadow-primary/20" :class="!isSidebarOpen && 'justify-center px-0'">
          <ArrowsRightLeftIcon class="w-6 h-6 shrink-0" :class="isSidebarOpen ? 'mr-3' : ''" />
          <span v-if="isSidebarOpen" class="truncate transition-opacity duration-300">Transactions</span>
          <!-- Tooltip for collapsed state -->
          <div v-if="!isSidebarOpen" class="absolute left-16 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-50">Transactions</div>
        </RouterLink>
        
        <RouterLink to="/categories" class="flex items-center px-4 py-3 rounded-xl transition-all font-medium text-slate-300 hover:bg-slate-800 hover:text-white group" exact-active-class="!bg-primary !text-white shadow-md shadow-primary/20" :class="!isSidebarOpen && 'justify-center px-0'">
          <TagIcon class="w-6 h-6 shrink-0" :class="isSidebarOpen ? 'mr-3' : ''" />
          <span v-if="isSidebarOpen" class="truncate transition-opacity duration-300">Categories</span>
          <!-- Tooltip for collapsed state -->
          <div v-if="!isSidebarOpen" class="absolute left-16 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-50">Categories</div>
        </RouterLink>
      </nav>
      
      <!-- Footer Area (Profile & Logout) -->
      <div class="p-4 border-t border-slate-800">
        <RouterLink to="/profile" class="flex items-center mb-2 rounded-xl transition-all hover:bg-slate-800 group" :class="isSidebarOpen ? 'px-4 py-3' : 'px-0 py-3 justify-center'">
          <UserCircleIcon class="w-6 h-6 text-slate-400 group-hover:text-white shrink-0" :class="isSidebarOpen ? 'mr-3' : ''" />
          <div v-if="isSidebarOpen" class="overflow-hidden">
            <div class="text-sm font-medium text-slate-300 group-hover:text-white truncate">
              {{ userEmail }}
            </div>
            <div class="text-xs text-slate-500 mt-0.5">Profile Settings</div>
          </div>
          <!-- Tooltip for collapsed state -->
          <div v-if="!isSidebarOpen" class="absolute left-16 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-50">Profile</div>
        </RouterLink>

        <button @click="handleLogout" class="w-full text-left text-slate-400 hover:text-red-400 font-medium transition-all flex items-center group rounded-xl hover:bg-red-500/10" :class="isSidebarOpen ? 'px-4 py-3' : 'px-0 py-3 justify-center'">
          <ArrowRightOnRectangleIcon class="w-6 h-6 shrink-0" :class="isSidebarOpen ? 'mr-3' : ''" />
          <span v-if="isSidebarOpen" class="truncate">Logout</span>
          <!-- Tooltip for collapsed state -->
          <div v-if="!isSidebarOpen" class="absolute left-16 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-50">Logout</div>
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { RouterView, RouterLink, useRouter, useRoute } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { 
  HomeIcon, 
  ArrowsRightLeftIcon, 
  TagIcon, 
  ChevronLeftIcon, 
  ChevronRightIcon,
  UserCircleIcon,
  ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

// State for sidebar toggle
const isSidebarOpen = ref(true)

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}

// Check if we are on a protected route to show the sidebar
const isAuthenticated = computed(() => {
  return route.name !== 'login'
})

// Get user info from auth store
const userEmail = computed(() => {
  return authStore.user ? authStore.user.email : ''
})

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>
