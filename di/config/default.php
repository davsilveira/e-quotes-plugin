<?php

declare( strict_types=1 );

namespace Emplement\eQuotes;

use function DI\get;

return [
	'Blocks' => [
		get( Blocks\Price::class ),
		get( Blocks\FinalSettings::class ),
	]
];
