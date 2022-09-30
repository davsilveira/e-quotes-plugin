'use strict';

const gulp = require('gulp');
const fs = require('fs');
const run = require('gulp-run');
const replace = require('gulp-replace');
const { exec } = require('child_process');

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

// Syncronous file search and replace.
function searchReplaceStrInFile(filePath, strToFind, contentToReplace) {
	// TODO Use gulp-replace instead.
	try {
		const contents = fs.readFileSync(filePath, 'utf8');
		const replaced = contents.replace(strToFind, contentToReplace);

		try {
			console.log(
				'Writing on file ' + filePath +
				', searching for ' + strToFind +
				', replacing to ' + contentToReplace
			);
			fs.writeFileSync(filePath, replaced, 'utf-8');
		} catch (err) {
			console.error(err);
		}
	} catch (err) {
		console.error(err);
	}
}

// Get dist data from a new build.
function getModulesBuildAssetsFiles() {
	const dist = getDistData();
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

async function buildReactModules() {
	const dist = getDistData();

	dist.react_modules.forEach(async function(module){
		const options = {
			cwd: 'modules/' + module.name,
			silent: true
		};
		const result = await run('npm run-script build', options).exec().pipe(gulp.dest('output'));
	});
}

/* ------ GULP TASKS ------ */

// Update react modules assets filenames.
async function updateReactModulesAssets() {
	const dist = getDistData();
	dist.react_modules.forEach(function(module){
		searchReplaceStrInFile(
			module.classPath,
			module.versions.previous.assets.css,
			module.versions.current.assets.css
		);
		searchReplaceStrInFile(
			module.classPath,
			module.versions.previous.assets.js,
			module.versions.current.assets.js
		);
	});
	// TODO update dist.json
}

// Prepare to build.
async function prepareBuild() {

	// Build the modules with npm run-scripts build.
	OK
	// Change version on package.json, e-quotes.php, src/eQuotes.php.
	OK
	// Update dist.json version.
	OK
	// Change module assets filepaths.
	OK
	// Update dist.json module assets.
	OK
	// Regenerate pot file.
	OK
}

// Build dist version.
async function buildDist() {
	// Create dist folder if not exists.

	// Copy di/ (except cache file if exists)

	// Copy languages/

	// Copy modules/*/build/static/*

	// Copy src/

	// Copy vendor/

	// Copy files: e-quotes.php, LICENSE, readme.md

	// Turn DI ->enable_compilation( false ) into ->enable_compilation( true )

	// Zip
}

async function buildReactModules2() {
	const dist = getDistData();

	dist.react_modules.forEach(async function(module){
		const options = {
			cwd: 'modules/' + module.name,
			silent: true
		};
		const result = await exec('npm run-script build', options);
	});
}

exports['prepare-build'] = gulp.series(
	buildReactModules2,
);
// gulp.task('update-react-modules-assets', updateReactModulesAssets);
// gulp.task('prepare-build', prepareBuild);
// gulp.task('build-dist', buildDist);
