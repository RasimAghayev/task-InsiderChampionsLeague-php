/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './components/**/*.{vue,js,ts,jsx,tsx}',
        './layouts/**/*.vue',
        './pages/**/*.vue',
        './plugins/**/*.{js,ts}',
        './nuxt.config.{js,ts}',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
    postcss: {
        plugins: {
            '@tailwindcss/postcss': {}, // Use the new package instead of 'tailwindcss'
            autoprefixer: {},
        },
    },
}