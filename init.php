<?php

namespace k1;

function rbe(...$params) {
  static $instance;

  if (!$instance) {
    require_once apply_filters('k1rbe_plugin_class', 'class/class.reusable-blocks-extended.php');

    $instance = new ReusableBlocksExtended(...$params);
  }

  return $instance;
}

rbe();
