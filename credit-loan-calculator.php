<?php
/**
 * Plugin Name: Credit Calculator
 * Plugin URI: https://github.com/aurshax
 * Description: This plugin creates loan calculator and save and integrate the data
 * Version: 1.0
 * Author: Umid Akhmedjanov
 * Author URI: https://www.linkedin.com/in/umid-akhmedjanov
 * Text Domain: clc
 *
 * @package clc
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

define( 'CLC_FILE', __FILE__ );
define( 'CLC_PATH', dirname( CLC_FILE ) );
define( 'CLC_URL', plugin_dir_url( CLC_FILE ) );
define( 'CLC_VERSION', '1.0.0' );
define( 'CLC_INC', CLC_PATH . '/includes' );
define( 'CLC_ASSETS', CLC_URL . 'assets' );
define( 'CLC_CSS', CLC_ASSETS . '/css' );
define( 'CLC_JS', CLC_ASSETS . '/js' );
define( 'CLC_SLUG', 'clc' );

require_once CLC_PATH .'/vendor/autoload.php';

if ( ! is_textdomain_loaded( 'clc' ) ) {
	load_plugin_textdomain(
		'clc',
		false,
		'clc/languages'
	);
}

require_once CLC_INC . '/class-starter.php';




