const path = require( 'path' );

const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
	...defaultConfig,
	entry: {
		columns: path.resolve( __dirname, 'src', 'columns.js' ),
	},
};
