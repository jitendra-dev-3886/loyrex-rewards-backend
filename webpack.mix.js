const mix = require("laravel-mix");
let File = require("./node_modules/laravel-mix/src/File");
const webpack = require("webpack");
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

mix.js("resources/js/app.js", "public/js").sass(
    "resources/sass/app.scss",
    "public/css"
);

mix.copyDirectory("resources/assets/images", "public/images");
mix.copyDirectory("resources/assets/media", "public/media");
mix.copyDirectory("resources/assets/samples", "public/samples");

if (mix.inProduction()) {
    // mix.minify("public/js/app.js");
    // mix.minify("public/css/app.css");
    mix.version();
    const ASSET_URL = `${process.env.ASSET_URL}/`;

    mix.webpackConfig(webPack => ({
        plugins: [
            new webPack.DefinePlugin({
                "process.env.ASSET_PATH": JSON.stringify(ASSET_URL)
            })
        ],
        output: {
            publicPath: ASSET_URL
        }
    }));
}

mix.webpackConfig({
    output: {
        chunkFilename: "js/[name].[chunkhash].js"
    },
    module: {
        /**
         * rule added to handle download csv
         */
        rules: [
            {
                test: /\.(csv|xlsx|xls)$/,
                loader: "file-loader",
                options: {
                    name: "csv/[name].[ext]"
                }
            }
        ]
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        })
    ]
});

mix.imgCDN = function(path, cdn) {
    var fs = require("fs");
    const file = new File(path);

    // Replace all occurrences of /images/ with CDN URL prepended
    const contents = file.read().replace(/\/images\//g, `${cdn}/images/`);
    file.write(contents);

    // Replace all occurrences of /media/ with CDN URL prepended
    const mediaContents = file.read().replace(/\/media\//g, `${cdn}/media/`);
    file.write(mediaContents);

    const contents1 = file.read().replace(/\/fonts\//g, `${cdn}/fonts/`);
    file.write(contents1);

    // Update version hash in manifest
    Mix.manifest.hash(file.pathFromPublic()).refresh();

    return this;
}.bind(mix);

mix.then(() => {
    const cdn = process.env.ASSET_URL;
    console.log("cdn : " + cdn);
    if (cdn !== undefined) {
        console.log("cdn again : " + cdn);
        mix.imgCDN("public/css/app.css", cdn);
        const currentJsOutputDir = "public/js/";
        // eslint-disable-next-line global-require
        const fs = require("fs");
        // Function to get current filenames in directory with "withFileTypes" set to "true"
        fileObjs = fs.readdirSync(currentJsOutputDir, { withFileTypes: true });
        fileObjs.forEach(file => {
            mix.imgCDN(currentJsOutputDir + file.name, cdn);
        });
    }
});
