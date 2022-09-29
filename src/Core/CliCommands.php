<?php
/**
 * Cli commands class.
 *
 * @since 0.1.0
 * @package  Emplement\eQuotes
 * @subpackage Core
 */

declare( strict_types=1 );

namespace Emplement\eQuotes\Core;

use WP_CLI;

/**
 * CliCommands class
 *
 * Adds wp cli custom commands.
 */
final class CliCommands {
	/**
	 * Example command
	 * Usage example: wp e-quotes example
	 *
	 * @subcommand example
	 *
	 * @param array $args Args.
	 * @param array $args_assoc Associative args.
	 *
	 * @return void
	 */
	public function example( array $args, array $args_assoc ) {
		WP_CLI::line( __( 'Line of text', 'e-quotes' ) );
		WP_CLI::error( __( 'Error line', 'e-quotes' ) );
		WP_CLI::success( __( 'Success line', 'e-quotes' ) );
	}
}
