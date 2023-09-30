const defaults = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const glob = require('glob');

const blockDirectories = glob.sync('./blocks/*/');

const blockConfigs = blockDirectories.map((blockDirectory) => {
	const blockName = path.basename(blockDirectory);
	return {
		...defaults,
		devServer: {
			devMiddleware: { writeToDisk: true },
			allowedHosts: 'auto',
			host: 'equotes.local',
			port: 8887,
			proxy: { '/build': [Object] },
			hot: false
		},
		entry: path.resolve(blockDirectory, 'src', 'index.tsx'), // Altere para .tsx se estiver usando TypeScript
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
								configFile: path.resolve(__dirname, 'tsconfig.json'), // Caminho para seu tsconfig.json
								transpileOnly: true,
							},
						},
					],
				},
			],
		},
		resolve: {
			extensions: ['.ts', '.tsx', '.js', '.jsx'], // Certifique-se de incluir .js e .jsx
		},
	};
});

module.exports = blockConfigs;
