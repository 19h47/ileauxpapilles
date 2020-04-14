import { AbstractBlock } from 'starting-blocks';
import RadioGroup from '@19h47/radiogroup';

/**
 *
 * @constructor
 * @param {object} container
 */
export default class RadioGroupBlock extends AbstractBlock {
	constructor(container) {
		super(container, 'RadioGroupBlock');

		this.radiogroup = null;
	}

	init() {
		super.init();
		this.initPlugins();
	}

	initPlugins() {
		this.radiogroup = new RadioGroup(this.rootElement);
		this.radiogroup.init();
	}

	initEvents() {
		super.initEvents();

		this.radiogroup.radios.map(radio =>
			radio.on('Radio.activate', item => {
				const changeEvent = new CustomEvent('RadioBlock.change', {
					detail: { item },
				});

				this.rootElement.dispatchEvent(changeEvent);
			}),
		);
	}
}
