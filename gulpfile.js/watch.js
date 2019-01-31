'use strict';

const {series, watch} = require('gulp');
const gulpLivereload = require('gulp-livereload');
const fancyLog = require('fancy-log');
const CONSTS = require('./CONSTS');

function watchers(cb) {
    gulpLivereload.listen({
        port: CONSTS.LIVERELOAD_PORT
    });
    const watchCopiedTemplates = watch(CONSTS.TEMPLATES_DEST + '/**/*.php', series(gulpLivereload.reload));
    const watchPublic = watch(
        [
            CONSTS.IMG_SRC + '/**/*',
            CONSTS.FONT_SRC + '/**/*',
            CONSTS.AUDIO_SRC + '/**/*',
            CONSTS.VIDEO_SRC + '/**/*'
        ], series('copystaticfiles'));
    const watchSass = watch(CONSTS.SASS_SRC + '/**/*', series('sass-watch'));
    const watchCss = watch(CONSTS.WP_CSS_SRC, series('copycss-lr'));
    const watchConfig = watch(CONSTS.WPCONFIG_SRC, series('copyconfig'));
    const watchTemplates = watch(CONSTS.TEMPLATES_SRC + '/**/*', series('copyviews'));
    const watchJs = watch([CONSTS.GULPFILE, CONSTS.GULP_TASKS + '/**/*.js', CONSTS.JS_SRC + '/**/*.js'],
        series('eslint-lr'));

    [
        watchCopiedTemplates,
        watchPublic,
        watchJs,
        watchSass,
        watchCss,
        watchConfig,
        watchTemplates
    ].forEach((w) => {
        w.on('change', (path) => {
            fancyLog(`file ${path} was changed`);
        });
    });
    cb();
}

module.exports = watchers;
