<?php

namespace MarketingAutomation;

class Admin
{
  public static function init(): void
  {
    add_action('admin_menu', [__CLASS__, 'menu']);
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
      <h1>WDD Dynamics</h1>
      <form method="post" action="options.php">
        <?php settings_fields('ma_settings'); ?>
        <table class="form-table" role="presentation">
          <tr>
            <th scope="row">FCL rules (decision engine)</th>
            <td>
              <textarea name="ma_fcl_code" rows="12" style="width:100%;font-family:monospace;"><?php echo esc_textarea(get_option('ma_fcl_code', "")); ?></textarea>
              <p class="description">Write FCL logic. Without &lt;?php tags.</p>
            </td>
          </tr>
          <tr>
            <th scope="row">Omogući modal</th>
            <td>
              <input type="checkbox" name="ma_enable_modal" value="1" <?php checked(get_option('ma_enable_modal'), 1); ?>>
            </td>
          </tr>

          <tr>
            <th scope="row">Omogući banner</th>
            <td>
              <input type="checkbox" name="ma_enable_banner" value="1" <?php checked(get_option('ma_enable_banner'), 1); ?>>
            </td>
          </tr>
          <tr>
            <th scope="row">Tekst bannera</th>
            <td>
              <input type="text" name="ma_banner_text" value="<?php echo esc_attr(get_option('ma_banner_text', 'Specijalna ponuda: Ostvari 10% popusta danas!'))  ?>">
              <p>
                Tekst koji se prikazuje na banneru
              </p>
            </td>
          </tr>
          <tr>
            <th scope="row">Link bannera</th>
            <td>
              <input type="text" name="ma_banner_link" value="<?php echo esc_attr(get_option('ma_banner_link', '/shop')); ?>" class="regular-text code">
              <p class="description">Link od stranice na koji vodi banner.</p>
            </td>
          </tr>
        </table>
        <?php submit_button(); ?>
      </form>

      <h2>Primjer varijabli</h2>
      <pre><code> $time_on_page // seconds on page
  $clicks       // number of clicks
  $visits       // number of visits (cookie)
      </code></pre>
    </div>
<?php
  }
}
