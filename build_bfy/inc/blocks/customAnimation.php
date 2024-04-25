<?php
// phpcs:disable PSR1.Files.SideEffects
/**
 * URI: https://github.com/WordPress/gutenberg-examples
 * Description: This is a plugin demonstrating how to register new blocks for the Gutenberg editor.
 * Version: 1.1.0
 * Author: the Gutenberg Team
 *
 * @package kenza-blocks
 */

defined('ABSPATH') || exit;

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function custom_animation()
{
    register_block_type('kenza/custom-animation', array(
        'editor_script' => 'blocks-js',
        'editor_style' => 'blocks-css'
    ));
}

add_action('init', 'custom_animation', 10);
