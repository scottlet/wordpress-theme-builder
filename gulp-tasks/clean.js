'use strict';

const gulp = require('gulp');
const del = require('del');

const CONSTS = require('./CONSTS');

gulp.task('clean', () => {
    return del.sync(['contrib', CONSTS.STATIC_DEST, CONSTS.BUILD_DEST, CONSTS.BUILD_DIST]);
});
