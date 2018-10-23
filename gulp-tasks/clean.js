'use strict';

const gulp = require('gulp');
const del = require('del');

const CONSTS = require('./CONSTS');

gulp.task('clean', () => {
    return del.sync([CONSTS.STATIC_DEST, CONSTS.BUILD_DEST, CONSTS.BUILD_DIST]);
});
