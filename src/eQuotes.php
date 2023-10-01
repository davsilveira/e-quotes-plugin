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

declare(strict_types=1);

namespace Emplement\eQuotes;

use DI\Container;
use DI\NotFoundException;

//phpcs:disable PEAR.NamingConventions.ValidClassName.StartWithCapital

/**
 * Plugin main class
 *
 * @since 0.0.1
 */
final class eQuotes {

	/**
	 * Our main instance
	 *
	 * @var eQuotes|null
	 */
	private static ?eQuotes $instance = null;

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	private Container $container;

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
	 * @throws NotFoundException|\DI\DependencyException Dependency not found.
	 */
	public function init(): void {

		// Loads our DI container.
		$this->container = ( new Core\DIContainer() )
			->enable_compilation( false )
			->build_container();

		// Loads translations.
		add_action( 'init', [ $this, 'load_text_domain' ] );

		// Load all blocks.
		$this->container()->make( 'Blocks' );

		// Register global settings.
		$this->container()->make( Admin\Settings\RegisterSettings::class )->init();

		// Admin only hooks.
		if ( is_admin() && ! wp_doing_ajax() ) {
			$this->container()->make( Admin\Settings\SettingsPage::class )->init();
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
