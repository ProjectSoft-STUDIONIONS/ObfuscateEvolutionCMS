const zl = require("zip-lib");
const chalk = require("chalk");

const zip = new zl.Zip();

zip.addFolder("assets", "obfuscate/assets");
zip.addFolder("install", "obfuscate/install");
zip.archive("obfuscate.zip").then(function () {
	console.log(chalk.yellowBright("done"));
}, function (err) {
	console.log(chalk.redBright(err));
});
