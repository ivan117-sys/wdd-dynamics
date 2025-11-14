<?php

/**
 * Plugin Name: WDD Marketing Dynamics
 * Description: Measures user behavior and, with FCL, decides which modal/banner to display.
 * Version: 1.0.0
 * Author: Ivan Mušković
 * License: GPLv2 or later
 * Text Domain: wdd-marketing-dynamics
 * Domain Path: /languages
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

if (function_exists('wp_add_privacy_policy_content')) {
  add_action('admin_init', function () {
    $content = wp_kses_post(
      __(
        'Ovaj dodatak prati osnovne metrike ponašanja korisnika (vrijeme na stranici, broj klikova, broj posjeta) koristeći kolačiće u pregledniku i, opcionalno, šalje IP adresu servisu ipapi.co radi određivanja države korisnika. E-mail adrese unesene u newsletter modal pohranjuju se u WordPress bazu podataka u tablici &lt;prefix&gt;ma_subscribers. Administrator može izbrisati podatke kroz standardne alate WordPressa ili deaktivacijom / deinstalacijom dodatka.',
        'wdd-marketing-dynamics'
      )
    );

    wp_add_privacy_policy_content(
      'WDD Marketing Dynamics',
      '<p>' . $content . '</p>'
    );
  });
}

add_action('plugins_loaded', function () {
  load_plugin_textdomain(
    'wdd-marketing-dynamics',
    false,
    dirname(plugin_basename(__FILE__)) . '/languages'
  );
});
