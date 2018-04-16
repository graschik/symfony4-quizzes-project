var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('js/app', './assets/js/app.js')
    .addEntry('js/questions', './assets/js/questions.js')
    .addEntry('js/quizzes', './assets/js/quizzes.js')
    .addEntry('js/games', './assets/js/games.js')

    .addStyleEntry('css/app', './assets/css/app.scss')
    .addStyleEntry('css/style', './assets/css/style.css')
    .addStyleEntry('css/forms', './assets/css/forms.css')
    .addStyleEntry('css/navbar', './assets/css/navbar.css')
    .addStyleEntry('css/footer', './assets/css/footer.css')
    .addStyleEntry('css/questions', './assets/css/questions.css')
    .addStyleEntry('css/quizzes', './assets/css/quizzes.css')
    .addStyleEntry('css/games', './assets/css/games.css')


    // uncomment if you use Sass/SCSS files
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: false
    })

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // show OS notifications when builds finish/fail
    .enableBuildNotifications()

// create hashed filenames (e.g. app.abc123.css)
// .enableVersioning()
;

module.exports = Encore.getWebpackConfig();
