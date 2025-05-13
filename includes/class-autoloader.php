<?php
/**
 * Plugin Autoloader Class
 *
 * @author  Umid Akhmedjanov
 * @package umid
 * @version 1.0.0
 * @since   1.0.0
 */

namespace CreditLoanCalculator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Autoloader.
 *
 * Plugin autoloader handler class is responsible for
 * loading the different classes needed to run theme.
 */
final class Autoloader {

	/**
	 * Maps classes to file names.
	 *
	 * @var array
	 */
	private static array $classes_map = array(); //phpcs:ignore Generic.PHP.Syntax.PHPSyntax

	/**
	 * Run autoloader.
	 *
	 * Register a function as `__autoload()` implementation.
	 */
	public static function init() {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Preload files directly from Path
	 *
	 * @param string $path Path.
	 * @return void
	 */
	public function preload( $path ) {
		$items = glob( $path . DIRECTORY_SEPARATOR . '*' );
		foreach ( $items as $item ) {
			if ( is_file( $item ) ) {
				$basename = basename( $item );
				if ( 'php' === pathinfo( $item )['extension'] ) {
					include_once $item;
				}
			}
		}

		// Load files in subdirectories.
		foreach ( $items as $item ) {
			if ( is_dir( $item ) ) {
				$this->preload( $item );
			}
		}
	}

	/**
	 * Autoloader method.
	 *
	 * For a given class, check if it exists and load it.
	 * Fired by `spl_autoload_register` function.
	 *
	 * @param string $class Classname.
	 */
	private static function autoload( $class ) {
		if ( ! str_starts_with( $class, __NAMESPACE__ ) ) {
			return;
		}

		if ( ! class_exists( $class ) ) {
			$relative_class_name = preg_replace( '/^' . __NAMESPACE__ . str_replace( '\\\\', '\\', '\\\\' ) . '\/', '', $class ); // phpcs: ignore

			if ( isset( self::$classes_map[ $relative_class_name ] ) ) {
				$filepath = CLC_INC . '/' . self::$classes_map[ $relative_class_name ];
			} else {
				$filename = strtolower(
					preg_replace(
						array( '/([a-z])([A-Z])/', '/_/', '/' . str_replace( '\\\\', '\\', '\\\\' ) . '\/' ),
						array( '$1-$2', '-', DIRECTORY_SEPARATOR ),
						$relative_class_name
					)
				);

				$filepath = CLC_INC . '/' . $filename . '.php';
			}

			$classname = substr( $filepath, ( strrpos( $filepath, DIRECTORY_SEPARATOR ) + 1 ) );
			$filepath  = str_replace( $classname, 'class-' . $classname, $filepath );

			if ( is_readable( $filepath ) ) {
				require $filepath;
			}
		}
	}
}
