const { src, dest, series } = require('gulp');
const gulpDownload = require('gulp-download');
const gulpUnzip = require('gulp-unzip');

const WP_URL = 'https://wordpress.org/latest.zip';

function download() {
    return gulpDownload(WP_URL)
        .pipe(gulpUnzip())
        .pipe(dest('./contrib'));
}

function move() {
    return src('./contrib/wordpress/**')
        .pipe(dest('.run/'));
}

module.exports = series(download, move);
