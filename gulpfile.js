'use strict';

const fs = require('fs');
const gulp = require('gulp');
const log = require('fancy-log');
const util = require('node:util');
const replace = require('gulp-replace');
const exec = util.promisify(require('node:child_process').exec);

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

/* ------ GULP TASKS ------ */

async function buildReactModules() {
	const dist = getDistData();
	const options = {
		cwd: 'modules/admin'
	};

	for (const module of dist.react_modules) {
		const { stdout, stderr } = await exec('npm run-script build', options);
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
	const dist = getDistData();
	for (const module of dist.react_modules) {
		gulp.src(module.classPath)
			.pipe(replace(module.versions.previous.assets.css, module.versions.current.assets.css))
			.pipe(replace(module.versions.previous.assets.js, module.versions.current.assets.js))
			.pipe(dest('.'));
	}
}

exports['prepare-build'] = gulp.series(
	// buildReactModules,
	updateWpEnqueueScriptsFilePaths
);
