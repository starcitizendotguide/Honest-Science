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
   .copyDirectory('resources/assets/fonts', 'public/assets/fonts')
   .js('resources/assets/js/app.js', 'public/assets/js')
   .sass('resources/assets/sass/app.scss', 'public/assets/css', {
       implementation: require('node-sass')
    })
   .sass('resources/assets/sass/manage_app.scss', 'public/assets/css', {
       implementation: require('node-sass')
   })
   .options({
	terser: {
	    terserOptions: {
                warnings: true
            }
    	}
    })
    .sourceMaps();
