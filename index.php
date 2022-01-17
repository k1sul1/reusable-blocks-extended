<?php
/**
 * Plugin name: k1 Reusable Blocks Extended
 * Plugin URI: https://github.com/k1sul1/k1-reusable-blocks-extended
 * Description: Exposes the "Reusable blocks" page to the admin menu and provides a way to call them with shortcodes or PHP.
 * Version: 1.0
 * Author: @k1sul1
 * Author URI: https://github.com/k1sul1/
 * License: MIT
 * Text Domain: k1rbe
 *
 */

if (!defined("ABSPATH")) {
  die("You're not supposed to be here.");
}

function k1rbe_version_problems($isNetwork = null) {
  $php_version = phpversion();
  $wp_version = $GLOBALS['wp_version'];
  $php_over_7 = version_compare($php_version, 7.3, '>=');
  $wp_ok = version_compare($wp_version, 5.8, '>=');
  $message = "";

  if (!$php_over_7) {
    $message .= "Minimum PHP version required is 7.3. Yours is {$php_version}. ";
  } elseif (!$wp_ok) {
    $message .= "Minimum WP version required is 5.8. Yours is {$wp_version}. ";
  }

  if ($isNetwork) {
    $message .= "Plugin must be activated on each site separately.";
  }

  if (empty($message)) {
    return false;
  }

  return $message;
}

function k1rbe_on_activate() {
  $problems = k1rbe_version_problems();

  if ($problems) {
    deactivate_plugins(basename(__FILE__));
    wp_die($problems);
  }

  add_action("shutdown", 'flush_rewrite_rules');
}

function k1_k1rbe_on_deactivate() {
  flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'k1rbe_on_activate');
register_deactivation_hook(__FILE__, 'k1_k1rbe_on_deactivate');

$problems = k1rbe_version_problems();
if ($problems) {
  deactivate_plugins(basename(__FILE__));
  wp_die($problems);
} else {
  require_once 'init.php';
}
