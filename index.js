'use strict'
const fs = require("node:fs"),
	zl = require("zip-lib"),
	chalk = require("chalk"),
	// RegExp version
	regex = /(\@version\s+)([\d.]+)/g,
	// Read package.json
	pack = JSON.parse(fs.readFileSync('package.json').toString()),
	// Read TPL
	tpl = fs.readFileSync('install/assets/plugins/obfuscate.tpl').toString(),
	// Read PHP
	php = fs.readFileSync('assets/plugins/obfuscate/plugin.obfuscate.php').toString(),
	// Text replaced package version
	subst = `$1${pack.version}`,
	// Replace TPL
	resultTpl = tpl.replace(regex, subst),
	// Replace PHP
	resultPhp = php.replace(regex, subst),
	// Create Zip
	zip = new zl.Zip();

// Write TPL
fs.writeFileSync('install/assets/plugins/obfuscate.tpl', resultTpl, {encode: 'utf8'});
// Write PHP
fs.writeFileSync('assets/plugins/obfuscate/plugin.obfuscate.php', resultPhp, {encode: 'utf8'});

// Add path assets
zip.addFolder("assets", "obfuscate/assets");
// Add path install
zip.addFolder("install", "obfuscate/install");
// Ziping
zip.archive("obfuscate.zip").then(function () {
	console.log("Output: " + chalk.yellowBright("obfuscate.zip"));
}, function (err) {
	console.log(chalk.redBright(err));
});
