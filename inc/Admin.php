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
      'WDD Dynamics',
      'WDD Dynamics',
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
      <h1 style="margin-bottom: 10px; font-weight: 600;">WDD Dynamics</h1>
      <form method="post" action="options.php">
        <?php settings_fields('ma_settings'); ?>

        <p class="description">
          <strong style="margin-bottom:10px;">Varijable koje se mogu koristiti u pravilima:</strong><br>
          <code>$time_on_page</code> — sekunde korisnika na stranici<br>
          <code>$clicks</code> — broj klikova korisnika<br>
          <code>$visits</code> — broj posjeta korisnika (sprema se u cookie)<br>
          <code>$is_mobile</code> — Da li korisnik koristi desktop ili mobilni uređaj<br>
          <code>$country</code> — Država iz koje je korisnik (Country code: 'HR', 'EN', 'DE', 'IT')
        </p>

        <!-- Decision Engine -->
        <div class="postbox" style="margin-top:30px;">
          <h2 class="hndle" style="margin-left:10px;"><span> Engine za odluke</span></h2>
          <div class="inside">
            <table class="form-table" role="presentation">
              <tr>
                <th scope="row">FCL pravila</th>
                <td>

                  <p>
                    <label for="ma_fcl_examples"><strong>Brzi primjeri:</strong></label>
                    <select id="ma_fcl_examples" style="min-width:300px;">
                      <option value="">— Odaberi primjer —</option>
                      <option value="modal_10s">Prikaži modal nakon 10 sekundi</option>
                      <option value="banner_5clicks">Prikaži banner nakon 5 klikova</option>
                      <option value="banner_3visits">Prikaži banner nakon 3 posjeta</option>
                      <option value="modal_mobile">Prikaži modal samo na mobitelu</option>
                      <option value="banner_croatia">Prikaži banner samo za Hrvatsku</option>
                    </select>
                  </p>

                  <textarea name="ma_fcl_code" rows="12" style="width:100%;font-family:monospace;margin-top:10px;"><?php echo esc_textarea(get_option('ma_fcl_code', '')); ?></textarea>

                  <p class="description" style="margin-bottom: 20px;">
                    Ovdje definirate kada se prikazuje <strong>banner</strong> ili <strong>newsletter modal</strong>.
                    <br>Možete koristiti varijable: <code>$time_on_page</code>, <code>$clicks</code>, <code>$visits</code>, <code>$is_mobile</code>, <code>$country</code>.
                    Odluke se automatski evaluiraju svakih <strong>5 sekundi</strong> na frontendu,
                    prema pravilima koja definirate ovdje.
                    <br>To znači da ako korisnik ispuni uvjete (npr. $clicks > 5),
                    banner ili modal će se pojaviti unutar nekoliko sekundi. <br> Za promjenu FCL pravila ili bilo koje opcije kliknite plavi gumb "save changes" na dnu stranice.
                  </p>

                  <p><strong>Primjeri:</strong></p>
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
                    Napišite <code>$return = 'show_newsletter_modal'</code> ili <code>$return = 'show_discount_banner'</code>.<br>
                    Ako se ništa ne treba prikazati, vratite <code>$return = 'none'</code>.
                  </p>
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="ma_enable_country_detection">Omogući detekciju države (ipapi.co)</label>
                </th>
                <td>
                  <label>
                    <input type="checkbox" id="ma_enable_country_detection" name="ma_enable_country_detection" value="1" <?php checked(get_option('ma_enable_country_detection'), 1); ?> />
                    <span>Ova opcija šalje IP adresu korisnika servisu ipapi.co radi otkrivanja države.</span>
                  </label>
                  <p class="description">
                    Ako ovo isključiš, varijabla <code>$country</code> će imati vrijednost <code>'DISABLED'</code>.
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
                    ⚠️ <strong>Napomena:</strong> Ako ne koristite <em>newsletter modal</em> ili <em>banner</em>,
                    <strong>obavezno ih isključi</strong> u opcijama ispod.
                    To će spriječiti nepotrebne pozive prema serveru i ubrzati web stranicu.
                  </p>
                </th>
              </tr>
            </table>
          </div>
        </div>

        <!-- Newsletter Modal -->
        <div class="postbox" style="margin-top:30px;">
          <h2 class="hndle" style="margin-left:10px;"><span> Newsletter modal</span></h2>
          <div class="inside">
            <table class="form-table" role="presentation">
              <tr>
                <th scope="row">Omogući Newsletter modal</th>
                <td>
                  <input type="checkbox" name="ma_enable_modal" value="1" <?php checked(get_option('ma_enable_modal'), 1); ?>>
                </td>
              </tr>
              <tr>
                <th scope="row">Naslov za newsletter</th>
                <td>
                  <input type="text" name="ma_modal_heading" value="<?php echo esc_attr(get_option('ma_modal_heading', 'Pridruži se newsletteru')); ?>" class="regular-text">
                  <p>Naslov koji se prikazuje na newsletteru.</p>
                </td>
              </tr>
              <tr>
                <th scope="row">Tekst za newsletter</th>
                <td>
                  <input type="text" name="ma_modal_text" value="<?php echo esc_attr(get_option('ma_modal_text', 'Dobij novosti i ponude, prijavi se ispod!')); ?>" class="regular-text">
                  <p>Tekst koji se prikazuje ispod naslova.</p>
                </td>
              </tr>
              <tr>
                <th scope="row"><label for="ma_modal_ttl_days">Ponavljanje newslettera (TTL u danima)</label></th>
                <td>
                  <input
                    type="number"
                    id="ma_modal_ttl_days"
                    name="ma_modal_ttl_days"
                    value="<?php echo esc_attr(get_option('ma_modal_ttl_days', 7)); ?>"
                    min="1"
                    max="30" />
                  <p class="description">Nakon što se modal jednom pojavio korisniku, koliko dana mora proći prije nego se modal ponovno pojavi?</p>
                </td>
            </table>
          </div>
        </div>

        <!-- Discount Banner -->
        <div class="postbox" style="margin-top:30px;">
          <h2 class="hndle" style="margin-left:10px;"><span> Banner za popust</span></h2>
          <div class="inside">
            <table class="form-table" role="presentation">
              <tr>
                <th scope="row">Omogući banner za popust</th>
                <td>
                  <input type="checkbox" name="ma_enable_banner" value="1" <?php checked(get_option('ma_enable_banner'), 1); ?>>
                </td>
              </tr>
              <tr>
                <th scope="row">Tekst bannera</th>
                <td>
                  <input type="text" name="ma_banner_text" value="<?php echo esc_attr(get_option('ma_banner_text', 'Specijalna ponuda: Ostvari 10% popusta danas!')); ?>" class="regular-text">
                  <p>Tekst koji se prikazuje u banneru.</p>
                </td>
              </tr>
              <tr>
                <th scope="row">Link bannera</th>
                <td>
                  <input type="text" name="ma_banner_link" value="<?php echo esc_attr(get_option('ma_banner_link', '/shop')); ?>" class="regular-text code">
                  <p class="description">Link na koji vodi klik na banner.</p>
                </td>
              </tr>
              <tr>
                <th scope="row"><label for="ma_banner_ttl_days">Ponavljanje bannera (TTL u danima)</label></th>
                <td>
                  <input
                    type="number"
                    id="ma_banner_ttl_days"
                    name="ma_banner_ttl_days"
                    value="<?php echo esc_attr(get_option('ma_banner_ttl_days', 7)); ?>"
                    min="1"
                    max="30" />
                  <p class="description">Nakon što se banner jednom pojavio korisniku, koliko dana mora proći prije nego se banner ponovno pojavi?</p>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <?php submit_button(); ?>
      </form>

      <!-- Newsletter Subscribers -->
      <?php
      global $wpdb;
      $subscribers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ma_subscribers ORDER BY created_at DESC LIMIT 50");

      echo '<div class="postbox" style="margin-top:30px;"><h2 class="hndle" style="margin-left:10px;"><span> Pretplatnici na Newsletter</span></h2><div class="inside">';
      if ($subscribers) {
        echo '<table class="widefat striped fixed"><thead><tr><th>Email</th><th>Datum</th></tr></thead><tbody>';
        foreach ($subscribers as $s) {
          echo '<tr><td>' . esc_html($s->email) . '</td><td>' . esc_html($s->created_at) . '</td></tr>';
        }
        echo '</tbody></table>';
      } else {
        echo '<p>Ovdje će se prikazati prijave na newsletter.</p>';
      }
      echo '</div></div>';
      ?>
    </div>
<?php
  }
}
