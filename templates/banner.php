<?php
$banner_link = esc_url(get_option('ma_banner_link', '/shop'));
$banner_text = esc_html(get_option('ma_banner_text', 'Specijalna ponuda: Ostvari 10% popusta danas!'));

?>

<div class="ma-banner">
  <div class="ma-banner__text">
    <a class="banner-link" href="<?php echo $banner_link; ?>">
      <span> <?php echo $banner_text ?> <span> → </span>
    </a>
  </div>
  <div>
    <button class="ma-banner__close" aria-label="Zatvori">&times;</button>
  </div>
</div>