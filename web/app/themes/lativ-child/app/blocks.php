<?php

/*
|--------------------------------------------------------------------------
| Render Custom Blocks via Blade
|--------------------------------------------------------------------------
|
| ref: https://discourse.roots.io/t/register-an-acf-block-with-modern-practices/25887/5
|
*/

namespace App;

use DirectoryIterator;
use Throwable;
use WP_Block;

if (!defined('THEME_BLOCK_SLUG')) {
    define('THEME_BLOCK_SLUG', 'theme');
}

/**
 * Enable custom blocks for theme.
 */
add_action('init', function () {
    // Directory containing the blocks, within the 'resources/views' directory.
    $directory = resource_path('views').'/blocks/';

    // Iterate over the directory provided and look for blocks.
    $block_directory = new DirectoryIterator($directory);

    foreach ($block_directory as $block) {
        if ($block->isDir() && !$block->isDot()) {
            register_block_type($block->getRealpath(), ['render_callback' => blade_render_callback(...)]);
        }
    }
});

/**
 * Callback for rendering Blade templates.
 * Name of the Blade template at resources/views/blocks/{title}.blade.php
 *
 * @link https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/
 *
 * @param  array  $block
 * @param  string  $content
 * @param  bool  $is_preview
 * @param  int  $post_id
 * @param  WP_Block|null  $wp_block
 * @param  array  $context
 * @return void
 * @throws Throwable
 */
function blade_render_callback(
    array $block,
    string $content = '',
    bool $is_preview = false,
    int $post_id = 0,
    ?WP_Block $wp_block = null,
    array $context = []
): void {
    $slug = str_replace(THEME_BLOCK_SLUG.'/', '', $block['name']);
    $block['slug'] = $slug;

    echo \Roots\view('blocks.'.$block['slug'].'.'.$block['slug'], compact(
        'block',
        'content',
        'is_preview',
        'post_id',
        'wp_block',
        'context'
    ))->render();
}
