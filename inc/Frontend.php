<?php

namespace MarketingAutomation;

class Frontend
{
  public static function init(): void
  {
    add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue']);
  }

  public static function enqueue(): void
  {
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
    ]);
  }

  public static function count_visit_cookie(): void
  {
    $visits = isset($_COOKIE['fclba_visits']) ? (int)$_COOKIE['fclba_visits'] + 1 : 1;
    setcookie('ma_visits', (string)$visits, time() + YEAR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true);
  }

  public static function maybe_render_ui(): void
  {
    $decision = Evaluator::decide();

    if ($decision === 'show_newsletter_modal' && get_option('ma_enable_modal')) {
      include __DIR__ . '/../templates/modal.php';
      self::inline_modal_css_js();
    }

    if ($decision === 'show_discount_banner' && get_option('ma_enable_banner')) {
      include __DIR__ . '/../templates/banner.php';
      self::inline_banner_css();
    }
  }

  private static function inline_modal_css_js(): void
  {
?>
    <style>
      .fclba-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999
      }

      .fclba-modal__card {
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        max-width: 520px;
        width: 92%
      }

      .fclba-close {
        cursor: pointer;
        float: right;
        font-size: 20px
      }
    </style>
    <script>
      (function() {
        const m = document.querySelector('.fclba-modal');
        if (!m) return;
        m.addEventListener('click', e => {
          if (e.target.classList.contains('fclba-modal') || e.target.classList.contains('fclba-close')) {
            m.remove();
          }
        });
      })();
    </script>
  <?php
  }

  private static function inline_banner_css(): void
  {
  ?>
    <style>
      .fclba-banner {
        position: fixed;
        bottom: 16px;
        left: 16px;
        right: 16px;
        background: #111;
        color: #fff;
        padding: 12px 16px;
        border-radius: 10px;
        z-index: 9998
      }

      .fclba-banner a {
        color: #fff;
        text-decoration: underline
      }
    </style>
<?php
  }
}
