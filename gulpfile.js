'use strict';

const fs = require('fs');
const gulp = require('gulp');
const log = require('fancy-log');
const util = require('node:util');
const argv = require('yargs').argv;
const replace = require('gulp-replace');
const exec = util.promisify(require('node:child_process').exec);
const dist = getDistData();

/* ------ FUNCTIONS ------ */

// Get data from last generated distribution.
function getDistData() {
	try {
		const contents = fs.readFileSync('dist.json');
		return JSON.parse(contents);
	} catch (err) {
		console.log(err);
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
			"aseets": hashNames
		});
	});
	return modules;
}

/* ------ GULP TASKS ------ */

async function buildReactModules() {
	const options = {
		cwd: 'modules/admin'
	};

	for (const module of dist.react_modules) {
		const { stdout, stderr } = await exec(
			'npm run-script build',
			{
				cwd: 'modules/' + module.name
			}
		);
		if(stdout.indexOf('Compiled successfully.') !== -1) {
			log('Module:' + module.name + ' compiled successfully.');
		} else {
			log.error('Module:' + module.name + ' failed to compile, aborting...');
			log.error('Stdout: ' + stdout);
			return;
		}
	}
}

async function updateWpEnqueueScriptsFilePaths() {
	for (const module of dist.react_modules) {
		gulp.src(module.classPath + module.className)
			.pipe(replace(module.versions.previous.assets.css, module.versions.current.assets.css))
			.pipe(replace(module.versions.previous.assets.js, module.versions.current.assets.js))
			.pipe(gulp.dest(module.classPath));
	}
}

async function updateTranslationsFiles() {
	const { stdout, stderr } = await exec('wp i18n make-pot . languages/e-quotes.pot');
	if(stdout.indexOf('POT file successfully generated!') !== -1) {
		log('Translations updated successfully!');
	} else {
		log.error('Error updating translations files.');
		log.error('Stdout: ' + stdout);
	}
}

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
	gulp
		.src('./e-quotes.php')
		.pipe(
			replace(
				' * Version:         ' + currentVersion,
				' * Version:         ' + newVersion
			)
		)
		.pipe(
			gulp.dest('.')
		);

	// Update eQuotes class.
	gulp
		.src('src/eQuotes.php')
		.pipe(
			replace(
				'public const VERSION = \'' + currentVersion + '\';',
				'public const VERSION = \'' + newVersion + '\';'
			)
		)
		.pipe(
			gulp.dest('src/')
		);

	// Update dist.json file.
	gulp
		.src('dist.json')
		.pipe(
			replace(
				'"version": "' + currentVersion + '"',
				'"version": "' + newVersion + '"'
			)
		)
		.pipe(
			gulp.dest('.')
		);

	log('Updated from version ' + currentVersion + ' to ' + newVersion);
}

exports['prepare-build'] = gulp.series(
	// buildReactModules,
	// updateWpEnqueueScriptsFilePaths,
	updateVersion,
	// updateTranslationsFiles,
);
