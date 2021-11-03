const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .js('resources/js/jquery-min.js', 'public/js')
    .js('resources/js/vue.js', 'public/js')
    .js('resources/js/openlayers.js', 'public/js')
    .js('resources/js/filepond.js', 'public/js')
    .js('resources/js/bootstrap-min.js', 'public/js')
    .js('resources/js/navigation.js', 'public/js')
    .js('resources/js/functions.js', 'public/js')
    .js('resources/js/popper-min.js', 'public/js')
    .js('resources/js/jquery-ui.js', 'public/js')
    .js('resources/js/frontpage.js', 'public/js')
    .copyDirectory('resources/fonts', 'public/fonts')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/frontpage.scss', 'public/css')
    .sass('resources/sass/navigation.scss', 'public/css')
    .sass('resources/sass/main.scss', 'public/css')
    .sass('resources/sass/footer.scss', 'public/css')
    .sourceMaps();
