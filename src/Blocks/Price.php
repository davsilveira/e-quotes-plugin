<?php
/**
 * Class Price
 *
 * A block class for rendering a price component.
 *
 * @since 1.0.0
 * @package Emplement\eQuotes\Blocks
 */

namespace Emplement\eQuotes\Blocks;

use Emplement\eQuotes\Abstracts\Block;

class Price extends Block {

	/**
	 * Get the name of the block.
	 *
	 * @return string The block name.
	 * @since 1.0.0
	 */
	public function name() : string {
		return 'price';
	}

	/**
	 * Get the block settings.
	 *
	 * @return array The block settings.
	 * @since 1.0.0
	 */
	public function settings() : array {
		return [
			'render_callback' => [ $this, 'render' ],
		];
	}

	/**
	 * Render the price block.
	 *
	 * @param array $attributes The block attributes.
	 * @return string The rendered HTML.
	 * @since 1.0.0
	 */
	public function render( array $attributes ) : string {

		ob_start();

		?>

		<div class="<?php echo esc_attr( implode( ' ', $this->ensure_class_name( 'e-quotes-price-component', $attributes ) ) ); ?>">
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
