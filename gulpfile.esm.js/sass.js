import { src, dest } from 'gulp';
import gulpChanged from 'gulp-changed';
import gulpIf from 'gulp-if';
import gulpLivereload from 'gulp-livereload';
import { onError } from 'gulp-notify';
import gulpPlumber from 'gulp-plumber';
import gulpPostcss from 'gulp-postcss';
import gulpRename from 'gulp-rename';
import gulpSassVariables from 'gulp-sass-variables';
import gulpSass from 'gulp-sass';
import postcssAssets from 'postcss-assets';
import postcssCombineMediaQuery from 'postcss-combine-media-query';
import cssnano from 'cssnano';
import postcssNormalize from 'postcss-normalize';
import postcssPresetEnv from 'postcss-preset-env';
import { CONSTS } from './CONSTS';
import sass from 'sass';
import Fiber from 'fibers';

const {
    NODE_ENV,
    BREAKPOINTS,
    NAME,
    VERSION,
    SASS_SRC,
    CSS_DEST,
    LIVERELOAD_PORT
} = CONSTS;

const isDev = NODE_ENV !== 'production';

const sassOptions = {
    errLogToConsole: true,
    fiber: Fiber,
    includePaths: []
};

const gulpOptions = isDev ? { sourcemaps: true } : {};

gulpSass.compiler = sass;

function buildSassVariables(breakpoints) {
    const c = {};

    for (const b in breakpoints) {
        c[`$${b.toLowerCase().replace(/_/g, '')}`] = `${breakpoints[b]}px`;
    }

    return c;
}

const sassVariables = buildSassVariables(BREAKPOINTS);

function rename(path) {
    path.basename = `${path.basename
        .replace('$name', NAME)
        .replace('$version', VERSION)}.min`;
}

function compileSass() {
    const processors = [
        postcssCombineMediaQuery,
        cssnano,
        postcssAssets,
        postcssNormalize,
        postcssPresetEnv
    ];

    return src(`${SASS_SRC}/**/*.scss`, gulpOptions)
        .pipe(gulpChanged(CSS_DEST))
        .pipe(
            gulpPlumber({
                errorHandler: onError(error => `Styles Error: ${error.message}`)
            })
        )
        .pipe(gulpSassVariables(sassVariables))
        .pipe(gulpSass(sassOptions).on('error', gulpSass.logError))
        .pipe(gulpPostcss(processors))
        .pipe(gulpRename(rename))
        .pipe(dest(CSS_DEST, gulpOptions))
        .pipe(gulpIf(isDev, gulpLivereload({ port: LIVERELOAD_PORT })));
}

export { compileSass as sass };
