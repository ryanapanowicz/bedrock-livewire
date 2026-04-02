<?php
/**
 * Lativ Child functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Lativ Child
 * @since Lativ 1.0
 */

/**
 * Die if Composer is not installed.
 */
if (!file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

/**
 * Handle autoloading.
 */
require $composer;

use Illuminate\Support\Facades\Blade;

/**
 * Die if Acorn is not installed.
 */
if (!function_exists('\Roots\bootloader')) {
    wp_die(
        __('You need to install Acorn to use this site.', 'domain'),
        '',
        [
            'link_url' => 'https://roots.io/acorn/docs/installation/',
            'link_text' => __('Acorn Docs: Installation', 'domain'),
        ]
    );
}

/**
 * Bootstraps WordPress with Acorn.
 */
add_action('after_setup_theme', fn() => Roots\bootloader()->boot());

/**
 * Add Livewire styles
 */
add_filter('wp_head', function () {
    echo Blade::render('@livewireStyles');
});

/**
 * Add Livewire scripts
 */
add_filter('wp_footer', function () {
    echo Blade::render('@livewireScripts');
});

/**
 * Register Gutenberg blocks.
 */
add_action('init', function () {
    // Directory containing the blocks, within the 'resources/views' directory.
    $directory = resource_path('views').'/blocks/';

    // Iterate over the directory provided and look for blocks.
    $block_directory = new DirectoryIterator($directory);

    foreach ($block_directory as $block) {
        if ($block->isDir() && !$block->isDot()) {
            register_block_type($block->getRealpath(), [
                'render_callback' => function (
                    array $block,
                    string $content = '',
                    bool $is_preview = false,
                    int $post_id = 0,
                    ?WP_Block $wp_block = null,
                    array $context = []
                ) {
                    $slug = str_replace('test'.'/', '', $block['name']);
                    $block['slug'] = $slug;
                    echo \Roots\view(
                        'blocks.'.$block['slug'].'.'.$block['slug'],
                        compact(
                            'block',
                            'content',
                            'is_preview',
                            'post_id',
                            'wp_block',
                            'context'
                        )
                    );
                }
            ]);
        }
    }
});
