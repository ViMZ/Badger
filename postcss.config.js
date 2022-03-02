const autoprefixer = require('autoprefixer');
const { default: purgecssWebpackPlugin } = require('purgecss-webpack-plugin');
const tailwindcss = require('tailwindcss');

module.exports = {
  plugins: [
    tailwindcss,
    autoprefixer,
    purgecssWebpackPlugin
  ],
};