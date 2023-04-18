const path = require( 'path' );

const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

const CopyPlugin = require( 'copy-webpack-plugin' );

module.exports = {
	...defaultConfig,
	entry: {
		columns: path.resolve( __dirname, 'src', 'columns.js' ),
		templates: path.resolve( __dirname, 'src', 'templates.js' ),
	},
	plugins: [
		...defaultConfig.plugins,
		new CopyPlugin( {
			patterns: [
				{ from: 'src/img', to: './img' },
			],
		} ),
	],
};
