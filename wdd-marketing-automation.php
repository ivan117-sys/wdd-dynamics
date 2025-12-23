<?php

/**
 * Plugin Name: WDD Marketing Automation
 * Description: Measures user behavior and, with FCL, decides which modal/banner to display.
 * Version: 1.0.0
 * Author: Ivan Mušković
 * License: GPLv2 or later
 * Text Domain: wdd-marketing-automation
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) exit;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/inc/Setup.php';
require __DIR__ . '/inc/Admin.php';
require __DIR__ . '/inc/Rest.php';
require __DIR__ . '/inc/Evaluator.php';
require __DIR__ . '/inc/Frontend.php';
require __DIR__ . '/inc/Helpers.php';

WDD_Marketing_Automation\Setup::init();
WDD_Marketing_Automation\Admin::init();
WDD_Marketing_Automation\Rest::init();
WDD_Marketing_Automation\Frontend::init();

register_activation_hook(__FILE__, [WDD_Marketing_Automation\Setup::class, 'create_table']);

if (function_exists('wp_add_privacy_policy_content')) {
  add_action('admin_init', function () {
    $content = wp_kses_post(
      __(
        'Ovaj dodatak prati osnovne metrike ponašanja korisnika (vrijeme na stranici, broj klikova, broj posjeta) koristeći kolačiće u pregledniku i, opcionalno, šalje IP adresu servisu ipapi.co radi određivanja države korisnika. E-mail adrese unesene u newsletter modal pohranjuju se u WordPress bazu podataka u tablici &lt;prefix&gt;wddma_subscribers. Administrator može izbrisati podatke kroz standardne alate WordPressa ili deaktivacijom / deinstalacijom dodatka.',
        'wdd-marketing-automation'
      )
    );

    wp_add_privacy_policy_content(
      'WDD Marketing Dynamics',
      '<p>' . $content . '</p>'
    );
  });
}
