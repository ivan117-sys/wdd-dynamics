<?php

namespace MarketingAutomation;

class Frontend
{
  public static function init(): void
  {
    add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue']);
    add_action('template_redirect', [__CLASS__, 'count_visit_cookie']);
  }

  public static function enqueue(): void
  {
    wp_enqueue_style(
      'ma-frontend',
      plugins_url('../assets/css/frontend.css', __FILE__),
      [],
      '1.0.0'
    );

    wp_enqueue_script(
      'ma-tracker',
      plugins_url('../assets/js/tracker.js', __FILE__),
      [],
      '1.1.0',
      true
    );

    wp_localize_script('ma-tracker', 'MA', [
      'track' => esc_url_raw(rest_url('ma/v1/track')),
      'evaluate' => esc_url_raw(rest_url('ma/v1/evaluate')),
      'subscribe' => esc_url_raw(rest_url('ma/v1/subscribe')),
      'restNonce'  => wp_create_nonce('wp_rest'),
      'bannerText' => esc_html(get_option('ma_banner_text', 'Specijalna ponuda: Ostvari 10% popusta danas!')),
      'bannerLink' => esc_url(get_option('ma_banner_link', '/shop')),
      'modalHeading' => esc_html(get_option('ma_modal_heading', 'Pridruži se newsletteru')),
      'modalText' => esc_html(get_option('ma_modal_text', 'Dobij novosti i ponude, prijavi se ispod!')),
      'bannerTTL' => (int) get_option('ma_banner_ttl_days', 7),
      'modalTTL' => (int) get_option('ma_modal_ttl_days', 7),
      'enableModal' => (bool) get_option('ma_enable_modal'),
      'enableBanner' => (bool) get_option('ma_enable_banner')
    ]);
  }

  public static function count_visit_cookie(): void
  {

    if (php_sapi_name() === 'cli') return;
    if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'GET') return;

    $uri = esc_url_raw(wp_unslash($_SERVER['REQUEST_URI']));

    if (
      strpos($uri, '/wp-json/') === 0 ||
      strpos($uri, '/favicon.ico') === 0 ||
      preg_match('/\.(png|jpe?g|gif|svg|webp|css|js|ico|woff2?|ttf|eot)$/i', $uri)
    ) {
      return;
    }

    $visits = isset($_COOKIE['ma_visits']) ? (int)$_COOKIE['ma_visits'] + 1 : 1;

    setcookie(
      'ma_visits',
      (string)$visits,
      [
        'expires'  => time() + YEAR_IN_SECONDS,
        'path'     => COOKIEPATH ?: '/',
        'domain'   => COOKIE_DOMAIN,
        'secure'   => is_ssl(),
        'httponly' => true,
        'samesite' => 'Lax',
      ]
    );
  }
}
