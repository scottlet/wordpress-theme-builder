'use strict';

const autoprefixer = require('autoprefixer');
const cssMqpacker = require('css-mqpacker');
const csswring = require('csswring');
const {src, dest} = require('gulp');
const gulpIf = require('gulp-if');
const gulpLivereload = require('gulp-livereload');
const {onError} = require('gulp-notify');
const gulpPlumber = require('gulp-plumber');
const gulpPostcss = require('gulp-postcss');
const gulpRename = require('gulp-rename');
const gulpSassVariables = require('gulp-sass-variables');
const gulpSass = require('gulp-sass');
const nodeNormalizeScss = require('node-normalize-scss').includePaths;
const postcssAssets = require('postcss-assets');
const CONSTS = require('./CONSTS');

const isDev = CONSTS.NODE_ENV !== 'production';

const sassOptions = {
    errLogToConsole: true,
    includePaths: [
        nodeNormalizeScss
    ]
};

const gulpOptions = isDev ? {
    sourcemaps: true
} : {};

gulpSass.compiler = require('node-sass');

function buildSassVariables(breakpoints) {
    var b;
    var c = {};

    for (b in breakpoints) {
        c['$' + b.toLowerCase().replace(/_/g, '')] = breakpoints[b] + 'px';
    }

    return c;
}

const sassVariables = buildSassVariables(CONSTS.BREAKPOINTS);

function rename(path) {
    path.basename = path.basename.replace('$name', CONSTS.NAME).replace('$version', CONSTS.VERSION) + '.min';
}

gulpSass.compiler = require('node-sass');

function styles() {
    const processors = [
        autoprefixer(),
        cssMqpacker,
        csswring,
        postcssAssets
    ];

    return src(CONSTS.SASS_SRC + '/**/*.scss', gulpOptions)
        .pipe(gulpPlumber({errorHandler: onError(error => `Styles Error: ${error.message}`)}))
        .pipe(gulpSassVariables(sassVariables))
        .pipe(gulpSass(sassOptions).on('error', gulpSass.logError))
        .pipe(gulpPostcss(processors))
        .pipe(gulpRename(rename))
        .pipe(dest(CONSTS.CSS_DEST, gulpOptions))
        .pipe(gulpIf(isDev, gulpLivereload({port: CONSTS.LIVERELOAD_PORT})));
}

module.exports = styles;
