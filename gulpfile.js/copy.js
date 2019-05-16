'use strict';

const CONSTS = require('./CONSTS');
const {src, dest, series, parallel} = require('gulp');
const gulpChanged = require('gulp-changed');
const gulpIf = require('gulp-if');
const gulpLivereload = require('gulp-livereload');
const gulpRename = require('gulp-rename');
const gulpReplace = require('gulp-replace');

const STATIC_SRC = [
    CONSTS.IMG_SRC + '/**',
    CONSTS.AUDIO_SRC + '/**',
    CONSTS.FONT_SRC + '/**',
    CONSTS.LANGUAGES_SRC + '/**',
    CONSTS.VIDEO_SRC + '/**'
];

const TEMPLATES_SRC = [
    CONSTS.TEMPLATES_SRC + '/**'
];
const BITS_SRC = [
    CONSTS.IMG_SRC + '/screenshot.png',
    CONSTS.TEXT_SRC + '/**'

];

function copyWordPress() {
    return copyFilesFn(CONSTS.WP_SRC + '/**', CONSTS.RUN_DEST, CONSTS.WP_SRC, true);
}

function copyViews() {
    return copyFilesReplaceFn(
        TEMPLATES_SRC,
        CONSTS.TEMPLATES_DEST,
        CONSTS.TEMPLATES_SRC,
        true,
        {
            name: '@@@name@@@',
            value: CONSTS.NAME
        }
    );
}

function copyStaticFiles() {
    return copyFilesFn(STATIC_SRC, CONSTS.STATIC_DEST, CONSTS.SRC, true);
}

function copyFilesFn(gsrc, gdest, base, reload) {
    return src(gsrc, {base: base || '.'})
        .pipe(gulpChanged(gdest))
        .pipe(dest(gdest))
        .pipe(gulpIf(reload, gulpLivereload({
            port: CONSTS.LIVERELOAD_PORT
        })));
}

function copyFilesReplaceFn(gsrc, gdest, base, reload, rp) {
    return src(gsrc, {base: base || '.'})
        .pipe(gulpReplace(rp.name, rp.value))
        .pipe(gulpChanged(gdest))
        .pipe(dest(gdest))
        .pipe(gulpIf(reload, gulpLivereload({
            port: CONSTS.LIVERELOAD_PORT
        })));
}

function getDateTime() {
    return new Date().toLocaleString('en', {
        weekday: 'short',
        day: 'numeric',
        year: 'numeric',
        month: 'short',
        hour: 'numeric',
        minute: 'numeric'
    });
}

function copyDeploy() {
    return copyFilesFn(CONSTS.STATIC_DEST + '**', CONSTS.BUILD_DEST, CONSTS.STATIC_DEST);
}

function copyCss() {
    return src(CONSTS.CSS_SRC + '/style.css')
        .pipe(gulpReplace('$version', CONSTS.VERSION))
        .pipe(gulpReplace('$name', CONSTS.FULL_NAME))
        .pipe(gulpReplace('$datetime', getDateTime()))
        .pipe(dest(CONSTS.STATIC_DEST));
}

function copyCssLR() {
    return src(CONSTS.CSS_SRC + '/style.css')
        .pipe(gulpReplace('$version', CONSTS.VERSION))
        .pipe(gulpReplace('$name', CONSTS.FULL_NAME))
        .pipe(gulpReplace('$datetime', getDateTime()))
        .pipe(dest(CONSTS.STATIC_DEST))
        .pipe(gulpLivereload({
            port: CONSTS.LIVERELOAD_PORT
        }));
}

function copyBits() {
    return src(BITS_SRC,
        {base: '.'})
        .pipe(gulpRename({dirname:''}))
        .pipe(dest(CONSTS.STATIC_DEST));
}

function copyFavicon() {
    return src(CONSTS.FAVICON,
        {base: CONSTS.IMG_SRC})
        .pipe(dest(CONSTS.CONTENT));
}

function copyConfig() {
    return copyFilesFn(CONSTS.WPCONFIG_SRC, CONSTS.RUN_DEST, CONSTS.SRC, true);
}

// gulp.task('copystaticfiles', copyStaticFiles);
// gulp.task('copybits', copyBits);
// gulp.task('copycss', copyCss);
// gulp.task('copycss-lr', copyCssLR);
// gulp.task('copyfavicon', copyFavicon);
// gulp.task('copywp', copyWordPress);
// gulp.task('copyconfig', copyConfig);

module.exports = {
    copy: series(
        parallel(
            copyConfig,
            copyCss,
            copyFavicon,
            copyStaticFiles,
            copyViews,
            copyWordPress
        ),
        copyBits
    ),
    copyDeploy,
    copyViews,
    copyCssLR,
    copyConfig,
    copyStaticFiles
};
