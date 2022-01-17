<?php

namespace k1;

class ReusableBlocksExtended {
  public $icon;
  public $capability;
  public $menuTitle;
  public $shortcode;
  public $strings = [
    "notFound" => "No block was found with the provided attributes. Please recheck them.",
    "noIdentifier" => "No block id or slug supplied! Cannot show reusable block.",
    "invalidShortcode" => "Shortcode options are invalid. You must provide either ID or the slug of the block.",
    "help" => "Help",
  ];

  public function __construct() {
    $this->icon = apply_filters("k1rbe_menu_icon", "dashicons-block-default");
    $this->capability = apply_filters("k1rbe_menu_capability", "edit_posts");
    $this->menuTitle = apply_filters("k1rbe_menu_title", "Reusable blocks");
    $this->shortcode = apply_filters("k1rbe_shortcode", "reusableblock");
    $this->strings = apply_filters("k1rbe_strings", $this->strings);

    \add_action('admin_menu', [$this, 'addToMenu']);
    \add_shortcode($this->shortcode, [$this, 'shortcodeCallback']);
  }

  public function renderBlock($identifier = null, $property = "slug") {
    // Forcing $property as "id" if it isn't a supported option
    if (!in_array($property, ["slug", "id"])) {
      $property = "id";
    }

    if (!$identifier) {
      echo $this->strings["noIdentifier"];
      return;
    }

    if ($property === "slug") {
      $posts = \get_posts([
        "post_type" => "wp_block",
        "name" => $identifier,
        "posts_per_page" => 1,
      ]);


      $block = array_shift($posts);
    } else {
      $posts = \get_posts([
        "post_type" => "wp_block",
        "include" => [$identifier],
        "posts_per_page" => 1,
      ]);

      $block = array_shift($posts);
    }

    if (!$block) {
      echo $this->strings["notFound"];
      return;
    }

    $blocks = \parse_blocks($block->post_content);
    $output = apply_filters("k1rbe_before_output", "");

    foreach ($blocks as $block) {
      $output .= \render_block($block);
    }

    echo apply_filters("k1rbe_after_output", $output);
  }

  public function addToMenu() {
    $pageTitle = $this->menuTitle; // Irrelevant as WP overrides it
    $menuTitle = $this->menuTitle;
    $cap = $this->capability;
    $slug = "edit.php?post_type=wp_block";
    $icon = $this->icon;

   $x = \add_menu_page(
      $pageTitle,
      $menuTitle,
      $cap,
      $slug,
      "", // The page already exists
      $icon
    );

    \add_submenu_page(
      $slug,
      $this->menuTitle . " " . $this->strings["help"],
      $this->strings["help"],
      $this->capability,
      'help',
      [$this, 'renderHelp']
    );
  }

  public function shortcodeCallback($attr) {
    $attr = shortcode_atts([
      "id" => null,
      "slug" => null,
    ], $attr, $this->shortcode);

    if (is_admin()) {
      return;
    }

    ob_start();
    if (!empty($attr["id"])) {
      $this->renderBlock($attr["slug"], "id");
    } else if (!empty($attr["slug"])) {
      $this->renderBlock($attr["slug"], "slug");
    } else {
      echo $this->strings["invalidShortcode"];
    }

    return ob_get_clean();
  }

  public function renderHelp() {
    ?>
    <h1>Reusable Blocks Extended</h1>
    <p>Makes reusable blocks available via shortcode or PHP API.</p>
    <p>Don't use the shortcode to display blocks inside Gutenberg containers, as Gutenberg UI already supports reusable blocks, that's what this plugin builds on.</p>
    <p>Do use shortcodes in Classic Editor containers, ACF fields, etc. Anywhere you can use shortcodes really.</p>

    <h2>Shortcode</h2>
    <code style="display: block">[<?=esc_html($this->shortcode)?> id="123" slug="hello-world"]</code>

    <p>Provide either slug or id.</p>

    <h2>PHP API</h2>
    <code style="display: block">$rbe = \k1\rbe(); <br>
      $rbe->renderBlock("footer");<br>
      $rbe->renderBlock(123, "id");</code>
    <?php
  }
}
