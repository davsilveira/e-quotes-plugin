<?php
/**
 * E-Quotes plugin
 *
 * Plugin for create awesome quotations
 *
 * PHP version 7.4
 *
 * @category Plugin
 * @package  Emplement\eQuotes
 * @author   Darvin da Silveira <davsilveira@gmail.com>
 * @license  GPL3+ https://www.gnu.org/licenses/gpl-3.0.txt
 * @link     https://github.com/davsilveira/e-quotes-plugin
 */

declare( strict_types=1 );

namespace Emplement\eQuotes;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;

//phpcs:disable PEAR.NamingConventions.ValidClassName.StartWithCapital

/**
 * Plugin main class
 *
 * @since 0.0.1
 */
final class eQuotes {
	/**
	 * Plugin version
	 */
	public const VERSION = '0.1.23';

	/**
	 * Dependency Admin\SettingsPage not found
	 */
	public const DEPENDENCY_NOT_FOUND = 1002;

	/**
	 * Our main instance
	 *
	 * @var eQuotes|null
	 */
	private static ?eQuotes $instance = null;

	/**
	 * Plugin absolute path
	 *
	 * @var string
	 */
	private static string $path = '';

	/**
	 * Plugin absolute url
	 *
	 * @var string
	 */
	private static string $url = '';

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	private Container $container;

	/**
	 * Plugin constructor
	 */
	public function __construct() {
		self::$url  = plugins_url( '', __DIR__ ) . '/';
		self::$path = plugin_dir_path( __DIR__ );
	}

	/**
	 * Return the absolute path for this plugin
	 *
	 * @return string
	 */
	public static function path(): string {
		return self::$path;
	}

	/**
	 * Return plugin's url
	 *
	 * @return string
	 */
	public static function url(): string {
		return self::$url;
	}

	/**
	 * Returns the plugin instance or instantiate a new one.
	 *
	 * @return eQuotes
	 */
	public static function instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Returns our DI Container.
	 *
	 * @return Container
	 */
	public function container(): Container {
		return $this->container;
	}

	/**
	 * Initialize plugin hooks
	 *
	 * @return void
	 * @throws NotFoundException Dependency not found.
	 */
	public function init(): void {

		// Loads our DI container.
		$this->container = ( new Core\DIContainer() )
			->enable_compilation( false )
			->build_container();

		// Loads translations.
		add_action( 'init', [ $this, 'load_text_domain' ] );

		// Admin only hooks.
		if ( is_admin() && ! wp_doing_ajax() ) {
			try {
				$this->container()->make( Admin\SettingsPage::class )->init();
			} catch ( DependencyException | NotFoundException $e ) {
				throw new NotFoundException(
					__( 'Dependency Admin\SettingsPage not found', 'e-quotes' ),
					self::DEPENDENCY_NOT_FOUND
				);
			}
		}
	}

	/**
	 * Load plugin text domain
	 *
	 * @return void
	 */
	public function load_text_domain(): void {
		load_plugin_textdomain(
			'e-quotes',
			false,
			plugin_basename( dirname( __FILE__ ) ) . '/../languages'
		);
	}
}
//phpcs:enable
