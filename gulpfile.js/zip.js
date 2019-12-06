const CONSTS = require('./CONSTS');
const { src, dest } = require('gulp');
const gulpZip = require('gulp-zip');

function zip() {
    return src(`${CONSTS.BUILD_DEST}/**`)
        .pipe(gulpZip(`${CONSTS.name || CONSTS.NAME}-${CONSTS.VERSION.replace(/\./gi, '-')}.zip`))
        .pipe(dest('dist'));
}

module.exports = zip;
