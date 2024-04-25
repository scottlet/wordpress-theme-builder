<?php
/**
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
global $bodyclass;
global $noposter;
$bodyclass='career';
$prev = get_previous_post_link();
$next = get_next_post_link();
$categoryname = kenza_get_category_name();
get_header();
addComponent('top-navigation');
?>
<main>
<?php
while (have_posts()) {
    the_post();
    addBannerImage(get_kenza_title(), get_kenza_subtitle(), false, false, false);?>
    <section class="main" data-transition="slideup" data-transition-include="through">
        <h3><?php echo get_kenza_opening_sentence() ?></h3>
    </section>
    <section class="main narrow" data-transition="slideup" data-transition-include="through">
    <?php
    the_content();
    ?></section>
<?php }

$jobid = get_kenza_job_reference();
?><span data-jobid="<?php echo $jobid?>"></span><?php

$mainformshortcode = get_kenza_hubspot_mainform_shortcode();
get_template_part('template-parts/content', 'form');

if (!empty($mainformshortcode)) {
    addComponent('hsform', [
        'title'     => get_kenza_hubspot_applicationform_title(),
        'text'      => get_kenza_hubspot_applicationform_text(),
        'shortcode' => get_kenza_hubspot_mainform_shortcode(),
        'error'     => get_kenza_hubspot_mainform_error_message()
    ]);
}
?>
<p class="links" data-transition="slideup" data-transition-include="through">
    <span class="elink left"><?php echo $prev ? $prev : '' ?></span>
    <span class="elink center"><a href="/careers/">Careers</a></span>
    <span class="elink right"><?php echo $next ? $next : '' ?></span>
</p>
<?php
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

get_footer();
