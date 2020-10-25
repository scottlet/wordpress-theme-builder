import { src, dest } from 'gulp';
import gulpBrotli from 'gulp-brotli';

import { CONSTS } from './CONSTS';

const { BUILD_DEST } = CONSTS;

/**
 * Compress static assets with Brotli so they can be served via nginx
 * without needing on-the-fly compression.
 *
 * @function
 * @returns  {object} vinylSourceStream
 */
function brotli() {
    return src(`${BUILD_DEST}**/*.{css,svg,js,html}`)
        .pipe(
            gulpBrotli.compress({
                skipLarger: true,
                mode: 0,
                quality: 11,
                lgblock: 0
            })
        )
        .pipe(dest(BUILD_DEST));
}

export { brotli };
