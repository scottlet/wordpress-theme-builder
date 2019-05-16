'use strict';

const {series, parallel} = require('gulp');

const browserify = require('./browserify');
const check = require('./check');
const clean = require('./clean');
const {copy, copyDeploy, copyViews} = require('./copy');
const eslint = require('./eslint');
const getwp = require('./getwp');
const sass = require('./sass');
const buildServer = require('./server');
const watch = require('./watch');
const zip = require('./zip');

const buildcode = parallel(check, eslint, copy, sass, browserify);
const build = series(clean, buildcode);
const deploy = series(build, copyDeploy, zip);

const server = series(build, watch, buildServer);

module.exports = {
    default: server,
    server,
    deploy,
    build,
    browserify,
    eslint,
    getwp,
    copyviews: copyViews
};
