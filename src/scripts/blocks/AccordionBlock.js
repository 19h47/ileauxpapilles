import { AbstractBlock } from 'starting-blocks';
import Accordion from '@19h47/accordion';
import mediaBreakpointUp from 'utils/mediaBreakpointUp';

/**
 *
 * @constructor
 * @param {object} container
 */
export default class AccordionBlock extends AbstractBlock {
	constructor(container) {
		super(container, 'AccordionBlock');
	}

	init() {
		super.init();

		this.desktop = JSON.parse(this.rootElement.getAttribute('data-accordion-desktop'));

		this.accordion = new Accordion(this.rootElement);
		this.accordion.init();

		this.onResize();
	}

	onResize() {
		super.onResize();

		this.accordion.destroyAll();

		if (!this.desktop && mediaBreakpointUp('md')) {
			return;
		}

		this.accordion.init();
	}
}
