const { TextControl } = wp.components;

export default function Edit(props) {
	const { attributes, setAttributes } = props;

	return (
		<div>
			<h2>Preço do combustível</h2>
			<TextControl
				label="Preço"
				value={attributes.price}
				onChange={(value) => setAttributes({ price: parseInt(value) })}
			/>
			<TextControl
				label="It works with Typescript"
				value={100}
			/>
		</div>
	);
}
