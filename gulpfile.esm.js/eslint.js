import { src } from 'gulp';
import gulpESLint from 'gulp-eslint';
import gulpChangedInPlace from 'gulp-changed-in-place';
import gulpPlumber from 'gulp-plumber';

import { notify } from './notify';
import { CONSTS } from './CONSTS';
const { GULPFILE, GULP_TASKS, JS_SRC } = CONSTS;

function eslint() {
    return src([GULPFILE, `${GULP_TASKS}/**/*.js`, `${JS_SRC}/**/*.js`])
        .pipe(gulpPlumber({ errorHandler: notify('ESLint Error') }))
        .pipe(gulpChangedInPlace())
        .pipe(gulpESLint())
        .pipe(gulpESLint.format())
        .pipe(gulpESLint.failAfterError());
}

export { eslint };
