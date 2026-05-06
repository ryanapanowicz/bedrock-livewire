<?php

/**
 * Theme filters.
 */

namespace App;

use stdClass;

/**
 * Ensure REST post objects include expected fallback fields.
 * Fixes the issue with Acorn turning PHP warnings into errors
 * when WP doesn't check if object properties exist.
 */
add_action('rest_api_init', function () {
    foreach (get_post_types(['show_in_rest' => true]) as $post_type) {
        add_filter("rest_pre_insert_{$post_type}", function (stdClass $post): stdClass {
            $post->id ??= $post->ID ?? 0;
            $post->post_parent ??= 0;
            return $post;
        });
    }
});

/**
 * Remove the "No fields assigned" message from ACF blocks.
 */
add_filter('acf/blocks/no_fields_assigned_message', '__return_empty_string');