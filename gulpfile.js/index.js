const { series, parallel } = require('gulp');

const { copy, copyDeploy, copyViews } = require('./copy');
const brotli = require('./brotli');
const browserify = require('./browserify');
const buildServer = require('./server');
const check = require('./check');
const clean = require('./clean');
const eslint = require('./eslint');
const getwp = require('./getwp');
const gzip = require('./gzip');
const sass = require('./sass');
const watch = require('./watch');
const zip = require('./zip');

const buildcode = parallel(check, eslint, copy, sass, browserify);
const build = series(clean, buildcode);
const deploy = series(build, copyDeploy, parallel(gzip, brotli), zip);

const server = series(build, watch, buildServer);

module.exports = {
    browserify,
    build,
    copyviews: copyViews,
    default: server,
    deploy,
    eslint,
    getwp,
    sass,
    server
};
