<?php
// phpcs:disable PSR1.Files.SideEffects
/**
 * Enqueue scripts and styles.
 */
function kenza_scripts()
{
    global $version;
    global $template_location_uri;
    global $environment;

    $ver = null;
    $date = new DateTime();
    if ($environment == 'development') {
        $ver = date_timestamp_get($date);
    }


    wp_enqueue_style(
        'kenza-style',
        $template_location_uri. '/css/kenza-'. $version .'.min.css',
        null,
        $ver
    );
    wp_enqueue_script(
        'kenza-js',
        $template_location_uri . '/js/kenza-'. $version .'.min.js',
        null,
        $ver,
        true
    );
    if (!is_admin()) {
        wp_deregister_script('jquery');
    }
}

function kenza_admin_scripts()
{
    global $version;
    global $template_location_uri;
    global $environment;

    $ver = null;
    $date = new DateTime();
    if ($environment == 'development') {
        $ver = date_timestamp_get($date);
    }


    wp_enqueue_style(
        'kenza-admin-style',
        $template_location_uri. '/css/admin-'. $version .'.min.css',
        null,
        $ver
    );
    wp_enqueue_script(
        'kenza-admin-js',
        $template_location_uri . '/js/admin-'. $version .'.min.js',
        null,
        $ver,
        true
    );
}

function kenza_defer_attribute($tag, $handle)
{
    if ('kenza-js' !== $handle) {
        return $tag;
    }
    return str_replace(' src', ' defer="defer" src', $tag);
}

function kenza_add_editor_styles()
{
    global $version;
    global $template_location_uri;
    add_editor_style('css/editor-' . $version . '.min.css');
}

require $template_location . '/inc/blocks.php';
