<?php

namespace Emplement\eQuotes\Blocks;

use Emplement\eQuotes\Abstracts\Block;

class FinalSettings extends Block {

	public function __construct() {

		$this->name = 'final-settings';
		$this->settings   = [
			'render_callback' => [ $this, 'render' ],
		];

//		$this->init();
	}

	public function render( array $attributes ) : string {

		ob_start();

		?>

		<div>Final Settings</div>

		<?php

		return ob_get_clean();
	}
}
