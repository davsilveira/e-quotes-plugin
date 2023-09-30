const { TextControl } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { __ } = wp.i18n;
import './editor.scss';

export default function Edit( props ) {

	const { attributes, setAttributes } = props;

	const blockProps = useBlockProps({
		className: 'equotes-price-component'
	});

	const getCurrencySign = () => {

		if ( typeof EQUOTES === 'undefined' ) {
			throw new Error( 'Global EQUOTES not found. Make sure it is enqueued.' );
		}

		return EQUOTES.settings.currency === 'USD'
			? '$'
			: 'R$';
	}

	const parseValue = ( value: any ) => {
		return isNaN( parseFloat( value ) ) ? '' : parseFloat( value );
	}

	return (
		<div { ...blockProps }>
			<span className="e-quotes-currency-sign">{getCurrencySign()}</span>
			<TextControl
				label={__('Price', 'equotes')}
				value={attributes.price}
				type="number"
				help={__('Digit only numbers. Use , for decimal separator.', 'equotes')}
				onChange={(value) => setAttributes({ price: parseValue( value ) })}
			/>
		</div>
	);
}
