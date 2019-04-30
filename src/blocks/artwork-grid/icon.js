/**
 * Render an SVG icon of a 4 x 2 grid of squares (representing images).
 *
 * From https://thenounproject.com/term/pack-of-dollars/131792/
 *
 * @return {Object}
 */
export default () => (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 20 20"
	>
		<g>
			<rect x="0" y="4" width="6" height="6" />
			<rect x="7" y="4" width="6" height="6" />
			<rect x="14" y="4" width="6" height="6" />
			<rect x="0" y="11" width="6" height="6" />
			<rect x="7" y="11" width="6" height="6" />
			<rect x="14" y="11" width="6" height="6" />
		</g>
	</svg>
);
