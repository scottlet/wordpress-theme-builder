<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kenza
 */

get_header();
addComponent('top-navigation');
?>
<main>
<?php

while (have_posts()) {
    the_post();
    get_template_part('template-parts/content', 'page');
}

$mainformshortcode = get_kenza_hubspot_mainform_shortcode();

$categoryname = kenza_get_category_name();

if (!empty($categoryname)) {
    addComponent('crosslinks', [
        'count' => '2',
        'category_name' => $categoryname
    ]);
}

addComponent('share');
addComponent('footforms', [
    'email' => 'kenza_emailform',
    'chat' => 'kenza_chat'
]);?>
</main>
<?php
if (!empty($mainformshortcode)) {
    addComponent('hsform', [
        'title'     => get_kenza_hubspot_mainform_title(),
        'text'      => get_kenza_hubspot_mainform_text(),
        'shortcode' => get_kenza_hubspot_mainform_shortcode(),
        'error'     => get_kenza_hubspot_mainform_error_message()
    ]);
}
get_footer();
