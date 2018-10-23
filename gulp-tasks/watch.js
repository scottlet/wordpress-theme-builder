'use strict';

const gulp = require('gulp');
const gulpLivereload = require('gulp-livereload');
const fancyLog = require('fancy-log');
const CONSTS = require('./CONSTS');

function watch() {
    gulpLivereload.listen({
        port: CONSTS.LIVERELOAD_PORT
    });
    const watchCopiedTemplates = gulp.watch([CONSTS.TEMPLATES_DEST + '/**/*'], gulpLivereload.reload);
    const watchPublic = gulp.watch(
        [
            CONSTS.IMG_SRC + '/**/*',
            CONSTS.FONT_SRC + '/**/*',
            CONSTS.AUDIO_SRC + '/**/*',
            CONSTS.VIDEO_SRC + '/**/*'
        ], ['copystaticfiles']);
    const watchSass = gulp.watch([CONSTS.SASS_SRC + '/**/*'], ['sass-watch']);
    const watchCss = gulp.watch([CONSTS.WP_CSS_SRC], ['copycss-lr']);
    const watchConfig = gulp.watch([CONSTS.WPCONFIG_SRC], ['copyconfig']);
    const watchTemplates = gulp.watch([CONSTS.TEMPLATES_SRC + '/**/*'], ['copyviews']);
    const watchJs = gulp.watch([CONSTS.GULPFILE, CONSTS.GULP_TASKS + '/**/*.js', CONSTS.JS_SRC + '/**/*.js'],
        ['eslint-lr']);

    [
        watchCopiedTemplates,
        watchPublic,
        watchJs,
        watchSass,
        watchCss,
        watchConfig,
        watchTemplates
    ].forEach((w) => {
        w.on('change', (e) => {
            fancyLog(e.path, e.type);
        });
    });
}

gulp.task('watch', ['build'], watch);
