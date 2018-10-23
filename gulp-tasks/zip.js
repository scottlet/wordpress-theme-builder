'use strict';

const CONSTS = require('./CONSTS');
const gulp = require('gulp');
const gulpZip = require('gulp-zip');

gulp.task('zip', ['copydeploy'], () => {
    return gulp.src(CONSTS.BUILD_DEST + '/**')
        .pipe(gulpZip(`${CONSTS.name || CONSTS.NAME}-${CONSTS.VERSION.replace(/\./gi, '-')}.zip`))
        .pipe(gulp.dest('dist'));
});
