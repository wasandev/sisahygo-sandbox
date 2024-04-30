let mix = require('laravel-mix')
let tailwindcss = require("tailwindcss");

mix
  .setPublicPath('dist')
  .js('resources/js/card.js', 'js').vue()
  .sass('resources/sass/card.scss', 'css')
