import del from 'del';
import { CONSTS } from './CONSTS';

const { STATIC_DEST, BUILD_DEST, BUILD_DIST } = CONSTS;

function clean() {
    return del(['contrib', STATIC_DEST, BUILD_DEST, BUILD_DIST]);
}

export { clean };
