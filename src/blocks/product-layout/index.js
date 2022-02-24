import { registerBlockType } from '@wordpress/blocks';

import './style.scss';

import edit from './edit';
// import save from './save';

registerBlockType('store-press/sp-all-products', {

	edit,

	// Depricated
	// save: () => null
});
