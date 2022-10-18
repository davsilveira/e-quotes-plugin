'use strict';

const fs = require('fs');
const gulp = require('gulp');
const log = require('fancy-log');
const util = require('node:util');
const argv = require('yargs').argv;
const replace = require('gulp-replace');
const exec = util.promisify(require('node:child_process').exec);
const dist = require('./dist.json');

/* ------ FUNCTIONS ------ */

// Search and replace in file using fs.
function searchReplaceStrInFile(filePath, strToFind, contentToReplace) {
	try {
		const contents = fs.readFileSync(filePath, 'utf8');
		const replaced = contents.replace(strToFind, contentToReplace);

		try {
			fs.writeFileSync(filePath, replaced, 'utf-8');
			log(
				'File updated ' + filePath +
				', searched for ' + strToFind +
				', replaced with ' + contentToReplace
			);
		} catch (err) {
			log.error(err);
		}
	} catch (err) {
		log.error(err);
	}
}

// Retrieve the current version from dist.json file.
function getCurrentVersion(parse = true) {
	if(!parse) {
		return dist.version;
	}
	const vkeys = ['ma', 'mi', 'pa'];
	let version = {};
	dist.version.split('.').map(function(val, idx){
		version[vkeys[idx]] = parseInt(val);
	});
	return version;
}

// Generate a new version number.
function getNewVersion(ma = null, mi = null, pa = null) {
	const version = getCurrentVersion();
	let updated = false;

	// Just update patch number.
	if(pa && pa >= version.pa) {
		version.pa = pa;
		updated = true;
	}

	// If a minor number is supplied, reset patch to zero.
	if(!updated && mi && mi >= version.mi) {
		version.mi = mi;
		version.pa = 0;
		updated = true;
	}

	// If a major number is supplied, reset all the other to zero.
	if(!updated && ma && ma >= version.ma) {
		version.ma = ma;
		version.mi = 0;
		version.pa = 0;
		updated = true;
	}

	// If none number is supplied, we just increment the patch number.
	if(!updated) {
		version.pa++;
	}

	return version.ma + '.' + version.mi + '.' + version.pa;
}

// Get dist data from a new build.
function getModulesBuildAssetsFiles() {
	let modules = [];
	dist.react_modules.forEach(function(module){
		const contents = fs.readFileSync(module.buildPath + '/asset-manifest.json');
		const manifest = JSON.parse(contents);
		let hashNames = {};
		let extensions = ['css', 'js'];
		manifest.entrypoints.forEach(function(filename){
			extensions.forEach(function(ext){
				if(filename.indexOf(ext) !== -1) {
					hashNames[ext] = filename.replace('static/' + ext + '/', '');
				}
			});
		});
		modules.push({
			"name": module.name,
			"assets": hashNames
		});
	});
	return modules;
}

/* ------ GULP TASKS ------ */

// Run npm build for each module inside /modules/ folder.
async function buildReactModules() {
	const options = {
		cwd: 'modules/admin'
	};

	for (const module of dist.react_modules) {
		const { stdout, stderr } = await exec(
			'npm run build',
			{
				cwd: 'modules/' + module.name
			}
		);
		if(stdout.indexOf('compiled successfully') !== -1) {
			log('Module:' + module.name + ' compiled successfully.');
		} else {
			log.error('Module:' + module.name + ' failed to compile, aborting...');
			log.error('Stdout: ' + stdout);
			return;
		}
	}
}

// Update the assets version for all modules in the dist.json file.
async function updateAssetsVersion() {
	const buildVersion = getModulesBuildAssetsFiles();
	let newDist = { ... dist };

	for (const idx in buildVersion) {
		const currentCss = newDist.react_modules[idx].versions.current.assets.css;
		const currentJs = newDist.react_modules[idx].versions.current.assets.js;

		newDist.react_modules[idx].versions.previous.assets.css = currentCss;
		newDist.react_modules[idx].versions.previous.assets.js = currentJs;

		newDist.react_modules[idx].versions.current.assets.css = buildVersion[idx].assets.css;
		newDist.react_modules[idx].versions.current.assets.js = buildVersion[idx].assets.js;
	}

	try {
		fs.writeFileSync('dist.json', JSON.stringify(newDist), 'utf-8');
		log('Assets version updated');
	} catch (err) {
		log.error(err);
	}
}

// Update wp_enqueue_scripts for modules with the new js and css paths based on versions in dist.json file.
async function updateWpEnqueueScriptsFilePaths() {
	for (const module of dist.react_modules) {
		searchReplaceStrInFile(
			module.classPath + module.className,
			module.versions.previous.assets.css,
			module.versions.current.assets.css
		);
		searchReplaceStrInFile(
			module.classPath + module.className,
			module.versions.previous.assets.js,
			module.versions.current.assets.js
		);
	}
}

// Run make-pot command.
async function updateTranslationsFiles() {
	const { stdout, stderr } = await exec('wp i18n make-pot . languages/e-quotes.pot');
	if(stdout.indexOf('POT file successfully generated!') !== -1) {
		log('Translations updated successfully!');
	} else {
		log.error('Error updating translations files.');
		log.error('Stdout: ' + stdout);
	}
}

// Update the version of the plugin in multiple files.
async function updateVersion() {
	const ma = (argv.ma === undefined) ? null : parseInt(argv.ma);
	const mi = (argv.mi === undefined) ? null : parseInt(argv.mi);
	const pa = (argv.pa === undefined) ? null : parseInt(argv.pa);
	const vs = (argv.vs === undefined) ? null : argv.vs;

	// Don't accept version combinations, use vs instead.
	if(
		ma && mi ||
		ma && pa ||
		mi && pa ||
		ma && mi && pa
	) {
		log.error('Error: Supplying multiple version parameters at same time is not allowed, use --vs instead.');
		return;
	}

	const newVersion = vs ? vs : getNewVersion(ma, mi, pa);
	const currentVersion = getCurrentVersion(false);

	// Update e-quotes main plugin file.
	searchReplaceStrInFile(
		'./e-quotes.php',
		' * Version:         ' + currentVersion,
		' * Version:         ' + newVersion
	);

	// Update eQuotes class.
	searchReplaceStrInFile(
		'src/eQuotes.php',
		'public const VERSION = \'' + currentVersion + '\';',
		'public const VERSION = \'' + newVersion + '\';'
	)

	// Update dist.json file.
	let newDist = { ... dist };
	newDist.version = newVersion;

	try {
		fs.writeFileSync('dist.json', JSON.stringify(newDist), 'utf-8');
		log('Updated dist.json version from ' + currentVersion + ' to ' + newVersion);
	} catch (err) {
		log.error(err);
	}
}

exports['prepare-build'] = gulp.series(
	buildReactModules,
	// updateAssetsVersion,
	// updateWpEnqueueScriptsFilePaths,
	updateTranslationsFiles,
	updateVersion,
);
