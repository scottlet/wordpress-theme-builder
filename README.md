###Wordpress Theme Builder

Requires global gulp and gulp-cli to be installed

Also requires node/npm, obvs.

If you have homebrew installed on OSX this is as simple as `brew install node`.

If you don't have homebrew on OSX, visit http://brew.sh

To run the demo, additionally check out https://github.com/scottbert/gulp-express-livereload-src-example then copy into the root of this folder as "src"

Do ```npm install```

then ```gulp getwp``` to download and uncompress the latest wordpress into `.run`

Modify ```src/wp-config.php``` to contain the correct database credentials

Modify ```src/options.js``` to change the name of your theme.

Then ```gulp``` to run the livereload local server ( http://localhost:9000 )
