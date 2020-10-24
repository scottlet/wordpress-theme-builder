import { copyConfig, copyCssLR, copyViews, copyStaticFiles } from './copy';
import { parallel, watch } from 'gulp';
import fancyLog from 'fancy-log';
import gulpLivereload from 'gulp-livereload';
import { eslint } from './eslint';
import { sass } from './sass';
import { CONSTS } from './CONSTS';

const {
    LIVERELOAD_PORT,
    TEMPLATES_DEST,
    IMG_SRC,
    FONT_SRC,
    AUDIO_SRC,
    VIDEO_SRC,
    SASS_SRC,
    WP_CSS_SRC,
    WPCONFIG_SRC,
    TEMPLATES_SRC,
    GULPFILE,
    JS_SRC,
    GULP_TASKS
} = CONSTS;

function watchers(cb) {
    gulpLivereload.listen({
        port: LIVERELOAD_PORT
    });
    const watchCopiedTemplates = watch(
        `${TEMPLATES_DEST}/**/*.php`,
        parallel(gulpLivereload.reload)
    );
    const watchPublic = watch(
        [
            `${IMG_SRC}/**/*`,
            `${FONT_SRC}/**/*`,
            `${AUDIO_SRC}/**/*`,
            `${VIDEO_SRC}/**/*`
        ],
        parallel(copyStaticFiles)
    );
    const watchSass = watch(`${SASS_SRC}/**/*`, parallel(sass));
    const watchCss = watch(WP_CSS_SRC, parallel(copyCssLR));
    const watchConfig = watch(WPCONFIG_SRC, parallel(copyConfig));
    const watchTemplates = watch(`${TEMPLATES_SRC}/**/*`, copyViews);
    const watchJs = watch(
        [GULPFILE, `${GULP_TASKS}/**/*.js`, `${JS_SRC}/**/*.js`],
        parallel(eslint)
    );

    [
        watchCopiedTemplates,
        watchPublic,
        watchJs,
        watchSass,
        watchCss,
        watchConfig,
        watchTemplates
    ].forEach(w => {
        w.on('change', path => {
            fancyLog(`file ${path} was changed`);
        });
    });
    cb();
}

export { watchers as watch };
