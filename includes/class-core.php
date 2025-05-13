<?php
/**
 * Class Admin
 *
 * @author  Umid Akhmedjanov
 * @package umid
 * @version 1.0.0
 * @since   1.0.0
 */

namespace CreditLoanCalculator;

use CreditLoanCalculator\Classes\ContactFormField;
use CreditLoanCalculator\Elementor\ElementorBuilder;
use CreditLoanCalculator\PostTypes\Submissions;

/**
 *  Class Admin
 */
class Core {

    /**
     *  Constructor
     */
    public function __construct() {
        new Submissions();
        new ElementorBuilder();
        new ContactFormField();

        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Enqueue Scripts & Styles
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'clc-nouislider', CLC_ASSETS . '/css/nouislider.min.css', array(), CLC_VERSION, 'all' );
        wp_enqueue_style( 'clc-calculator', CLC_ASSETS . '/css/calculator.css', array(), CLC_VERSION, 'all' );
        wp_enqueue_style( 'clc-register', CLC_ASSETS . '/css/register.css', array(), CLC_VERSION, 'all' );

        wp_register_script('clc-js-cookie', CLC_ASSETS . '/js/js-cookie.min.js', [], CLC_VERSION, true);
        wp_register_script('clc-wnumb', CLC_ASSETS . '/js/wNumb.min.js', [], CLC_VERSION, true);
        wp_register_script('clc-nouislider', CLC_ASSETS . '/js/nouislider.min.js', [], CLC_VERSION, true);
        wp_register_script('clc-calculator', CLC_ASSETS . '/js/calculator.js', ['jquery', 'clc-wnumb', 'clc-nouislider'], CLC_VERSION, true);
        wp_register_script('clc-register', CLC_ASSETS . '/js/registration.js', ['jquery', 'clc-js-cookie'], CLC_VERSION, true);

        $reg_link = get_clc_settings('clc-page-link');

        wp_localize_script( 'clc-calculator', 'clc', array(
            'registration_link' => $reg_link
        ) );
    }
}
