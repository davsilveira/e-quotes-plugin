<?php

namespace Emplement\eQuotes\Blocks;

use Emplement\eQuotes\eQuotes;

class Price {

	public function __construct() {

		add_action( 'init', [$this, 'register_block'] );
		add_action( 'wp_enqueue_scripts', [$this, 'register_front_script'] );
		add_action( 'admin_enqueue_scripts', [$this, 'register_admin_script'] );
	}

	public function register_front_script() {

		wp_enqueue_style(
			'equotes-price-front',
			eQuotes::url() . '/blocks/price/build/style-main.css',
			[],
			eQuotes::VERSION,
		);
	}

	public function register_admin_script() {

		wp_enqueue_style(
			'equotes-price-admin',
			eQuotes::url() . '/blocks/price/build/main.css',
			[],
			eQuotes::VERSION,
		);

		wp_enqueue_script(
			'equotes-price',
			eQuotes::url() . '/blocks/price/build/index.js',
			[
				'wp-block-editor',
				'wp-blocks',
				'wp-components',
				'wp-element',
				'wp-i18n',
			],
			eQuotes::VERSION,
			true
		);

		wp_add_inline_script(
			'equotes-price',
			'const EQUOTES = ' . wp_json_encode(
				[
					'settings' => [
						'currency' => get_option( 'e_quotes_currency', 'USD' ),
					]
				]
			),
			'before'
		);
	}

	public function register_block() {

		register_block_type_from_metadata(
			eQuotes::path() . '/blocks/price/src/block.json',
			array(
				'render_callback' => [ $this, 'render' ],
			)
		);
	}


	public function render( array $attributes ) : string {

		// Ensure unique values.
		$class_name = array_unique(
			// Filter any empty strings from the final array.
			array_filter(
				// Ensure our class is always present.
				array_merge( ['equotes-price-component', $attributes['className']] )
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
