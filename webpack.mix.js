const mix = require("laravel-mix");
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Wordpress plugins. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({ stats: { children: false } })
	.js("resources/js/backend.js", "assets/js")
	// .js("resources/js/metabox.js", "assets/js")
	.postCss("resources/css/backend.css", "assets/css", [
		require("postcss-import"),
		require("tailwindcss"),
		require("autoprefixer"),
	])
	// .postCss("resources/backend/css/metabox.css", "backend/css", [
	// 	require("postcss-import"),
	// 	require("tailwindcss"),
	// 	require("autoprefixer"),
	// ])
	.react()
	.sourceMaps(false, "source-map")
	.disableSuccessNotifications();
