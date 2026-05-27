<template>
  <div class="min-h-screen flex bg-white">
    <!-- Left Panel: Hero Graphic (Hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 bg-primary relative overflow-hidden items-center justify-center p-12">
      <!-- Decorative background elements -->
      <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
      <div class="absolute bottom-[-10%] left-[-10%] w-96 h-96 bg-teal-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
      
      <div class="relative z-10 w-full max-w-lg">
        <h1 class="text-5xl font-bold text-white mb-6 leading-tight">Master your money with <span class="text-emerald-200">Mini Tracker</span>.</h1>
        <p class="text-emerald-50 text-xl leading-relaxed mb-12">The simplest, most beautiful way to take control of your personal finances, track your expenses, and reach your goals.</p>
        
        <!-- Decorative Glassmorphism Mockup -->
        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-6 shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-500">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </div>
              <div>
                <div class="text-white font-medium">Total Balance</div>
                <div class="text-white/70 text-sm">Updated just now</div>
              </div>
            </div>
            <div class="text-white text-2xl font-bold">฿124,500</div>
          </div>
          <div class="space-y-3">
            <div class="h-2 w-full bg-white/20 rounded-full overflow-hidden">
              <div class="h-full bg-emerald-400 w-3/4"></div>
            </div>
            <div class="flex justify-between text-xs text-white/70">
              <span>Income</span>
              <span>75% of goal</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Panel: Login Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 sm:p-12 lg:p-24 bg-white relative items-center">
      <div class="w-full max-w-sm animate-slide-up">
        <!-- Logo / Icon -->
        <div class="flex justify-center mb-8">
          <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-primary rotate-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
        </div>

        <div class="text-center mb-10">
          <h2 class="text-3xl font-bold text-slate-800 tracking-tight">{{ isLogin ? 'Welcome back' : 'Create an account' }}</h2>
          <p class="text-slate-500 mt-2">{{ isLogin ? 'Please enter your details to sign in.' : 'Start tracking your finances today.' }}</p>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-6">
          
          <!-- Name Field (Register Only) -->
          <div v-if="!isLogin" class="space-y-1 relative">
            <label class="block text-sm font-medium text-slate-700">Full Name</label>
            <div class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
              <input v-model="form.name" type="text" required
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm"
                placeholder="John Doe" />
            </div>
          </div>

          <!-- Email Field -->
          <div class="space-y-1">
            <label class="block text-sm font-medium text-slate-700">Email Address</label>
            <div class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
              <input v-model="form.email" type="email" required
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm"
                placeholder="you@example.com" />
            </div>
          </div>

          <!-- Password Field -->
          <div class="space-y-1">
            <div class="flex items-center justify-between">
              <label class="block text-sm font-medium text-slate-700">Password</label>
              <a v-if="isLogin" href="#" class="text-xs font-semibold text-primary hover:underline">Forgot password?</a>
            </div>
            <div class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
              <input v-model="form.password" type="password" required
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm"
                placeholder="••••••••" />
            </div>
          </div>

          <!-- Confirm Password (Register Only) -->
          <div v-if="!isLogin" class="space-y-1">
            <label class="block text-sm font-medium text-slate-700">Confirm Password</label>
            <div class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
              <input v-model="form.password_confirmation" type="password" required
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm"
                placeholder="••••••••" />
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" :disabled="loading"
            class="w-full bg-primary hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 flex justify-center items-center mt-6 group">
            <span v-if="loading">Processing...</span>
            <span v-else class="flex items-center">
              {{ isLogin ? 'Sign In' : 'Create Account' }}
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </span>
          </button>
        </form>

        <!-- Toggle Mode -->
        <div class="mt-8 text-center text-sm text-slate-500">
          {{ isLogin ? "Don't have an account?" : "Already have an account?" }}
          <button @click="toggleMode" class="text-primary font-semibold hover:underline ml-1">
            {{ isLogin ? 'Sign up for free' : 'Sign in here' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'
import api from '../lib/api'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const isLogin = ref(true)
const loading = ref(false)

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const toggleMode = () => {
  isLogin.value = !isLogin.value
  form.name = ''
  form.password = ''
  form.password_confirmation = ''
}

const handleSubmit = async () => {
  loading.value = true

  try {
    // 1. Initialize CSRF protection
    await authStore.initializeCsrf()

    // 2. Perform Login/Register
    const endpoint = isLogin.value ? '/login' : '/register'
    const payload = isLogin.value 
      ? { email: form.email, password: form.password }
      : form
      
    const response = await api.post(endpoint, payload)
    
    // 3. Save user state (Token is handled automatically by HttpOnly Cookie)
    if (response.data.user) {
      authStore.setAuth(response.data.user)
      
      toast.success(isLogin.value ? 'Welcome back!' : 'Account created successfully!')
      
      // Redirect to dashboard
      router.push('/')
    }
  } catch (error) {
    let msg = 'An unexpected error occurred. Please try again.'
    if (error.response?.data?.errors) {
      // Laravel validation errors
      const errors = error.response.data.errors
      msg = Object.values(errors).flat()[0]
    } else if (error.response?.data?.message) {
      msg = error.response.data.message
    }
    toast.error(msg)
  } finally {
    loading.value = false
  }
}
</script>

<style>
/* Custom animations */
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
@keyframes slideUpFade {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
.animate-slide-up {
  animation: slideUpFade 0.7s ease-out forwards;
}
</style>
