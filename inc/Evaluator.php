<?php

namespace MarketingAutomation;

use FormsComputedLanguage\LanguageRunner;

class Evaluator
{
  public static function decide(): string
  {
    $code = get_option('ma_fcl_code', 'return "none";');

    $metrics = [];

    if (isset($_COOKIE['ma_metrics'])) {
      $decoded = json_decode(stripslashes($_COOKIE['ma_metrics']), true);
      if (is_array($decoded)) $metrics = $decoded;
    }

    // Number of visits
    $visits = isset($_COOKIE['ma_visits']) ? (int) $_COOKIE['ma_visits'] : 1;

    try {

      $lr = new LanguageRunner();
      $lr->setCode($code);
      $lr->setVars([
        'time_on_page' => (int) ($metrics['time_on_page'] ?? 0),
        'clicks' => (int) ($metrics['clicks'] ?? 0),
        'visits' => $visits,
        'is_mobile' => Helpers::is_mobile(),
        'country'   => Helpers::get_country()
      ]);

      $lr->setAllowedConstants(['true', 'false']);
      $lr->setConstantBehaviour('whitelist');

      $lr->evaluate();
      $vars = $lr->getVars();

      return $vars['return'] ?? 'none';
    } catch (\Throwable $e) {
      error_log('FCL evaluation failed' . $e->getMessage());
      return 'none';
    }
  }
}
