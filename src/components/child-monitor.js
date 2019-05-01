import { Component } from '@wordpress/element';

class ChildMonitor extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			result: null,
		};
		this.checkChildren = this.checkChildren.bind( this );
	}

	componentDidMount() {
		this.interval = setInterval( this.checkChildren, 100 );
	}

	componentWillUnmount() {
		clearInterval( this.interval );
	}

	checkChildren() {
		if ( ! this.el ) {
			return;
		}
		const result = this.props.check( this.el );
		if ( this.state.result !== result ) {
			this.props.onChange( result );

			this.setState( {
				result,
			} );

			// If the optional "once" attribute has been specified, unbind the interval.
			if ( this.props.once ) {
				clearInterval( this.interval );
			}
		}
	}

	render() {
		const { children } = this.props;
		return (
			<div ref={ el => this.el = el }>
				{ children }
			</div>
		);
	}
}

export default ChildMonitor;
