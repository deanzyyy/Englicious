/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
      extend: {
        fontFamily: {
          integral: ['"IntegralCF"', 'sans-serif'],
        },
      },
    },
    plugins: [require('tailwind-scrollbar-hide')],
  }