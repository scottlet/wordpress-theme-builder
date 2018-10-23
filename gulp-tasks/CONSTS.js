'use strict';

const LIVERELOAD = 35679;
const RANDOM_PORT = LIVERELOAD + parseInt(Math.random() * 100); // Randomize port for livereload.
const APPSERVER_PORT = 8888;
const SERVER_PORT = 9000;

const THEME = '.run/wp-content/themes/wp-theme/';
const {name, version} = require('../package.json');

const OPTIONS = require('../src/options');

let CONSTS = {
    APPSERVER_PORT: process.env.PORT || APPSERVER_PORT,
    AUDIO_SRC: 'src/audio',
    BROWSER_CONFIG: ['> 2%', 'last 1 version', 'IE 11', 'not dead'],
    BUILD_DEST: 'build/',
    BUILD_DIST: 'dist/',
    CSS_DEST: THEME + 'css/',
    CSS_SRC: 'src/css/',
    FONT_SRC: 'src/fonts',
    GULP_PORT: process.env.GULP_PORT || SERVER_PORT,
    GULP_TASKS: 'gulp-tasks',
    GULPFILE: 'gulpfile.js',
    IMG_DEST: THEME + '/img/',
    IMG_SRC: 'src/img',
    JS_DEST: THEME + '/js/',
    JS_ENTRY: 'src/js/app.js',
    JS_OUTPUT: `${OPTIONS.NAME || name}-${OPTIONS.VERSION || version}.min.js`,
    JS_SRC: 'src/js/',
    LANGUAGES_SRC: 'src/languages',
    LIVERELOAD_PORT: process.env.LIVERELOAD_PORT || RANDOM_PORT,
    NAME: OPTIONS.NAME || name,
    NODE_ENV: process.env.NODE_ENV,
    RUN_DEST: '.run/',
    SASS_SRC: 'src/sass',
    SRC: 'src',
    STATIC_DEST: THEME + '/',
    TEMPLATES_DEST:THEME + '/',
    TEMPLATES_SRC:'src/templates/',
    TEXT_SRC: 'src/text',
    UPLOAD_SRC: 'src/upload',
    VERSION: OPTIONS.VERSION || version,
    VIDEO_SRC: 'src/video',
    WP_SRC: '.contrib/wordpress',
    WP_CSS_SRC: 'src/css/style.css',
    WPCONFIG_SRC: 'src/wp-config.php'
};

CONSTS = Object.assign(CONSTS, OPTIONS);

module.exports = CONSTS;
