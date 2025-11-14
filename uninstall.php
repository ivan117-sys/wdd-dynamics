<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit;
}

global $wpdb;

$table = $wpdb->prefix . 'ma_subscribers';
$wpdb->query("DROP TABLE IF EXISTS {$table}");

$options = [
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

foreach ($options as $option) {
  delete_option($option);
}
