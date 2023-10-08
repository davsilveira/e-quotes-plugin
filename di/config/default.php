<?php

declare( strict_types=1 );

namespace Emplement\eQuotes;

use function DI\get;
use function DI\create;
use function DI\autowire;

return [
	'Admin' => [
		'Dashboard' => create( Admin\Dashboard::class )
			->constructor( get( Utils\AssetsManagement::class ) )
			->method( 'init' ),
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
		create( Blocks\Price::class )
			->method( 'init' ),
//		get( Blocks\PriceGrid::class ),
	],
	Utils\AssetsManagement::class => autowire() // Autowired for caching purposes.
];
