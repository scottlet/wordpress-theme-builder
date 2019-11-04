'use strict';

const browserify = require('browserify');
const CONSTS = require('./CONSTS');
const merge2 = require('merge2');
const glob = require('glob');
const gulp = require('gulp');
const gulpIf = require('gulp-if');
const gulpLivereload = require('gulp-livereload');
const gulpNotify = require('gulp-notify');
const gulpPlumber = require('gulp-plumber');
const gulpSourcemaps = require('gulp-sourcemaps');
const fancyLog = require('fancy-log');
const vinylBuffer = require('vinyl-buffer');
const vinylSourceStream = require('vinyl-source-stream');
const watchify = require('watchify');

const isDev = CONSTS.NODE_ENV !== 'production';

const entries = glob.sync(CONSTS.JS_SRC + '*.js');

function addToBrowserify(entry) {
    const options = {
        entries:  [entry],
        cache: {},
        packageCache: {},
        paths: [
            `./${CONSTS.JS_SRC}modules`
        ]
    };

    const name = entry.replace('$name', CONSTS.NAME).replace('$version', CONSTS.VERSION)
        .replace(/.*\/([\w$\-.]+).js/, '$1');

    const b = browserify(options);

    if (isDev) {
        b.transform('babelify', { presets: ['@babel/preset-env'], sourceMaps: true });
        b.plugin(watchify, { delay: 10 });
    } else {
        b.transform('babelify', { presets: ['@babel/preset-env'] });
        b.plugin('tinyify', { flat: false });
    }

    function doLR() {
        if (process.env.OVERRIDE_LR === 'true') {
            return false;
        }

        return isDev;
    }

    function bundle() {
        return b.bundle()
            .pipe(gulpPlumber({errorHandler: gulpNotify.onError(error => `JS Bundle Error: ${error.message}`)}))
            .pipe(vinylSourceStream(name + CONSTS.JS_OUTPUT))
            .pipe(vinylBuffer())
            .pipe(gulpSourcemaps.init({loadMaps: true}))
            .pipe(gulpIf(isDev, gulpSourcemaps.write()))
            .pipe(gulp.dest(CONSTS.JS_DEST))
            .pipe(gulpIf(doLR(), gulpLivereload({
                port: CONSTS.LIVERELOAD_PORT
            })));
    }

    b.on('update', bundle);
    b.on('log', fancyLog);
    b.on('error', fancyLog);

    return bundle();
}

function createJSbundles() {
    const tasks = entries.map(addToBrowserify);

    return merge2(tasks);
}

module.exports = createJSbundles;
