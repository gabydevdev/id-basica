// WordPress webpack config.
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

// Plugins.
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );

// Utilities.
const path = require( 'path' );

// Environment check
const isProduction = process.env.NODE_ENV === 'production';

// Disable RTL CSS generation
const filteredPlugins = defaultConfig.plugins.filter(
	(plugin) => plugin.constructor.name !== 'RtlCssPlugin'
);

// Add any new entry points by extending the webpack config.
module.exports = {
	...defaultConfig,
	entry: {
		'js/main': path.resolve( process.cwd(), 'src/js/main.js' ),
		'js/acf-fields': path.resolve( process.cwd(), 'src/js/acf-fields.js' ),
		'css/main': path.resolve( process.cwd(), 'src/scss/main.scss' ),
		'css/acf-fields': path.resolve( process.cwd(), 'src/scss/acf-fields.scss' ),
	},
	output: {
		...defaultConfig.output,
		clean: true,
	},
	devtool: isProduction ? 'source-map' : 'eval-source-map',
	optimization: {
		...defaultConfig.optimization,
		splitChunks: {
			cacheGroups: {
				style: {
					type: 'css/mini-extract',
					enforce: true,
				},
			},
		},
	},
	plugins: [
		...filteredPlugins,
		new RemoveEmptyScriptsPlugin(
			{
				stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
			}
		),
	],
performance: {
	maxEntrypointSize: 512000,
	maxAssetSize: 512000,
	},
};
