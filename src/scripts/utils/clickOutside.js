const isNodeList = target => NodeList.prototype.isPrototypeOf(target); // eslint-disable-line no-prototype-builtins, max-len
const isHTMLCollection = target => HTMLCollection.prototype.isPrototypeOf(target); // eslint-disable-line no-prototype-builtins, max-len
const isHTMLElement = target => HTMLElement.prototype.isPrototypeOf(target); // eslint-disable-line no-prototype-builtins, max-len

/**
 *
 * @param {object} selector
 * @param {function} callback
 * @param {boolean} options
 */
export default function clickOutside(selector, callback, options = { removeListener: true }) {
	const listener = event => {
		const { target } = event;

		if (isNodeList(selector) || isHTMLCollection(selector)) {
			if ([...selector].some(selection => selection.contains(target))) {
				return callback(false); // eslint-disable-line standard/no-callback-literal
			}
		} else if (isHTMLElement(selector)) {
			if (selector.contains(target)) {
				return callback(false); // eslint-disable-line standard/no-callback-literal
			}
		} else {
			console.warn('Undefined type of', selector);
			return callback(null);
		}

		if (options.removeListener) {
			document.removeEventListener('click', listener);
		}

		return callback(true); // eslint-disable-line standard/no-callback-literal
	};

	document.addEventListener('click', event => listener(event));

	return listener;
}
