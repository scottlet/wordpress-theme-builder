<?php
/**
 * Template Name: Work
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
$bodyclass= 'work';
get_header();
addComponent('top-navigation');
?>
<main>
<?php

while (have_posts()) {
    the_post();
    addBannerImage(get_kenza_title(), get_kenza_subtitle(), true);
    ?>
    <section class="main" data-transition="slideup" data-transition-include="through">
        <?php the_content(); ?>
    </section><?php
}

addComponent('footforms', [
    'email' => 'kenza_emailform',
    'chat' => 'kenza_chat'
]);
addComponent('share');
$categoryname = kenza_get_category_name();

if (!empty($categoryname)) {
    addComponent('crosslinks', [
        'count' => '2',
        'category_name' => $categoryname
    ]);
}?>
</main>
<?php
$mainformshortcode = get_kenza_hubspot_mainform_shortcode();
if (!empty($mainformshortcode)) {
    addComponent('hsform', [
        'title'     => get_kenza_hubspot_mainform_title(),
        'shortcode' => get_kenza_hubspot_mainform_shortcode(),
        'error'     => get_kenza_hubspot_mainform_error_message()
    ]);
}
get_footer();
