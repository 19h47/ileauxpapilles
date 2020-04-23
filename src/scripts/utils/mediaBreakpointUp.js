import getViewportSize from 'utils/getViewportSize';

const breakpoints = {
	xs: 0,
	sm: 754,
	md: 992,
	lg: 1200,
	hd: 1400,
};

/**
 * Media breakpoint up
 *
 * @param  str breakpoint
 * @return bool
 */
export default function mediaBreakpointUp(breakpoint) {
	if (!breakpoints[breakpoint]) {
		const errorMessage = `Breakpoint '${breakpoint}' do not exist`;
		console.error(errorMessage);
		throw new Error(errorMessage);
	}

	return getViewportSize().width > breakpoints[breakpoint];
}
