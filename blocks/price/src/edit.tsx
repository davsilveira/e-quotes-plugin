const {
	TextControl,
	Panel,
	PanelBody,
	PanelRow,
	ToggleControl
} = wp.components;
const { InspectorControls, useBlockProps } = wp.blockEditor;
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
		<>
			<InspectorControls>
				<Panel>
					<PanelBody title={ __( 'Settings', 'e-quotes' ) } initialOpen={ true }>
						<PanelRow>
							{ __( 'Display label:', 'e-quotes' ) }
						</PanelRow>
						<ToggleControl
							label={ __( 'Toggle label visibility', 'e-quotes' ) }
							checked={ attributes.displayLabel }
							onChange={ ( state ) => {
								setAttributes({
									displayLabel: state
								});
							} }
						/>
						<PanelRow>
							{ __( 'Display currency sign:', 'e-quotes' ) }
						</PanelRow>
						<ToggleControl
							label={ __( 'Toggle sign visibility', 'e-quotes' ) }
							checked={ attributes.displaySign }
							onChange={ ( state ) => {
								setAttributes({
									displaySign: state
								});
							} }
						/>
					</PanelBody>
				</Panel>
			</InspectorControls>
			<div { ...blockProps }>
				{ attributes.displaySign
					? <span className="e-quotes-currency-sign">{getCurrencySign()}</span>
					: null
				}
				<TextControl
					label={ attributes.displayLabel ? __('Price', 'equotes') : '' }
					value={attributes.price}
					type="number"
					help={__('Digit only numbers. Use , for decimal separator.', 'equotes')}
					onChange={(value) => setAttributes({ price: parseValue( value ) })}
				/>
			</div>
		</>
	);
}
