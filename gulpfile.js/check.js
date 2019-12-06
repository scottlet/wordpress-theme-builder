const fs = require('fs');
const fancyLog = require('fancy-log');

function check(cb) {
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

    cb();
}

module.exports = check;
