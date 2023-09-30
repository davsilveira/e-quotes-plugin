const { TextControl } = wp.components;

export default function Edit(props) {
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
}
