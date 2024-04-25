import { CONSTS } from './CONSTS';
import { src, dest } from 'gulp';
import gulpZip from 'gulp-zip';

const { BUILD_DEST, NAME, VERSION } = CONSTS;

function zip() {
  return src(`${BUILD_DEST}/**`)
    .pipe(gulpZip(`${NAME}-${VERSION.replace(/\./gi, '-')}.zip`))
    .pipe(dest('dist'));
}

export { zip };
