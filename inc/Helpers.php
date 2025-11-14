<?php

namespace MarketingAutomation;

class Helpers
{
  public static function is_mobile(): bool
  {
    return wp_is_mobile();
  }

  public static function get_country(): string
  {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';

    if ($ip === '127.0.0.1' || $ip === '::1') {
      return 'LOCAL';
    }

    $response = wp_remote_get("https://ipapi.co/{$ip}/country/");

    if (is_wp_error($response)) {
      return 'UNKNOWN';
    }

    $country = trim(wp_remote_retrieve_body($response));

    return $country ?: 'UNKNOWN';
  }
}
