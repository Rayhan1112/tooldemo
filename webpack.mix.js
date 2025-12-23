const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

// Copy necessary libraries for PDF generation
mix.copy('node_modules/jspdf/dist/jspdf.umd.min.js', 'public/js/jspdf.umd.min.js')
   .copy('node_modules/html2canvas/dist/html2canvas.min.js', 'public/js/html2canvas.min.js');
