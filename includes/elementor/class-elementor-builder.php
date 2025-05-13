<?php
/**
 * CLC- Elementor Class
 *
 * @package tenpo-core
 * @version 1.0.0
 * @since 1.0.0
 */

namespace CreditLoanCalculator\Elementor;

/**
 *  Header Builder Class
 */
class ElementorBuilder {
    /**
     *  Constructor
     */
    public function __construct() {
        if ( in_array( 'elementor/elementor.php', (array) get_option( 'active_plugins', array() ), true ) ) {
            add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_category' ) );
            add_action( 'elementor/widgets/register', array( $this, 'add_elementor_elements' ), 10, 1 );
        }
    }

    /**
     * Add Elementor Category
     *
     * @param object $category Category.
     * @return void
     */
    public function add_elementor_category( $category ) {
        $category->add_category(
            'balcom-soft',
            array(
                'title'  => __( 'BalcomSoft', 'clc' ),
                'active' => true,
            )
        );
    }

    /**
     * Add Elementor Elements
     *
     * @param object $widgets_manager Widgets manager.
     * @return void
     */
    public function add_elementor_elements( $widgets_manager ) {
        $widgets_manager->register( new Calculator() );
    }
}
