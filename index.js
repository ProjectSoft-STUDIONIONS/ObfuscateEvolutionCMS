'use strict'
const zl = require("zip-lib"),
	chalk = require("chalk"),
	zip = new zl.Zip();

zip.addFolder("assets", "obfuscate/assets");
zip.addFolder("install", "obfuscate/install");
zip.archive("obfuscate.zip").then(function () {
	console.log("Output: " + chalk.yellowBright("obfuscate.zip"));
}, function (err) {
	console.log(chalk.redBright(err));
});
