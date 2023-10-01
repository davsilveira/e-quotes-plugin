const defaults = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const glob = require('glob');

// Change to the block you want to edit.
// const blockDirectories = glob.sync('./blocks/final-settings/');
const blockDirectories = glob.sync('./blocks/price/');

const blockConfigs = blockDirectories.map((blockDirectory) => {
	const blockName = path.basename(blockDirectory);
	return {
		...defaults,
		devServer: {
			devMiddleware: { writeToDisk: true },
			allowedHosts: 'auto',
			host: 'equotes.local',
			port: 8887,
			proxy: { '/build': [Object] }
		},
		entry: path.resolve(blockDirectory, 'src', 'index.tsx'),
		output: {
			filename: 'index.js',
			path: path.resolve(blockDirectory, 'build'),
		},
		module: {
			...defaults.module,
			rules: [
				...defaults.module.rules,
				{
					test: /\.(ts|tsx)$/,
					use: [
						{
							loader: 'ts-loader',
							options: {
								configFile: path.resolve(__dirname, 'tsconfig.json'),
								transpileOnly: true,
							},
						},
					],
				},
			],
		},
		resolve: {
			extensions: ['.ts', '.tsx', '.js', '.jsx'],
		},
	};
});

module.exports = blockConfigs;
