const defaults = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const glob = require('glob');

module.exports = {
	...defaults,
	devServer: {
		devMiddleware: { writeToDisk: true },
		allowedHosts: 'auto',
		host: 'equotes.local',
		port: 8887,
		proxy: { '/build': [Object] },
	},
	externals: {
		react: 'React',
		'react-dom': 'ReactDOM',
	},
	entry: () => {
		const entries = {};

		const blockFiles = glob.sync('./src/blocks/**/*.tsx'); // Alteração para .tsx

		blockFiles.forEach((file) => {
			const blockName = path.basename(file, '.tsx'); // Alteração para .tsx
			entries[blockName] = path.resolve(process.cwd(), file);
		});

		return entries;
	},
	output: {
		filename: './build/[name].js', // Manter a saída como .js para compatibilidade
		path: path.resolve(process.cwd()),
	},
	module: {
		...defaults.module,
		rules: [
			...defaults.module.rules,
			{
				test: /\.tsx?$/,
				use: [
					{
						loader: 'ts-loader',
						options: {
							configFile: 'tsconfig.json',
							transpileOnly: true,
						},
					},
				],
			},
		],
	},
	resolve: {
		extensions: ['.ts', '.tsx', ...(defaults.resolve ? defaults.resolve.extensions || ['.js', '.jsx'] : [])],
	},
};
