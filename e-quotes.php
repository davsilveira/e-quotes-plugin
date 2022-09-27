<?php
/**
 * Plugin Name:     e-quotes
 * Plugin URI:      https://github.com/davsilveira/e-quotes-plugin
 * Description:     Quotation WordPress plugin
 * Author:          Darvin da Silveira
 * Author URI:      https://www.linkedin.com/in/darvin-da-silveira-9b016155/
 * License:         GPL3+
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:     e-quotes
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package AwesomeTable
 */

declare(strict_types=1);

//use AwesomeTable\AwesomeTable;
//use AwesomeTable\Core\Requirements;

// Prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * PSR4 autoload
 */
if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Check if plugin is installable and uninstall if conditions are not met.
 */
//Requirements::check_installation_conditions( __FILE__ );

/**
 * Initialize plugin
 */
//add_action( 'plugins_loaded', [ AwesomeTable::instance(), 'init' ] );
