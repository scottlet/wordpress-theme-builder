'use strict';

const {series, parallel, task} = require('gulp');

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

task('browserify', browserify);
task('check', check);
task('copy', copy);
task('copyviews', copyViews);
task('eslint', eslint);
task('eslint-lr', eslint);
task('getwp', getwp);
task('sass-watch', sass);
task('server', buildServer);
task('sass', sass);
task('watch', watch);

const buildcode = parallel(check, eslint, copy, sass, browserify);
const build = series(clean, buildcode);
const deploy = series(build, copyDeploy, zip);

task('buildcode', buildcode);
task('build', build);
task('deploy', deploy);

const server = series('build', 'watch', 'server');

exports.default = server;
