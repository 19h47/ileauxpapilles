const baseline = 24;

export default class Baseline {
	constructor($element) {
		this.$element = $element;

		this.resize = this.resize.bind(this);
	}

	init() {
		window.addEventListener('resize', this.resize);
		this.resize();
	}

	resize() {
		this.$element.style.setProperty('height', 'auto');

		const rect = this.$element.getBoundingClientRect();
		const ratio = rect.width / rect.height;
		const height = rect.width / ratio;
		const leftover = rect.height % baseline;

		this.$element.style.setProperty('height', `${height + (baseline - leftover)}px`);
	}
}
