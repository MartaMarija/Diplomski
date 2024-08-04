let mix = require("laravel-mix");

mix.js("src/public/js/index.js", "public/static/js")
    .sass("src/public/sass/index.sass", "public/static/css")
    .options({
        processCssUrls: false,
    }).webpackConfig({
        devtool: 'source-map'
    })

// browsersync watch for files included below and proxy setup
mix.browserSync({
    files: ["src/public/**/*"],
    injectChanges: true,
    proxy: "http://localhost:8091",
    port: 3000,
});
