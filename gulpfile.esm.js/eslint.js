import { src } from 'gulp';
import gulpESLint from 'gulp-eslint';
import gulpNotify from 'gulp-notify';
import gulpPlumber from 'gulp-plumber';
import { CONSTS } from './CONSTS';
const { GULPFILE, GULP_TASKS, JS_SRC } = CONSTS;

function eslint() {
    return src([GULPFILE, `${GULP_TASKS}/**/*.js`, `${JS_SRC}/**/*.js`])
        .pipe(gulpPlumber({ errorHandler: gulpNotify.onError(error => `ESLint Error: ${error.message}`) }))
        .pipe(gulpESLint())
        .pipe(gulpESLint.format());
}

export { eslint };
