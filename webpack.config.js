const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path

    .copyFiles({
        from: './assets/images',

        // optional target path, relative to the output dir
        to: 'images/[path][name].[ext]',

        // if versioning is enabled, add the file hash too
        //to: 'images/[path][name].[hash:8].[ext]',

        // only copy files matching this pattern
        //pattern: /\.(png|jpg|jpeg)$/
    })

    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

        /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')
    .addEntry('calcul-prix', './assets/js/calcul-prix.js')
    .addEntry('commande-barrette', './assets/js/commande-barrette.js')
    .addEntry('commande-cacheplaque', './assets/js/commande-cacheplaque.js')
    // .addEntry('commande', './assets/js/commande.js') pour le script de commande.js qui gère barrette + cache plaque
    .addEntry('panier', './assets/js/panier.js')
    .addEntry('produits', './assets/js/produits.js')
    .addEntry('creation', './assets/js/creation.js')
    .addEntry('bibliotheque', './assets/js/bibliotheque.js')


    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    .configureDevServerOptions(options => {
        options.host = 'localhost';
        options.port = 8080;
        options.allowedHosts = 'all'; // <-- important
        options.headers = {
            'Access-Control-Allow-Origin': '*',
        };
        options.static = {
            watch: false,
        };
        options.liveReload = true;
        options.watchFiles = {
            paths: ['templates/**/*.twig', 'src/**/*.php'],
        };
    })

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()

    // Displays build status system notifications to the user
    // .enableBuildNotifications()

    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
