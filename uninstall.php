<?php

if (! defined('WP_UNINSTALL_PLUGIN')) {
  exit;
}

global $wpdb;

/**
 * Table created by the plugin – safe to interpolate directly.
 * Plugin authors are allowed to drop their own tables without prepare().
 */
$wdd_dynamics_table = $wpdb->prefix . 'ma_subscribers';

$wpdb->query("DROP TABLE IF EXISTS `$wdd_dynamics_table`");

$wdd_dynamics_option_keys = [
  'ma_fcl_code',
  'ma_enable_modal',
  'ma_enable_banner',
  'ma_banner_text',
  'ma_banner_link',
  'ma_modal_text',
  'ma_modal_heading',
  'ma_banner_ttl_days',
  'ma_modal_ttl_days',
  'ma_enable_country_detection',
];

foreach ($wdd_dynamics_option_keys as $wdd_dynamics_key) {
  delete_option($wdd_dynamics_key);
}
