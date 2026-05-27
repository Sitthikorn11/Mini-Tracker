/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#059669',
        'income': '#10B981',
        'expense': '#EF4444'
      }
    },
  },
  plugins: [],
}

