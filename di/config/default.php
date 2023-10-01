<?php

declare( strict_types=1 );

namespace Emplement\eQuotes;

use function DI\get;
use function DI\create;

return [
	'Admin' => [
		'Dashboard' => get( Admin\Dashboard::class ),
		'SettingsPage' => create( Admin\Settings\SettingsPage::class )
			->method( 'init' )
	],
	'App' => [
		get( 'Blocks' ),
		'RegisterSettings' => create( Admin\Settings\RegisterSettings::class )
			->method( 'init' )
	],
	'Blocks' => [
		get( Blocks\Price::class ),
		get( Blocks\FinalSettings::class ),
	]
];
