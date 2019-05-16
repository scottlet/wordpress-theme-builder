'use strict';

const connectCORS = require('connect-cors');
const connectLivereload = require('connect-livereload');
const fancyLog = require('fancy-log');
const {series} = require('gulp');
const gulpConnect = require('gulp-connect');
const gulpConnectPHP = require('gulp-connect-php');
const httpProxyMiddleware = require('http-proxy-middleware');
const httpRewriteMiddleware = require('http-rewrite-middleware');
const serveStatic = require('serve-static');
const url = require('url');
const CONSTS = require('./CONSTS');

const urlrewrites = [
    {
        from: '^/(wp-admin|wp-includes|wp-content)/((?!.*php).*)',
        to: '/$2'
    }
];

const staticOptions = {
    etag: false,
    index: false
};

function runPHP(cb) {
    gulpConnectPHP.server({
        port: CONSTS.APPSERVER_PORT,
        hostname: '0.0.0.0',
        base: CONSTS.RUN_DEST,
        debug: false,
        configCallback: function _configCallback(type, collection) {
            if (type === gulpConnectPHP.OPTIONS_SPAWN_OBJ) {
                collection.env = Object.assign({
                    APPLICATION_ENV: 'development'
                }, process.env);

                return collection;
            }

            return collection;
        }
    });
    cb();
}

function makeServer(cb) {
    const port = CONSTS.GULP_PORT;

    gulpConnect.server({
        root: '.run',
        port,
        host: '0.0.0.0',
        debug: true,
        middleware: () => {
            return [
                connectLivereload({
                    port: CONSTS.LIVERELOAD_PORT
                }),
                httpRewriteMiddleware.getMiddleware(urlrewrites),
                serveStatic('.run/wp-admin/', staticOptions),
                serveStatic('.run/wp-content/', staticOptions),
                serveStatic('.run/wp-includes/', staticOptions),
                httpProxyMiddleware('/', {
                    target: url.parse('http://127.0.0.1:' + CONSTS.APPSERVER_PORT)
                }),
                connectCORS()
            ];
        }
    });
    cb();

    fancyLog('server http://127.0.0.1:' + port);
}

module.exports = series(runPHP, makeServer);
