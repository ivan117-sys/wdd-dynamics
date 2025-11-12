<?php

namespace MarketingAutomation;

class Setup
{
  public static function init(): void
  {
    add_action('init', [__CLASS__, 'register_options']);
  }

  public static function register_options(): void
  {
    register_setting('ma_settings', 'ma_fcl_code');
    register_setting('ma_settings', 'ma_enable_modal');
    register_setting('ma_settings', 'ma_enable_banner');
    register_setting('ma_settings', 'ma_banner_text');
    register_setting('ma_settings', 'ma_banner_link');
    register_setting('ma_settings', 'ma_modal_text');
    register_setting('ma_settings', 'ma_modal_heading');
    register_setting('ma_settings', 'ma_banner_ttl_days', [
      'type' => 'integer',
      'default' => 7,
      'sanitize_callback' => 'absint',
    ]);
    register_setting('ma_settings', 'ma_modal_ttl_days', [
      'type' => 'integer',
      'default' => 7,
      'sanitize_callback' => 'absint',
    ]);
  }

  public static function create_table(): void
  {
    global $wpdb;
    $table = $wpdb->prefix . 'ma_subscribers';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
  ) $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
  }
}
