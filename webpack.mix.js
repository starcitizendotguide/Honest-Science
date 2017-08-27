let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.copyDirectory('resources/assets/images', 'public/assets/images')
   .js('resources/assets/js/app.js', 'public/assets/js')
   .sass('resources/assets/sass/app.scss', 'public/assets/css')
   .sass('resources/assets/sass/manage_app.scss', 'public/assets/css')
   .options({
        processCssUrls: true
    })
    .sourceMaps();
