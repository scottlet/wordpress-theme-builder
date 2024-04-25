<?php
/**
 * Template Name: Careers
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
$no_positions = '';
$sectionclass = ' narrow';
while (have_posts()) {
    the_post();
    get_template_part('template-parts/content', 'page');
    $no_positions = get_kenza_no_positions();
}
$cat_ID = kenza_get_category_ID();
$categories = get_categories(
    array(
        'parent' => $cat_ID,
        'hide_empty' => false
    )
);
$args = array(
        'post_type' => 'careers',
        'posts_per_page' => 20,
        'orderby' => 'category'
    );
    $the_query = new WP_Query($args);
?>
<section class="jobs" data-transition="slideup" data-transition-include="through">
<?php
if ($the_query->have_posts()) {
    foreach ($categories as $category) {
        $hasjob = false;
        echo '<div class="cat"><h3>' . $category->name . '</h3><p>';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            if (kenza_get_category_name() === $category->slug) {
                $hasjob = true;
                get_template_part('template-parts/content', 'link');
                echo '<br />';
            }
        }

        if (!$hasjob) {
            echo $no_positions;
        }

        echo '</p></div>';
        $the_query->rewind_posts();
    }

    $the_query->the_post();
    wp_reset_postdata();
} ?>
</section>
<?php

get_template_part('template-parts/content', 'form');

$categoryname = kenza_get_category_name();
addComponent('footforms', [
    'email' => 'kenza_emailform',
    'chat' => 'kenza_jobs_chat'
]);
addComponent('share');
if (!empty($categoryname)) {
    addComponent('crosslinks', [
        'count' => '2',
        'category_name' => $categoryname
    ]);
}
?>
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
