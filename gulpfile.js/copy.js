'use strict';

const CONSTS = require('./CONSTS');
const gulp = require('gulp');
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

function copyFilesFn(src, dest, base, reload) {
    return gulp.src(src, {base: base || '.'})
        .pipe(gulpChanged(dest))
        .pipe(gulp.dest(dest))
        .pipe(gulpIf(reload, gulpLivereload({
            port: CONSTS.LIVERELOAD_PORT
        })));
}

function copyFilesReplaceFn(src, dest, base, reload, rp) {
    return gulp.src(src, {base: base || '.'})
        .pipe(gulpReplace(rp.name, rp.value))
        .pipe(gulpChanged(dest))
        .pipe(gulp.dest(dest))
        .pipe(gulpIf(reload, gulpLivereload({
            port: CONSTS.LIVERELOAD_PORT
        })));
}

function getDateTime() {
    return new Date().toString()
        .replace(/ [GUTMC].*?$/gi, '');
}

function copyDeploy() {
    return copyFilesFn(CONSTS.STATIC_DEST + '**', CONSTS.BUILD_DEST, CONSTS.STATIC_DEST);
}

function copyCss() {
    return gulp.src(CONSTS.CSS_SRC + '/style.css')
        .pipe(gulpReplace('$version', CONSTS.VERSION))
        .pipe(gulpReplace('$name', CONSTS.FULL_NAME))
        .pipe(gulpReplace('$datetime', getDateTime()))
        .pipe(gulp.dest(CONSTS.STATIC_DEST));
}

function copyCssLR() {
    return gulp.src(CONSTS.CSS_SRC + '/style.css')
        .pipe(gulpReplace('$version', CONSTS.VERSION))
        .pipe(gulpReplace('$name', CONSTS.FULL_NAME))
        .pipe(gulpReplace('$datetime', getDateTime()))
        .pipe(gulp.dest(CONSTS.STATIC_DEST))
        .pipe(gulpLivereload({
            port: CONSTS.LIVERELOAD_PORT
        }));
}

function copyBits() {
    return gulp.src(BITS_SRC,
        {base: '.'})
        .pipe(gulpRename({dirname:''}))
        .pipe(gulp.dest(CONSTS.STATIC_DEST));
}

function copyFavicon() {
    return gulp.src(CONSTS.FAVICON,
        {base: CONSTS.IMG_SRC})
        .pipe(gulp.dest(CONSTS.CONTENT));
}

function copyConfig() {
    return copyFilesFn(CONSTS.WPCONFIG_SRC, CONSTS.RUN_DEST, CONSTS.SRC, true);
}

gulp.task('copystaticfiles', copyStaticFiles);
gulp.task('copybits', copyBits);
gulp.task('copycss', copyCss);
gulp.task('copycss-lr', copyCssLR);
gulp.task('copyfavicon', copyFavicon);
gulp.task('copywp', copyWordPress);
gulp.task('copyconfig', copyConfig);

module.exports = {
    copy: gulp.series(
        gulp.parallel(
            copyConfig,
            copyCss,
            copyFavicon,
            copyStaticFiles,
            copyViews,
            copyWordPress
        ),
        copyBits
    ),
    copyDeploy: copyDeploy,
    copyViews: copyViews
};
