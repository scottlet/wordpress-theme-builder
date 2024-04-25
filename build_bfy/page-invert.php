<?php
/**
 * Template Name: Inverted no poster
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
    the_post();?>
    <div class="invert fakeposter">
    <h1><?php the_title()?></h1>
    <?php the_content();?>
</div>
    <?php
}

$mainformshortcode = get_kenza_hubspot_mainform_shortcode();

$categoryname = kenza_get_category_name();
addComponent('share');
if (!empty($categoryname)) {
    addComponent('crosslinks', [
        'count' => '2',
        'category_name' => $categoryname
    ]);
}
// addComponent('footforms', [
//     'email' => 'kenza_emailform',
//     'chat' => 'kenza_chat'
// ]);?>
</main>
<?php

if (!empty($mainformshortcode)) {
    addComponent('hsform', [
        'title'     => get_kenza_hubspot_mainform_title(),
        'shortcode' => get_kenza_hubspot_mainform_shortcode(),
        'error'     => get_kenza_hubspot_mainform_error_message()
    ]);
}
get_footer();
