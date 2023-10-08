<?php

declare( strict_types=1 );

namespace Emplement\eQuotes;

use function DI\get;
use function DI\create;
use function DI\autowire;

return [
	'Admin' => [
		'Dashboard' => get( Admin\Dashboard::class ),
		'SettingsPage' => create( Admin\Settings\Page::class )
			->constructor( get( Utils\AssetsManagement::class ) )
			->method( 'init' ),
		'Menu' => create( Admin\Menu::class )
			->method( 'init' )
	],
	'App' => [
		'PostTypes' => create( Commons\PostTypes::class )
			->method( 'init' ),
		'RegisterSettings' => create( Admin\Settings\RegisterSettings::class )
			->method( 'init' ),
		get( 'Blocks' ),
	],
	'Blocks' => [
		get( Blocks\Price::class ),
		get( Blocks\FinalSettings::class ),
	],
	Utils\AssetsManagement::class => autowire() // Autowired for caching purposes.
];
