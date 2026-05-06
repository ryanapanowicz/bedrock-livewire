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

use Roots\Acorn\Application;

/**
 * Die if Acorn is not installed.
 */
if (!class_exists(Application::class)) {
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
Application::configure()
    ->withRouting(wordpress: true)
    ->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/
collect(['setup', 'filters', 'blocks'])
    ->each(function ($file) {
        if (!locate_template($file = "app/{$file}.php", true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });
