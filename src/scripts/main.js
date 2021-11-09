import StartingBlocks, { polyfills } from 'starting-blocks';

// import WebpackAsyncBlockBuilder from 'services/WebpackAsyncBlockBuilder';

import GiftCouponPage from 'pages/GiftCouponPage';

import AccordionBlock from 'blocks/AccordionBlock';
import CarouselBlock from 'blocks/CarouselBlock';
import CheckboxBlock from 'blocks/CheckboxBlock';
import MapBlock from 'blocks/MapBlock';
import ModalBlock from 'blocks/ModalBlock';
import RadioGroupBlock from 'blocks/RadioGroupBlock';

import Guid from 'common/Guid';
import Baseline from 'common/Baseline';

const production = 'production' !== process.env.NODE_ENV;

(() => {
	window.MAIN_EXECUTED = true;

	polyfills();

	if (production) {
		const guid = new Guid();

		guid.init();
	}

	const images = [...document.querySelectorAll('.js-baseline')];

	images.map($image => new Baseline($image).init());

	const startingBlocks = new StartingBlocks({
		// manualDomAppend: true,
		debug: production ? 1 : 0,
	});

	// startingBlocks.provider('BlockBuilder', WebpackAsyncBlockBuilder);

	startingBlocks.instanceFactory('GiftCouponPage', c => new GiftCouponPage(c));

	startingBlocks.instanceFactory('AccordionBlock', c => new AccordionBlock(c));
	startingBlocks.instanceFactory('CarouselBlock', c => new CarouselBlock(c));
	startingBlocks.instanceFactory('CheckboxBlock', c => new CheckboxBlock(c));
	startingBlocks.instanceFactory('MapBlock', c => new MapBlock(c));
	startingBlocks.instanceFactory('ModalBlock', c => new ModalBlock(c));
	startingBlocks.instanceFactory('RadioGroupBlock', c => new RadioGroupBlock(c));

	startingBlocks.boot();
})();
