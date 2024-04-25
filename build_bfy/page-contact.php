<?php
/**
 * Template Name: Contact
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
    addBannerImage(get_kenza_title(), get_kenza_subtitle(), true);
    ?>
    <section class="contactform">
        <?php the_content(); ?>
    </section><div class="map">

    </div><?php
    get_template_part('template-parts/content', 'form');
    addComponent('share');
}




$categoryname = kenza_get_category_name();

if (!empty($categoryname)) {
    addComponent('crosslinks', [
        'count' => '2',
        'category_name' => $categoryname
    ]);
}
addComponent('footforms', [
    'email' => 'kenza_emailform',
    'chat' => 'kenza_chat'
]);?>
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
