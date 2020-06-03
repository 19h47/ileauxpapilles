/* global delileauxpapilles */
import { AbstractPage } from 'starting-blocks';
import multiply from '@19h47/multiply';
import Bouncer from 'formbouncerjs';

const { ajax_url: ajaxUrl, nonce } = delileauxpapilles;

const remove = target => target.classList.remove('Form--loading');

/**
 * Gift coupon page
 *
 * @extends {GiftCouponPage}
 * @class
 */
export default class GiftCouponPage extends AbstractPage {
	constructor(container) {
		super(container, 'GiftCouponPage');

		this.bouncer = null;
		this.url = `${ajaxUrl}?action=request_gift_coupon&nonce=${nonce}`;

		this.updatePrice = this.updatePrice.bind(this);
	}

	async init() {
		this.$form = this.rootElement.querySelector('form');
		this.$person = this.rootElement.querySelector('.js-person');
		this.$price = this.rootElement.querySelector('.js-price');
		this.$template = this.rootElement.querySelector('.js-template');
		this.$radioGroup = this.$template.querySelector('[data-node-type="RadioGroupBlock"]');
		this.$total = this.rootElement.querySelector('.js-total');

		this.checkboxes = [...this.$template.querySelectorAll('[data-node-type="CheckboxBlock"]')];

		this.radios = [...this.$radioGroup.querySelectorAll('input[type="radio"]')];

		this.person = 1;
		this.index = 0;
		this.price = 0;

		this.initPlugins();
		this.updatePrice();

		await super.init();
	}

	initPlugins() {
		this.bouncer = new Bouncer('form', {
			fieldClass: 'has-error',
			errorClass: 'Form__message',
			messages: {
				missingValue: {
					default: "Veuillez remplir ce champ s'il vous plaît.",
				},
				patternMismatch: {
					email: 'Veuillez entrer une adresse email valide.',
					default: 'Veuillez correspondre au format attendu.',
				},
			},
			disableSubmit: true,
		});
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

		document.addEventListener('bouncerFormValid', () => {
			const form = new FormData(this.$form);
			this.$form.classList.add('Form--loading');

			fetch(this.url, {
				method: 'POST',
				body: form,
			})
				.then(() => {
					remove(this.$form);
					this.$form.classList.add('Form--success');
				})
				.catch(error => {
					console.log(error.message);
					remove(this.$form);
					this.$form.classList.add('Form--error');
				});
		});
	}

	updatePrice() {
		this.price = 0;

		this.checkboxes.map($checkbox => {
			const $input = $checkbox.querySelector('input[type="checkbox"]');
			const price = parseFloat($input.getAttribute('data-price'));

			if (true === JSON.parse($input.getAttribute('checked'))) {
				this.price += multiply(price, this.person);
			}

			return true;
		});

		this.radios.map($input => {
			const price = parseFloat($input.getAttribute('data-price'));

			if (true === $input.checked) {
				this.price += multiply(price, this.person);
			}

			return true;
		});

		this.renderPrice(this.price);
	}

	renderPrice(price) {
		this.$price.innerHTML = price
			.toFixed(2)
			.toString()
			.replace('.', ',');
		this.$total.value = price.toFixed(2);
	}
}
