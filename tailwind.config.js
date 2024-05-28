/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "node_modules/preline/dist/*.js",
    "./node_modules/flowbite/**/*.js"
  ],
  plugins: [
    require('preline/plugin'),
    require('flowbite/plugin')
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

