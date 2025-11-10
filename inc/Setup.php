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
    // options: FCL kod + toggleovi
    register_setting('ma_settings', 'ma_fcl_code');     // FCL pravila
    register_setting('ma_settings', 'ma_enable_modal'); // bool
    register_setting('ma_settings', 'ma_enable_banner'); // bool
  }
}
