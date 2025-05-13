<?php
/**
 * Plugin Starter Class
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
 * Starter class for theme modules.
 *
 * Includes autoloader and Core class.
 *
 * @since 1.0.0
 */
class Starter {

	/**
	 * Starter constructor.
	 *
	 * Includes autoloader and Core class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		require_once CLC_INC . '/class-autoloader.php';

		$autoloader = new Autoloader();

		$autoloader->preload( CLC_INC . '/functions' );

		$autoloader::init();

        new Core();
	}

    /**
     * Activate Elementor widgets if Elementor plugin is active
     *
     * @return void
     */
}

new Starter();
