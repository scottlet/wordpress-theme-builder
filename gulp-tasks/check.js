'use strict';

const fs = require('fs');
const gulp = require('gulp');
const fancyLog = require('fancy-log');

function check() {
    if (!fs.existsSync('.run')) {
        fancyLog(`\n\n
             ╔═══════════════════════════════════════════════════════════╗
             ║  WordPress isn't downloaded. Run 'gulp getwp' and make    ║
             ║  sure WordPress is configured according to the setup      ║
             ║  instructions in README.md                                ║
             ╚═══════════════════════════════════════════════════════════╝
            \n\n`);
        process.exit();
    }

    return gulp;
}

gulp.task('check', check);
