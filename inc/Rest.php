<?php

namespace MarketingAutomation;

class Rest
{
  public static function init()
  {
    add_action('rest_api_init', [__CLASS__, 'routes']);
  }

  public static function routes()
  {
    register_rest_route('ma/v1', '/track', [
      'methods'  => 'POST',
      'callback' => [__CLASS__, 'track'],
      'permission_callback' => '__return_true', // za PoC; u produkciji dodaj nonce provjeru
    ]);
  }

  public static function track($request)
  {
    $data         = json_decode($request->get_body(), true);
    $time_on_page = isset($data['time_on_page']) ? (int)$data['time_on_page'] : 0;
    $clicks       = isset($data['clicks']) ? (int)$data['clicks'] : 0;
    $visits       = isset($_COOKIE['ma_visits']) ? (int)$_COOKIE['ma_visits'] : 1;

    // spremi u cookie simple JSON (1h)
    $payload = wp_json_encode([
      'time_on_page' => $time_on_page,
      'clicks'       => $clicks,
      'visits'       => $visits
    ]);
    setcookie('ma_metrics', $payload, time() + 3600, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true);

    return rest_ensure_response(['ok' => true]);
  }
}
