<?php

if (! defined('WP_UNINSTALL_PLUGIN')) {
  exit;
}

global $wpdb;

/**
 * Table created by the plugin – safe to interpolate directly.
 * Plugin authors are allowed to drop their own tables without prepare().
 */
$wdd_dynamics_table = $wpdb->prefix . 'wddma_subscribers';

$wpdb->query("DROP TABLE IF EXISTS `$wdd_dynamics_table`");

$wdd_dynamics_option_keys = [
  'wddma_fcl_code',
  'wddma_enable_modal',
  'wddma_enable_banner',
  'wddma_banner_text',
  'wddma_banner_link',
  'wddma_modal_text',
  'wddma_modal_heading',
  'wddma_banner_ttl_days',
  'wddma_modal_ttl_days',
  'wddma_enable_country_detection',
];

foreach ($wdd_dynamics_option_keys as $wdd_dynamics_key) {
  delete_option($wdd_dynamics_key);
}
