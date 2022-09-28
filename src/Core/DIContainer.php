<?php
/**
 * Responsible to adds all our container definitions
 *
 * @since 0.1.0
 * @package  Emplement\eQuotes
 * @subpackage Core
 */

declare( strict_types=1 );

namespace Emplement\eQuotes\Core;

use DI;
use Exception;
use RuntimeException;

/**
 * DIContainer class
 *
 * PSR11 Container definition class
 */
class DIContainer {

	/**
	 * Error while trying to build the php-di container.
	 */
	const ERROR_BUILD_CONTAINER = 1001;

	/**
	 * Container folder path without final trailing slash.
	 *
	 * @var string
	 */
	private string $di_path = __DIR__ . '/../../di';

	/**
	 * Tell if is compilation enable
	 *
	 * @var bool
	 */
	private bool $enable_compilation = true;

	/**
	 * Toggle container compilation.
	 *
	 * @param bool $enabled Enable or disable container compilation.
	 *
	 * @return DIContainer
	 */
	public function enable_compilation( bool $enabled = true ): DIContainer {
		$this->enable_compilation = $enabled;
		return $this;
	}

	/**
	 * Build and return a new container.
	 *
	 * @param string|null $config_file Config file path.
	 *
	 * @return DI\Container
	 * @throws RuntimeException Error while trying to build a new PSR11 DI Container.
	 */
	public function build_container( string $config_file = '' ): DI\Container {
		try {
			if ( ! is_readable( $config_file ) ) {
				$config_file = $this->di_path . '/config/default.php';
			}

			$builder = new DI\ContainerBuilder();
			$builder->addDefinitions( $config_file );

			if ( $this->enable_compilation ) {
				$builder->enableCompilation( $this->di_path . '/cache' );
			}

			return $builder->build();
		} catch ( Exception $exception ) {
			throw new RuntimeException(
				__( 'Error while trying to build the php-di container.', 'e-quotes' ),
				self::ERROR_BUILD_CONTAINER
			);
		}
	}
}
