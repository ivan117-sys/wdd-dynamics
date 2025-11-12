<?php

namespace MarketingAutomation;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class Rest
{
  public static function init(): void
  {
    add_action('rest_api_init', [__CLASS__, 'routes']);
  }

  public static function routes(): void
  {
    register_rest_route('ma/v1', '/subscribe', [
      'methods'  => 'POST',
      'callback' => [__CLASS__, 'subscribe'],
      'permission_callback' => '__return_true',
    ]);
    register_rest_route('ma/v1', '/track', [
      'methods'  => 'POST',
      'callback' => [__CLASS__, 'track'],
      'permission_callback' => '__return_true', // za PoC; u produkciji dodaj nonce provjeru
    ]);
    register_rest_route('ma/v1', '/evaluate', [
      'methods'  => 'POST',
      'callback' => [__CLASS__, 'evaluate'],
      'permission_callback' => '__return_true',
    ]);
  }

  public static function track(WP_REST_Request $request): WP_Error|WP_REST_Response
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

  public static function evaluate(): WP_Error|WP_REST_Response
  {
    $result = Evaluator::decide();

    $enable_modal  = get_option('ma_enable_modal');
    $enable_banner = get_option('ma_enable_banner');

    if ($result === 'show_newsletter_modal' && !$enable_modal) {
      $result = 'none';
    }

    if ($result === 'show_discount_banner' && !$enable_banner) {
      $result = 'none';
    }

    return rest_ensure_response(['decision' => $result]);
  }


  public static function subscribe(WP_REST_Request $request): WP_Error|WP_REST_Response
  {
    global $wpdb;

    /** @var \WP_REST_Request $request */
    $email = sanitize_email($request['email']);
    if (!is_email($email)) {
      return new WP_Error('invalid_email', 'Neispravna email adresa', ['status' => 400]);
    }

    $table = $wpdb->prefix . 'ma_subscribers';
    $wpdb->insert($table, [
      'email' => $email,
      'created_at' => current_time('mysql')
    ]);

    return rest_ensure_response(['ok' => true]);
  }
}
