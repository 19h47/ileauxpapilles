import { AbstractBlock } from 'starting-blocks';

import { disableScroll, enableScroll } from 'utils/scroll';
import { elements } from 'scripts/config';
import clickOutside from 'utils/clickOutside';

/**
 *
 * @constructor
 * @param {object} container
 */
export default class ModalBlock extends AbstractBlock {
	constructor(container) {
		// console.info('Modal.constructor');

		super(container, 'ModalBlock');

		this.isOpen = elements.body.classList.contains('modal--is-open');

		// Scroll
		this.disableScroll = disableScroll;
		this.enableScroll = enableScroll;

		// Bind
		this.toggle = this.toggle.bind(this);
	}

	init() {
		super.init();

		this.buttons = [...this.rootElement.querySelectorAll('.js-modal-button')] || [];
		this.$body = this.rootElement.querySelector('.js-modal-body');
	}

	/**
	 * Modal.initEvents
	 */
	initEvents() {
		// console.info('Modal.setupEvents');
		super.initEvents();

		// On click
		this.buttons.forEach(button => button.addEventListener('click', this.toggle));

		// On esc key
		document.onkeydown = event => {
			if (27 === event.keyCode && this.isOpen) {
				this.close();
			}
		};

		clickOutside(
			this.$body,
			type => {
				if (type) {
					this.close();
				}
			},
			false,
		);
	}

	/**
	 * Modal.toggle
	 */
	toggle() {
		console.info('Modal.toggle');
		if (this.isOpen) return this.close();

		return this.open();
	}

	/**
	 * Modal.open
	 */
	open() {
		console.info('Modal.open');
		if (this.isOpen) return false;

		this.isOpen = true;

		elements.body.classList.add('modal--is-open');

		// When menu is open, disableScroll
		this.disableScroll();

		return true;
	}

	/**
	 * Modal.close
	 */
	close() {
		// console.info('Modal.close');
		if (!this.isOpen) return false;

		this.isOpen = false;

		elements.body.classList.remove('modal--is-open');

		// When menu is closed, enableScroll
		this.enableScroll();

		return true;
	}
}
