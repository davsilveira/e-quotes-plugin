const { TextControl } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { __ } = wp.i18n;
import './editor.scss';

export default function Edit( props ) {

	const { attributes, setAttributes } = props;

	const blockProps = useBlockProps({
		className: 'equotes-price-component'
	});

	const parseValue = ( value: any ) => {
		return isNaN( parseInt( value ) ) ? 0 : parseInt( value );
	}

	return (
		<div { ...blockProps }>
			<TextControl
				label={__('Price', 'equotes')}
				value={attributes.price}
				onChange={(value) => setAttributes({ price: parseValue( value ) })}
			/>
		</div>
	);
}
