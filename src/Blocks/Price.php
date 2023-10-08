<?php

namespace Emplement\eQuotes\Blocks;

use Emplement\eQuotes\Abstracts\Block;

class Price extends Block {

	public function __construct() {

		$this->name = 'price';
		$this->is_restrict = false;
		$this->settings   = [
			'render_callback' => [ $this, 'render' ],
		];
	}

	public function render( array $attributes ) : string {

		// Ensure unique values.
		$class_name = array_unique(
			// Filter any empty strings from the final array.
			array_filter(
				// Ensure our class is always present.
				array_merge( ['e-quotes-price-component', $attributes['className']] )
			)
		);

		ob_start();

		?>

		<div class="<?php echo esc_attr( implode( ' ', $class_name ) ); ?>">
			<?php if ( isset( $attributes['displayLabel'] ) && ! empty( $attributes['displayLabel'] ) ) : ?>
				<label for="<?php echo esc_attr( $attributes['priceId'] ); ?>" class="e-quotes-price-label">
					<?php echo esc_html_e( 'Price', 'e-quotes' ); ?>
				</label>
			<?php endif; ?>
			<span class="e-quotes-price">
				<?php if ( isset( $attributes['displaySign'] ) && ! empty( $attributes['displaySign'] ) ) : ?>
					<span class="e-quotes-currency-sign">
						<?php echo esc_html( get_option( 'e_quotes_currency', 'USD' ) === 'USD' ? '$' : 'R$' ); ?>
					</span>
				<?php endif; ?>
				<input
					id="<?php echo esc_attr( $attributes['priceId'] ); ?>"
					class="e-quotes-value"
					value="<?php echo esc_attr( $attributes['price'] ?? 0 );  ?>"
					readonly="readonly"
				>
			</span>
		</div>

		<?php

		return ob_get_clean();
	}
}
