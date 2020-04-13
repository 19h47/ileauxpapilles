import StartingBlocks, { polyfills } from 'starting-blocks';

import WebpackAsyncBlockBuilder from 'services/WebpackAsyncBlockBuilder';

import Guid from 'common/Guid';

const production = 'production' !== process.env.NODE_ENV;

(() => {
	window.MAIN_EXECUTED = true;

	polyfills();

	if (production) {
		const guid = new Guid();

		guid.init();
	}

	const startingBlocks = new StartingBlocks({
		// manualDomAppend: true,
		debug: production ? 1 : 0,
	});

	startingBlocks.provider('BlockBuilder', WebpackAsyncBlockBuilder);

	startingBlocks.boot();
})();
