import { AbstractPage } from 'starting-blocks';

/**
 * Destination page
 *
 * @extends {VoucherPage}
 * @class
 */
export default class VoucherPage extends AbstractPage {
	constructor(container) {
		super(container, 'VoucherPage');

		this.updatePrice = this.updatePrice.bind(this);
	}

	async init() {
		this.$person = this.rootElement.querySelector('.js-person');
		this.$template = this.rootElement.querySelector('.js-template');
		this.$radioGroup = this.$template.querySelector('[data-node-type="RadioGroupBlock"]');
		this.$price = this.rootElement.querySelector('.js-price');

		this.checkboxes = [...this.$template.querySelectorAll('[data-node-type="CheckboxBlock"]')];

		this.radios = [...this.$radioGroup.querySelectorAll('input[type="radio"]')];

		this.person = 1;
		this.index = 0;
		this.price = 0;

		await super.init();
	}

	initEvents() {
		super.initEvents();

		this.$person.addEventListener('change', event => {
			const {
				target: { value },
			} = event;
			this.person = Math.max(1, parseInt(value, 10));
			this.$person.value = this.person;

			// this.insert();
			this.updatePrice();
		});

		this.$radioGroup.addEventListener('RadioBlock.change', this.updatePrice);
		this.checkboxes.map($checkbox => {
			const $input = $checkbox.querySelector('input[type="checkbox"]');
			$input.addEventListener('change', this.updatePrice);

			return true;
		});
	}

	updatePrice() {
		this.price = 0;

		this.checkboxes.map($checkbox => {
			const $input = $checkbox.querySelector('input[type="checkbox"]');
			const price = parseFloat($input.getAttribute('data-price'));

			console.log($input.checked);
			if (true === JSON.parse($input.getAttribute('checked'))) {
				this.price += price * this.person;
			}

			return true;
		});

		this.radios.map($input => {
			const price = parseFloat($input.getAttribute('data-price'));

			if (true === $input.checked) {
				this.price += price;
			}

			return true;
		});

		this.$price.innerHTML = this.price.toFixed(2).toString().replace('.', ',');
	}
}
