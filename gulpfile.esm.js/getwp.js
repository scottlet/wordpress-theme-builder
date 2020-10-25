import { src, dest, series } from 'gulp';
import gulpDownload from 'gulp-download';
import gulpUnzip from 'gulp-unzip';

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

const getwp = series(download, move);

export { getwp };
