const {
	TextControl,
	Panel,
	PanelBody,
	PanelRow,
	ToggleControl
} = wp.components;
const { InspectorControls, useBlockProps } = wp.blockEditor;
const { __ } = wp.i18n;
const { useEffect } = wp.element;

import { uniqueId } from 'lodash';
import './editor.scss';

export default function Edit( props ) {

	const { attributes, setAttributes } = props;

	const blockProps = useBlockProps({
		className: 'e-quotes-price-component'
	});

	const getCurrencySign = () => {

		if ( typeof eQuotes === 'undefined' ) {
			throw new Error( 'eQuotes not found. Make sure it is enqueued.' );
		}

		if ( ! eQuotes.has( 'pluginSettings' ) ) {
			throw new Error( 'eQuotes.pluginSettings not found.' );
		}

		return eQuotes.pluginSettings.currency === 'USD'
			? '$'
			: 'R$';
	}

	const parseValue = ( value: any ) => {
		return isNaN( parseFloat( value ) ) ? '' : parseFloat( value );
	}

	useEffect( () => {
		setAttributes( { priceId : uniqueId( 'e-quotes-price-' ) } );
	}, [] );

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
					label={ attributes.displayLabel ? __('Price', 'e-quotes') : '' }
					value={attributes.price}
					type="number"
					help={__('Only numbers. Use , for decimal separator.', 'e-quotes')}
					onChange={(value) => setAttributes({ price: parseValue( value ) })}
				/>
			</div>
		</>
	);
}
