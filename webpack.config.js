const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const path = require("path");

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry,
		"product-grid": path.resolve(process.cwd(), "src/blocks/product-grid"),
		// "product-list": path.resolve(process.cwd(), "src/blocks/product-list"),
	},
};
