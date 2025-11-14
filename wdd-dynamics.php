<?php

/**
 * Plugin Name: WDD Marketing Dynamics
 * Description: Measures user behavior and, with FCL, decides which modal/banner to display.
 * Version: 1.0.0
 * Author: Ivan Mušković
 * License: GPLv2 or later
 * Update URI: false
 */

if (!defined('ABSPATH')) exit;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/inc/Setup.php';
require __DIR__ . '/inc/Admin.php';
require __DIR__ . '/inc/Rest.php';
require __DIR__ . '/inc/Evaluator.php';
require __DIR__ . '/inc/Frontend.php';
require __DIR__ . '/inc/Helpers.php';

MarketingAutomation\Setup::init();
MarketingAutomation\Admin::init();
MarketingAutomation\Rest::init();
MarketingAutomation\Frontend::init();

register_activation_hook(__FILE__, [MarketingAutomation\Setup::class, 'create_table']);
