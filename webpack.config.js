const defaults = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const glob = require('glob');

const blockDirectories = glob.sync('./blocks/*/');

const blockConfigs = blockDirectories.map((blockDirectory) => {
	const blockName = path.basename(blockDirectory);
	return {
		...defaults,
		entry: path.resolve(blockDirectory, 'src', 'index.js'),
		output: {
			filename: 'index.js',
			path: path.resolve(blockDirectory, 'build'),
		},
	};
});

module.exports = blockConfigs;
