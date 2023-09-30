<?php

declare( strict_types=1 );

namespace Emplement\eQuotes;

use function DI\get;

return [
	'AdminSettingsPage' => get( Admin\Settings\SettingsPage::class ),
	'PriceBlock' => get( Blocks\Price::class ),
];
