import { ref, watch } from 'vue'

const canUseDOM = typeof window !== 'undefined' && typeof document !== 'undefined'
const canUseStorage = canUseDOM && typeof window.localStorage !== 'undefined'

const getStoredTheme = () => {
  if (!canUseStorage) return false

  try {
    return window.localStorage.getItem('darkMode') === 'true'
  } catch {
    return false
  }
}

const isDarkMode = ref(getStoredTheme())

// Apply initial class
if (canUseDOM) {
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

// Watch for changes and update DOM + localStorage
watch(isDarkMode, (val) => {
  if (canUseStorage) {
    try {
      window.localStorage.setItem('darkMode', String(val))
    } catch {
      // Ignore storage failures so theme toggling still works for this session.
    }
  }

  if (!canUseDOM) return

  if (val) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
})

export function useTheme() {
  const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value
  }

  return {
    isDarkMode,
    toggleDarkMode
  }
}
