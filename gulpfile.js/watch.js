'use strict';

const {copyConfig, copyCssLR, copyViews, copyStaticFiles} = require('./copy');
const {parallel, watch} = require('gulp');
const fancyLog = require('fancy-log');
const gulpLivereload = require('gulp-livereload');
const eslint = require('./eslint');
const sass = require('./sass');
const CONSTS = require('./CONSTS');

function watchers(cb) {
    gulpLivereload.listen({
        port: CONSTS.LIVERELOAD_PORT
    });
    const watchCopiedTemplates = watch(CONSTS.TEMPLATES_DEST + '/**/*.php', parallel(gulpLivereload.reload));
    const watchPublic = watch(
        [
            CONSTS.IMG_SRC + '/**/*',
            CONSTS.FONT_SRC + '/**/*',
            CONSTS.AUDIO_SRC + '/**/*',
            CONSTS.VIDEO_SRC + '/**/*'
        ], parallel(copyStaticFiles));
    const watchSass = watch(CONSTS.SASS_SRC + '/**/*', parallel(sass));
    const watchCss = watch(CONSTS.WP_CSS_SRC, parallel(copyCssLR));
    const watchConfig = watch(CONSTS.WPCONFIG_SRC, parallel(copyConfig));
    const watchTemplates = watch(CONSTS.TEMPLATES_SRC + '/**/*', parallel(copyViews));
    const watchJs = watch([CONSTS.GULPFILE, CONSTS.GULP_TASKS + '/**/*.js', CONSTS.JS_SRC + '/**/*.js'],
        parallel(eslint));

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
