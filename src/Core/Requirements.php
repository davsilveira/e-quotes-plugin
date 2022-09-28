<?php
/**
 * Responsible to check the requirements for installation.
 * Abort installation in case of error.
 *
 * @since 0.1.0
 * @package  Emplement\eQuotes
 * @subpackage Core
 */

declare( strict_types=1 );

namespace Emplement\eQuotes\Core;

/**
 * Requirements class
 *
 * Check requirements for installation.
 */
final class Requirements {

	/**
	 * Minimal PHP version required
	 */
	public const MIN_PHP_VERSION = '7.4.0';

	/**
	 * Minimal WordPress version required
	 */
	public const MIN_WP_VERSION = 5.6;

	/**
	 * Check if conditions are met and show a message and uninstall the plugin if not
	 *
	 * @param string $plugin_file plugin filename.
	 *
	 * @return void
	 */
	public static function check_installation_conditions( string $plugin_file ): void {
		$conditions = self::get_installation_conditions();

		if ( $conditions['is_installable'] ) {
			return;
		}

		add_action(
			'admin_notices',
			function () use ( $plugin_file, $conditions ) {
				self::deactivate_plugin( $plugin_file );

				$conditions_message = '';

				if ( ! $conditions['php_version_ok'] ) {
					$conditions_message .= 'PHP ' . self::MIN_PHP_VERSION;
				}

				if ( ! $conditions['wp_version_ok'] ) {
					$conditions_message .= empty( $conditions_message ) ? '' : __( ' and ', 'e-quotes' );
					$conditions_message .= ' WordPress ' . self::MIN_WP_VERSION;
				}

				$conditions_message = trim( $conditions_message );

				$message = sprintf(
				/* translators: %1$s is replaced with "string" */
					__(
						'This plugin requires at least <strong>%s</strong>. Please fix the requirements and try again.',
						'e-quotes'
					),
					$conditions_message
				);
				?>
				<div class="notice notice-error">
					<p><?php wp_die( wp_kses( $message, [ 'strong' => [] ] ) ); ?></p>
				</div>
				<?php
			}
		);
	}

	/**
	 * Return if requirements for installation are met
	 *
	 * @return array
	 */
	private static function get_installation_conditions(): array {
		global $wp_version;

		$conditions = [];

		$conditions['php_version_ok'] = version_compare( self::MIN_PHP_VERSION, phpversion(), '<' );
		$conditions['wp_version_ok']  = $wp_version >= self::MIN_WP_VERSION;
		$conditions['is_installable'] = $conditions['php_version_ok'] && $conditions['wp_version_ok'];

		return $conditions;
	}

	/**
	 * Deactivate plugin from a given file
	 *
	 * @param string $plugin_file plugin filename.
	 *
	 * @return void
	 */
	private static function deactivate_plugin( string $plugin_file ): void {
		deactivate_plugins( plugin_basename( $plugin_file ), true );
	}

}
