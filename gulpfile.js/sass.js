'use strict';

const autoprefixer = require('autoprefixer');
const cssMqpacker = require('css-mqpacker');
const csswring = require('csswring');
const gulp = require('gulp');
const gulpIf = require('gulp-if');
const gulpLivereload = require('gulp-livereload');
const gulpNotify = require('gulp-notify');
const gulpPlumber = require('gulp-plumber');
const gulpPostcss = require('gulp-postcss');
const gulpRename = require('gulp-rename');
const gulpSassVariables = require('gulp-sass-variables');
const gulpSass = require('gulp-sass');
const gulpSourcemaps = require('gulp-sourcemaps');
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

function rename(path) {
    path.basename = path.basename.replace('$name', CONSTS.NAME).replace('$version', CONSTS.VERSION) + '.min';
}

function styles() {
    const processors = [
        autoprefixer({browsers: CONSTS.BROWSER_CONFIG}),
        cssMqpacker,
        csswring,
        postcssAssets
    ];

    return gulp.src(CONSTS.SASS_SRC + '/**/*.scss')
        .pipe(gulpPlumber({errorHandler: gulpNotify.onError(error => `Styles Error: ${error.message}`)}))
        .pipe(gulpIf(isDev, gulpSourcemaps.init()))
        .pipe(gulpSassVariables({
            $oldmob: `${CONSTS.BREAKPOINTS.OLD_MOBILE}px`,
            $mob: `${CONSTS.BREAKPOINTS.MOBILE}px`,
            $smalltablet: `${CONSTS.BREAKPOINTS.SMALL_TABLET}px`,
            $tablet: `${CONSTS.BREAKPOINTS.TABLET}px`,
            $smalldesktop: `${CONSTS.BREAKPOINTS.SMALL_DESKTOP}px`
        }))
        .pipe(gulpSass(sassOptions).on('error', gulpSass.logError))
        .pipe(gulpPostcss(processors))
        .pipe(gulpIf(isDev, gulpSourcemaps.write()))
        .pipe(gulpRename(rename))
        .pipe(gulp.dest(CONSTS.CSS_DEST))
        .pipe(gulpIf(isDev, gulpLivereload({port: CONSTS.LIVERELOAD_PORT})));
}

module.exports = styles;
