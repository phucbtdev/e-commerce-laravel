/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    'node_modules/preline/dist/*.js',
  ],
  plugins: [
    require('preline/plugin'),
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

