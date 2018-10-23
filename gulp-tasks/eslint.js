'use strict';

const gulp = require('gulp');
const gulpESLint = require('gulp-eslint');
const gulpNotify = require('gulp-notify');
const gulpPlumber = require('gulp-plumber');
const CONSTS = require('./CONSTS');

function lint() {
    return gulp.src([CONSTS.GULPFILE, CONSTS.GULP_TASKS + '/**/*.js', CONSTS.JS_SRC + '/**/*.js'])
        .pipe(gulpPlumber({errorHandler: gulpNotify.onError(error => `ESLint Error: ${error.message}`)}))
        .pipe(gulpESLint())
        .pipe(gulpESLint.format());
}

gulp.task('eslint', ['check'], lint);

gulp.task('eslint-lr', lint);
