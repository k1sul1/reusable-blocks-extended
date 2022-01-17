# Reusable Blocks Extended

Small WordPress plugin that adds reusable blocks to the menu and exposes them under an easy API. And shortcode, if you need it.

## Installation

Copy the plugin into the plugins folder. Or;

```
composer require k1sul1/reusable-blocks-extended
```

## Usage

Should be straightforward enough?

```
[reusableblock id="123" slug="hello-world"]
```

```
$rbe = \k1\rbe();
$rbe->renderBlock("footer");
$rbe->renderBlock(123, "id");
```

## Filters

Plenty. If you feel like you need translations, don't like my words of choice, or some other thing; change it.

```
$ ag -Q apply_filters
init.php
9:    require_once apply_filters('k1rbe_plugin_class', 'class/class.reusable-blocks-extended.php');

class/class.reusable-blocks-extended.php
18:    $this->icon = apply_filters("k1rbe_menu_icon", "dashicons-block-default");
19:    $this->capability = apply_filters("k1rbe_menu_capability", "edit_posts");
20:    $this->menuTitle = apply_filters("k1rbe_menu_title", "Reusable blocks");
21:    $this->shortcode = apply_filters("k1rbe_shortcode", "reusableblock");
22:    $this->strings = apply_filters("k1rbe_strings", $this->strings);
63:    $output = apply_filters("k1rbe_before_output", "");
69:    echo apply_filters("k1rbe_after_output", $output);
```

## Contributing

If something is broken and you know how to fix it, please do and send the fix as a PR.

Cheers.

## Licence

I don't care, do whatever you want.
