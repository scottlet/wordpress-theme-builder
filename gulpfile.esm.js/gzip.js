import { CONSTS } from './CONSTS';
import { src, dest } from 'gulp';
import gulpGZip from 'gulp-gzip';
const { BUILD_DEST } = CONSTS;

function gzip() {
    return src(`${BUILD_DEST}/**/*.{css,svg,js,html}`)
        .pipe(gulpGZip({ gzipOptions: { level: 9 } }))
        .pipe(dest(BUILD_DEST));
}

export { gzip };
