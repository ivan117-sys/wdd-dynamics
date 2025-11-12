<?php

namespace MarketingAutomation;

class Frontend
{
  public static function init(): void
  {
    add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue']);
    add_action('wp', [__CLASS__, 'count_visit_cookie']);
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
      'rest' => esc_url_raw(rest_url('ma/v1/track')),
      'evaluate' => esc_url_raw(rest_url('ma/v1/evaluate')),
      'subscribe' => esc_url_raw(rest_url('ma/v1/subscribe')),
      'bannerText' => esc_html(get_option('ma_banner_text', 'Specijalna ponuda: Ostvari 10% popusta danas!')),
      'bannerLink' => esc_url(get_option('ma_banner_link', '/shop')),
      'modalHeading' => esc_html(get_option('ma_modal_heading', 'Pridruži se newsletteru')),
      'modalText' => esc_html(get_option('ma_modal_text', 'Dobij novosti i ponude, prijavi se ispod!')),
    ]);
  }

  public static function count_visit_cookie(): void
  {
    $visits = isset($_COOKIE['ma_visits']) ? (int)$_COOKIE['ma_visits'] + 1 : 1;
    setcookie('ma_visits', (string)$visits, time() + YEAR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true);
  }
}
