<?php

declare( strict_types=1 );

namespace Emplement\eQuotes;

use function DI\get;
use function DI\create;

return [
	'AssetsManager' => create( Utils\AssetsManagement::class ),
	'Admin' => [
		'Dashboard' => create( Admin\Dashboard::class )
			->constructor( get( 'AssetsManager' ) )
			->method( 'init' ),
		'SettingsPage' => create( Admin\Settings\Page::class )
			->constructor( get( 'AssetsManager' ) )
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
			->constructor( get( 'AssetsManager' ) )
			->method( 'init' ),
	],
];
