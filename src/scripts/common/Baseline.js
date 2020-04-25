const imagesLoaded = require('imagesloaded');

const baseline = 24;

export default class Baseline {
	constructor($element) {
		this.$element = $element;

		this.resize = this.resize.bind(this);
	}

	init() {
		window.addEventListener('resize', this.resize);

		imagesLoaded(this.$element, () => {
			const rect = this.$element.getBoundingClientRect();

			this.ratio = rect.width / rect.height;
			this.resize();
		});
	}

	resize() {
		const rect = this.$element.getBoundingClientRect();
		const height = rect.width / this.ratio;
		const leftover = height % baseline;

		this.$element.style.setProperty('height', `${height + (baseline - leftover)}px`);
	}
}
