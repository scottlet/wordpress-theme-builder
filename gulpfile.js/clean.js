'use strict';

const del = require('del');
const CONSTS = require('./CONSTS');

function clean() {
    return del(['contrib', CONSTS.STATIC_DEST, CONSTS.BUILD_DEST, CONSTS.BUILD_DIST]);
}

module.exports = clean;
