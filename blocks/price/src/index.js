const { registerBlockType } = wp.blocks;
const { TextControl } = wp.components;

registerBlockType('my-blocks/price', {
	title: 'Price Block',
	icon: 'shield',
	category: 'common',
	attributes: {
		price: {
			type: 'number',
			default: 0,
		},
	},
	edit: function(props) {
		const { attributes, setAttributes } = props;

		return (
			<div>
				<h2>Price Block</h2>
				<TextControl
					label="Price"
					value={attributes.price}
					onChange={(value) => setAttributes({ price: parseInt(value) })}
				/>
			</div>
		);
	},
	save: function() {
		// Save the block's content here.
	},
});
