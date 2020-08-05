<?php
/**
 * Plugin Name: Coupons for Give
 * Version: 1.0.0
 * Author: Mehul Gohil
 */

namespace MG\Give\Coupons;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/config/constants.php';

// Automatically loads files used throughout the plugin.
require_once 'vendor/autoload.php';

// Initialize the plugin.
$plugin = new Plugin();
$plugin->register();