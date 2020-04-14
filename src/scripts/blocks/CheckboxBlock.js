import { AbstractBlock } from 'starting-blocks';
import Checkbox from '@19h47/checkbox';

/**
 *
 * @constructor
 * @param {object} container
 */
export default class CheckboxBlock extends AbstractBlock {
	constructor(container) {
		super(container, 'CheckboxBlock');
	}

	init() {
		super.init();
		this.initPlugins();
	}

	initPlugins() {
		const checkbox = new Checkbox(this.rootElement);
		checkbox.init();
	}
}
