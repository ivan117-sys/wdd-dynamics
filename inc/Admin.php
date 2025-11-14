<?php

namespace MarketingAutomation;

class Admin
{
  public static function init(): void
  {
    add_action('admin_menu', [__CLASS__, 'menu']);
    add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
  }

  public static function enqueue_assets(string $hook): void
  {

    if ($hook !== 'toplevel_page_wdd-dynamics') {
      return;
    }

    wp_enqueue_script(
      'ma-admin-fcl-examples',
      plugins_url('../assets/js/admin-fcl-examples.js', __FILE__),
      [],
      '1.0.0',
      true
    );
  }


  public static function menu(): void
  {
    add_menu_page(
      __('WDD Dynamics', 'wdd-marketing-dynamics'),
      __('WDD Dynamics', 'wdd-marketing-dynamics'),
      'manage_options',
      'wdd-dynamics',
      [__CLASS__, 'render'],
      'dashicons-chart-area',
      25
    );
  }

  public static function render(): void
  {
    if (!current_user_can('manage_options')) return;
?>
    <div class="wrap">
      <h1 style="margin-bottom: 10px; font-weight: 600;"><?php esc_html_e('WDD Dynamics', 'wdd-marketing-dynamics'); ?></h1>

      <form method="post" action="options.php">
        <?php settings_fields('ma_settings'); ?>

        <p class="description">
          <strong style="margin-bottom:10px;"><?php esc_html_e('Varijable koje se mogu koristiti u pravilima:', 'wdd-marketing-dynamics'); ?></strong><br>
          <code>$time_on_page</code> — <?php esc_html_e('sekunde korisnika na stranici', 'wdd-marketing-dynamics'); ?><br>
          <code>$clicks</code> — <?php esc_html_e('broj klikova korisnika', 'wdd-marketing-dynamics'); ?><br>
          <code>$visits</code> — <?php esc_html_e('broj posjeta korisnika (sprema se u cookie)', 'wdd-marketing-dynamics'); ?><br>
          <code>$is_mobile</code> — <?php esc_html_e('Da li korisnik koristi desktop ili mobilni uređaj', 'wdd-marketing-dynamics'); ?><br>
          <code>$country</code> — <?php esc_html_e("Država iz koje je korisnik (Country code: 'HR', 'EN', 'DE', 'IT')", 'wdd-marketing-dynamics'); ?>
        </p>

        <!-- Decision Engine -->
        <div class="postbox" style="margin-top:30px;">
          <h2 class="hndle" style="margin-left:10px;"><span><?php esc_html_e('Engine za odluke', 'wdd-marketing-dynamics'); ?></span></h2>
          <div class="inside">
            <table class="form-table" role="presentation">
              <tr>
                <th scope="row"><?php esc_html_e('FCL pravila', 'wdd-marketing-dynamics'); ?></th>
                <td>

                  <p>
                    <label for="ma_fcl_examples">
                      <strong><?php esc_html_e('Brzi primjeri:', 'wdd-marketing-dynamics'); ?></strong>
                    </label>
                    <select id="ma_fcl_examples" style="min-width:300px;">
                      <option value=""><?php esc_html_e('— Odaberi primjer —', 'wdd-marketing-dynamics'); ?></option>
                      <option value="modal_10s"><?php esc_html_e('Prikaži modal nakon 10 sekundi', 'wdd-marketing-dynamics'); ?></option>
                      <option value="banner_5clicks"><?php esc_html_e('Prikaži banner nakon 5 klikova', 'wdd-marketing-dynamics'); ?></option>
                      <option value="banner_3visits"><?php esc_html_e('Prikaži banner nakon 3 posjeta', 'wdd-marketing-dynamics'); ?></option>
                      <option value="modal_mobile"><?php esc_html_e('Prikaži modal samo na mobitelu', 'wdd-marketing-dynamics'); ?></option>
                      <option value="banner_croatia"><?php esc_html_e('Prikaži banner samo za Hrvatsku', 'wdd-marketing-dynamics'); ?></option>
                    </select>
                  </p>

                  <textarea name="ma_fcl_code" rows="12" style="width:100%;font-family:monospace;margin-top:10px;"><?php echo esc_textarea(get_option('ma_fcl_code', '')); ?></textarea>

                  <p class="description" style="margin-bottom: 20px;">
                    <?php esc_html_e('Ovdje definirate kada se prikazuje', 'wdd-marketing-dynamics'); ?>
                    <strong><?php esc_html_e('banner', 'wdd-marketing-dynamics'); ?></strong>
                    <?php esc_html_e('ili', 'wdd-marketing-dynamics'); ?>
                    <strong><?php esc_html_e('newsletter modal', 'wdd-marketing-dynamics'); ?></strong>.
                    <br><?php esc_html_e('Možete koristiti varijable:', 'wdd-marketing-dynamics'); ?>
                    <code>$time_on_page</code>, <code>$clicks</code>, <code>$visits</code>, <code>$is_mobile</code>, <code>$country</code>.
                    <br><?php esc_html_e('Odluke se automatski evaluiraju svakih 5 sekundi na frontendu, prema pravilima koja definirate ovdje.', 'wdd-marketing-dynamics'); ?>
                    <br><?php esc_html_e('To znači da ako korisnik ispuni uvjete (npr. $clicks > 5), banner ili modal će se pojaviti unutar nekoliko sekundi.', 'wdd-marketing-dynamics'); ?>
                    <br><?php esc_html_e('Za promjenu FCL pravila ili bilo koje opcije kliknite plavi gumb "spremi promjene" na dnu stranice.', 'wdd-marketing-dynamics'); ?>
                  </p>

                  <p><strong><?php esc_html_e('Primjeri:', 'wdd-marketing-dynamics'); ?></strong></p>

                  <!-- Primjeri koda ostaju neprevedeni (Opcija A) -->
                  <pre style="background:#f9f9f9;border:1px solid #ddd;padding:10px;border-radius:6px;">
// Ako je korisnik tek došao na stranicu → pokaži modal
if ($time_on_page == 0) {
  $return = 'show_newsletter_modal';
} else {
  $return = 'none';
}

// Ako je kliknuo više od 5 puta → pokaži banner
if ($clicks > 5) {
  $return = 'show_discount_banner';
} else {
  $return = 'none';
}

// Ako korisnik koristi mobitel → pokaži modal
if ($is_mobile == true) {
  $return = 'show_newsletter_modal';
} else {
  $return = 'none';
}

// Ako je korisnik iz Hrvatske → pokaži banner
if ($country == "HR") {
  $return = 'show_discount_banner';
} else {
  $return = 'none';
}
                  </pre>

                  <p class="description">
                    <?php esc_html_e('Napišite', 'wdd-marketing-dynamics'); ?> <code>$return = 'show_newsletter_modal'</code>
                    <?php esc_html_e('ili', 'wdd-marketing-dynamics'); ?> <code>$return = 'show_discount_banner'</code>.<br>
                    <?php esc_html_e('Ako se ništa ne treba prikazati, vratite', 'wdd-marketing-dynamics'); ?> <code>$return = 'none'</code>.
                  </p>
                </td>
              </tr>

              <tr>
                <th scope="row">
                  <label for="ma_enable_country_detection">
                    <?php esc_html_e('Omogući detekciju države', 'wdd-marketing-dynamics'); ?>
                  </label>
                </th>
                <td>
                  <label>
                    <input type="checkbox" id="ma_enable_country_detection" name="ma_enable_country_detection" value="1" <?php checked(get_option('ma_enable_country_detection'), 1); ?> />
                    <span><?php esc_html_e('Ova opcija šalje IP adresu korisnika servisu ipapi.co radi detekcije države.', 'wdd-marketing-dynamics'); ?></span>
                  </label>
                  <p class="description">
                    <?php esc_html_e("Ako je ova opcija isključena, varijabla", 'wdd-marketing-dynamics'); ?>
                    <code>$country</code>
                    <?php esc_html_e("neće se uzimati u obzir kod analitike.", 'wdd-marketing-dynamics'); ?>
                  </p>
                </td>
              </tr>

              <tr>
                <th scope="row" colspan="2">
                  <p style="
                    background:#fffbea;
                    border-left:4px solid #ffcc00;
                    padding:12px;
                    margin-top:10px;
                    font-size:14px;
                  ">
                    ⚠️ <strong><?php esc_html_e('Napomena:', 'wdd-marketing-dynamics'); ?></strong>
                    <?php esc_html_e('Ako ne koristite newsletter modal ili banner, obavezno ih isključite u opcijama ispod. To će spriječiti nepotrebne pozive prema serveru i ubrzati web stranicu.', 'wdd-marketing-dynamics'); ?>
                  </p>
                </th>
              </tr>
            </table>
          </div>
        </div>

        <!-- Newsletter Modal -->
        <div class="postbox" style="margin-top:30px;">
          <h2 class="hndle" style="margin-left:10px;"><span><?php esc_html_e('Newsletter modal', 'wdd-marketing-dynamics'); ?></span></h2>
          <div class="inside">
            <table class="form-table" role="presentation">
              <tr>
                <th scope="row"><?php esc_html_e('Omogući Newsletter modal', 'wdd-marketing-dynamics'); ?></th>
                <td>
                  <input type="checkbox" name="ma_enable_modal" value="1" <?php checked(get_option('ma_enable_modal'), 1); ?>>
                </td>
              </tr>
              <tr>
                <th scope="row"><?php esc_html_e('Naslov za newsletter', 'wdd-marketing-dynamics'); ?></th>
                <td>
                  <input
                    type="text"
                    name="ma_modal_heading"
                    value="<?php echo esc_attr(get_option('ma_modal_heading', __('Pridruži se newsletteru', 'wdd-marketing-dynamics'))); ?>"
                    class="regular-text">
                  <p><?php esc_html_e('Naslov koji se prikazuje na newsletteru.', 'wdd-marketing-dynamics'); ?></p>
                </td>
              </tr>
              <tr>
                <th scope="row"><?php esc_html_e('Tekst za newsletter', 'wdd-marketing-dynamics'); ?></th>
                <td>
                  <input
                    type="text"
                    name="ma_modal_text"
                    value="<?php echo esc_attr(get_option('ma_modal_text', __('Dobij novosti i ponude, prijavi se ispod!', 'wdd-marketing-dynamics'))); ?>"
                    class="regular-text">
                  <p><?php esc_html_e('Tekst koji se prikazuje ispod naslova.', 'wdd-marketing-dynamics'); ?></p>
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="ma_modal_ttl_days">
                    <?php esc_html_e('Ponavljanje newslettera (TTL u danima)', 'wdd-marketing-dynamics'); ?>
                  </label>
                </th>
                <td>
                  <input
                    type="number"
                    id="ma_modal_ttl_days"
                    name="ma_modal_ttl_days"
                    value="<?php echo esc_attr(get_option('ma_modal_ttl_days', 7)); ?>"
                    min="1"
                    max="30" />
                  <p class="description">
                    <?php esc_html_e('Nakon što se modal jednom pojavio korisniku, koliko dana mora proći prije nego se modal ponovno pojavi?', 'wdd-marketing-dynamics'); ?>
                  </p>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <!-- Discount Banner -->
        <div class="postbox" style="margin-top:30px;">
          <h2 class="hndle" style="margin-left:10px;"><span><?php esc_html_e('Banner za popust', 'wdd-marketing-dynamics'); ?></span></h2>
          <div class="inside">
            <table class="form-table" role="presentation">
              <tr>
                <th scope="row"><?php esc_html_e('Omogući banner za popust', 'wdd-marketing-dynamics'); ?></th>
                <td>
                  <input type="checkbox" name="ma_enable_banner" value="1" <?php checked(get_option('ma_enable_banner'), 1); ?>>
                </td>
              </tr>
              <tr>
                <th scope="row"><?php esc_html_e('Tekst bannera', 'wdd-marketing-dynamics'); ?></th>
                <td>
                  <input
                    type="text"
                    name="ma_banner_text"
                    value="<?php echo esc_attr(get_option('ma_banner_text', __('Specijalna ponuda: Ostvari 10% popusta danas!', 'wdd-marketing-dynamics'))); ?>"
                    class="regular-text">
                  <p><?php esc_html_e('Tekst koji se prikazuje u banneru.', 'wdd-marketing-dynamics'); ?></p>
                </td>
              </tr>
              <tr>
                <th scope="row"><?php esc_html_e('Link bannera', 'wdd-marketing-dynamics'); ?></th>
                <td>
                  <input
                    type="text"
                    name="ma_banner_link"
                    value="<?php echo esc_attr(get_option('ma_banner_link', '/shop')); ?>"
                    class="regular-text code">
                  <p class="description">
                    <?php esc_html_e('Link na koji vodi klik na banner.', 'wdd-marketing-dynamics'); ?>
                  </p>
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="ma_banner_ttl_days">
                    <?php esc_html_e('Ponavljanje bannera (TTL u danima)', 'wdd-marketing-dynamics'); ?>
                  </label>
                </th>
                <td>
                  <input
                    type="number"
                    id="ma_banner_ttl_days"
                    name="ma_banner_ttl_days"
                    value="<?php echo esc_attr(get_option('ma_banner_ttl_days', 7)); ?>"
                    min="1"
                    max="30" />
                  <p class="description">
                    <?php esc_html_e('Nakon što se banner jednom pojavio korisniku, koliko dana mora proći prije nego se banner ponovno pojavi?', 'wdd-marketing-dynamics'); ?>
                  </p>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <?php submit_button(__('Spremi promjene', 'wdd-marketing-dynamics')); ?>
      </form>

      <!-- Newsletter Subscribers -->
      <?php
      global $wpdb;
      $subscribers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ma_subscribers ORDER BY created_at DESC LIMIT 50");

      echo '<div class="postbox" style="margin-top:30px;"><h2 class="hndle" style="margin-left:10px;"><span>' . esc_html__('Pretplatnici na Newsletter', 'wdd-marketing-dynamics') . '</span></h2><div class="inside">';

      if ($subscribers) {
        echo '<table class="widefat striped fixed"><thead><tr><th>' . esc_html__('Email', 'wdd-marketing-dynamics') . '</th><th>' . esc_html__('Datum', 'wdd-marketing-dynamics') . '</th></tr></thead><tbody>';
        foreach ($subscribers as $s) {
          echo '<tr><td>' . esc_html($s->email) . '</td><td>' . esc_html($s->created_at) . '</td></tr>';
        }
        echo '</tbody></table>';
      } else {
        echo '<p>' . esc_html__('Ovdje će se prikazati prijave na newsletter.', 'wdd-marketing-dynamics') . '</p>';
      }
      echo '</div></div>';
      ?>
    </div>
<?php
  }
}
