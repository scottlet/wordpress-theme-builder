### Wordpress Theme Builder

Requires global gulp and gulp-cli to be installed

Also requires node/npm, obvs.

If you have homebrew installed on OSX this is as simple as `brew install node`.

If you don't have homebrew on OSX, visit [brew.sh](http://brew.sh)

To run the demo, additionally check out [wordpress-theme-builder-src](https://github.com/scottbert/wordpress-theme-builder-src) then copy into the root of this folder as "src"

Do `npm install`

then ```gulp getwp``` to download and uncompress the latest wordpress into `.run`

Modify `src/wp-config.php` to contain the correct database credentials. By default this expects a local MySQL/MariaDB database called `wordpress` which can be accessed by `wordpress_user@localhost` with no password.

Optionally, modify ```src/options.js``` to change the name of your theme.

Then ```gulp``` to run the livereload local server ( http://localhost:9000 )
