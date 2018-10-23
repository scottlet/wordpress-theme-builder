'use strict';

const gulp = require('gulp');
const gulpDownload = require('gulp-download');
const gulpUnzip = require('gulp-unzip');

const WP_URL = 'https://wordpress.org/latest.zip';

function download() {
    return gulpDownload(WP_URL)
        .pipe(gulpUnzip())
        .pipe(gulp.dest('./contrib'));
}

function move() {
    return gulp.src('./contrib/wordpress/**')
        .pipe(gulp.dest('.run/'));
}

gulp.task('getwp', ['downloadwp'], move);
gulp.task('movewp', move);
gulp.task('downloadwp', download);
