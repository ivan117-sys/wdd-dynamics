<?php

namespace MarketingAutomation;

class Setup
{
  public static function init()
  {
    add_action('init', [__CLASS__, 'register_options']);
  }

  public static function register_options()
  {
    register_setting('ma_settings', 'ma_fcl_code');
    register_setting('ma_settings', 'ma_enable_modal');
    register_setting('ma_settings', 'ma_enable_banner');
    register_setting('ma_settings', 'ma_banner_text');
    register_setting('ma_settings', 'ma_banner_link');
  }
}
