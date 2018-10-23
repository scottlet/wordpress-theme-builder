'use strict';

const gulp = require('gulp');

gulp.task('default', ['server']);

gulp.task('build', ['check', 'eslint', 'clean', 'copy', 'sass', 'browserify']);
gulp.task('deploy', ['buildtoprod']);
gulp.task('buildtoprod', ['build', 'copydeploy', 'zip']);
