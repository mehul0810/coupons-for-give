<?php
// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin version in SemVer format.
if ( ! defined( 'COUPONS4GIVE_VERSION' ) ) {
	define( 'COUPONS4GIVE_VERSION', '1.0.0' );
}

// Define plugin root File.
if ( ! defined( 'COUPONS4GIVE_PLUGIN_FILE' ) ) {
	define( 'COUPONS4GIVE_PLUGIN_FILE', dirname( dirname( __FILE__ ) ) . '/coupons-for-give.php' );
}

// Define plugin directory Path.
if ( ! defined( 'COUPONS4GIVE_PLUGIN_DIR' ) ) {
	define( 'COUPONS4GIVE_PLUGIN_DIR', plugin_dir_path( COUPONS4GIVE_PLUGIN_FILE ) );
}

// Define plugin directory URL.
if ( ! defined( 'COUPONS4GIVE_PLUGIN_URL' ) ) {
	define( 'COUPONS4GIVE_PLUGIN_URL', plugin_dir_url( COUPONS4GIVE_PLUGIN_FILE ) );
}