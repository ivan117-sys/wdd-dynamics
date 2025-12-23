<?php
if (! defined('WP_UNINSTALL_PLUGIN')) {
  exit;
}

global $wpdb;

$wddma_dynamics_table = $wpdb->prefix . 'wddma_subscribers';

$wddma_table = esc_sql($wddma_dynamics_table);

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
$wpdb->query("DROP TABLE IF EXISTS `$wddma_table`");

$wddma_dynamics_option_keys = [
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

foreach ($wddma_dynamics_option_keys as $wddma_dynamics_key) {
  delete_option($wddma_dynamics_key);
}
