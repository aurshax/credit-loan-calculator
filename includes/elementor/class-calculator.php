<?php
/**
 * BalcomSoft - Loan Calculator Widget Class
 *
 * @package clc
 * @version 1.0.0
 * @since 1.0.0
 */

namespace CreditLoanCalculator\Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

/**
 *  Loan Calculator Widget
 */
class Calculator extends Widget_Base {
    /**
     * Elementor Get Name
     *
     * @return string
     */
    public function get_name() {
        return 'clc_calculator';
    }

    /**
     * Get Scripts Dependencies
     *
     * @return array
     */
    public function get_script_depends() {
        return array( 'jquery-ui', 'clc-calculator' );
    }

    /**
     *  Get Style Dependencies
     *
     * @return string[]
     */
    public function get_style_depends() {
        return array(  'jquery-ui', 'clc-calculator' );
    }

    /**
     * Elementor Get Title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Calculator', 'clc' );
    }

    /**
     * Elementor Get Icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-code';
    }

    /**
     * Elementor Get Parent Categories
     *
     * @return string[]
     */
    public function get_categories() {
        return array( 'balcom-soft' );
    }

    /**
     * Elementor Get Keywards
     *
     * @return string[]
     */
    public function get_keywords() {
        return array( 'Loan Calculator' );
    }

    /**
     * Register Controls
     *
     * @return void
     */


    protected function register_controls() {
        // Content Tab Start.
        $this->start_controls_section(
            'section__icon',
            array(
                'label' => esc_html__( 'Content', 'clc' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_responsive_control(
            'label',
            array(
                'label'       => esc_html__( 'Label', 'clc' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Type your title here', 'clc' ),
                'value' => 'Chọn số tiền'
            )
        );

        $this->add_responsive_control(
            'button_label',
            array(
                'label'       => esc_html__( 'Button Label', 'clc' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Type your text here', 'clc' ),
                'value' => 'Nhận'
            )
        );

        $this->add_responsive_control(
            'space_gap',
            array(
                'label'      => esc_html__( 'Gap', 'clc' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'max' => 200,
                        'min' => 0,
                    ),
                ),
                'size_units' => array( 'px', '%', 'rem', 'em', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tenpo-iconed-list-item' => 'gap: {{SIZE}}{{UNIT}};',
                ),
                'default'    => array(
                    'size' => 8,
                ),
                'condition'  => array(
                    'show_text' => 'block',
                ),
            )
        );



        $this->end_controls_section();
        // Content Tab End.


    }

    /**
     * Elementor - Render Widget
     *
     * @return void
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $label = !empty($settings['label']) ? $settings['label'] : 'Chọn số tiền';
        $button_label = !empty($settings['button_label']) ? $settings['button_label'] : 'Nhận';

        ?>

        <div class="cls-loan-calculator-wrapper">
            <h3><?php echo esc_html($label) ?></h3>

            <input type="text" id="cls-amount" readonly>

            <div id="cls-loan-calculator-slider" class="cls-loan-calculator-slider"></div>

            <div class="cls-range-min-max">
                <div class="cls-min">500 000 ₫</div>
                <div class="cls-max">200 000 000  ₫</div>
            </div>

            <button id="cls-submit-for-loan" class="cls-submit"><span><?php echo esc_html($button_label) ?></span><span class="cls-amount"></span> </button>
        </div>
        <?php
    }
}
