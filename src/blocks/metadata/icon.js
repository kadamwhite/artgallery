/**
 * Render an SVG icon of a measuring triangle as JSX.
 *
 * @return {Object}
 */
export default () => (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		role="img"
		aria-hidden="true"
		focusable="false"
	>
		<path d="M 0,0 20,24 H 0 Z" />
		<g fill="white" stroke="none">
			<path d="m 5,11 7,9 H 5 Z" />
		</g>
		<g stroke="white" strokeWidth="1.25">
			<path d="M 0,8 H 2 Z" />
			<path d="M 0,12 H 2 Z" />
			<path d="M 0,16 H 2 Z" />
			<path d="M 0,20 H 2 Z" />
		</g>
	</svg>
);
