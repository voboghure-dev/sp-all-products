import { registerBlockType } from '@wordpress/blocks';

import './style.scss';

import edit from './edit';

registerBlockType('store-press/sp-all-products-list', {

	edit,

});
