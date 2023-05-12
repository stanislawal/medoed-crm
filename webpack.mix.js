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


//---------------------JS-----------------
// app js
mix.js('resources/js/app.js', 'public/js');
//select2 (plugin) js
mix.js('resources/js/select2.js', 'public/js');
//project js
mix.js('resources/js/project/project.js', 'public/js');
// article js
mix.js('resources/js/article/article.js', 'public/js');
// payment.js
mix.js('resources/js/payment/payment.js', 'public/js');


//---------------------CSS-----------------
// app css
mix.css('resources/css/app.css', 'public/css');
// auth css
mix.css('resources/css/auth.css', 'public/css');
