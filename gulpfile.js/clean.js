'use strict';

const {sync} = require('del');
const CONSTS = require('./CONSTS');

function clean(cb) {
    sync(['contrib', CONSTS.STATIC_DEST, CONSTS.BUILD_DEST, CONSTS.BUILD_DIST]);
    cb();
}

module.exports = clean;
