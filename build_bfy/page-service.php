<?php
/**
 * Template Name: Single Service Page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kenza
 */
global $bodyclass;
global $noposter;
$bodyclass='service';
$noposter = true;
get_header();
addComponent('top-navigation');
$prev = previous_page_not_post();
$next = next_page_not_post();
?>
<main>
<?php
while (have_posts()) {
    the_post();
    addBannerImage(get_kenza_title(), get_kenza_subtitle(), false, false, false);
    //$sizes = get_intermediate_image_sizes($postid);
    //console_log($sizes);
    ?>
        <section class="main" data-transition="slideup" data-transition-include="through">
            <h3><?php echo get_kenza_opening_sentence() ?></h3>
        </section>
        <section class="main narrow" data-transition="slideup" data-transition-include="through">
        <?php
        the_content();
        get_template_part('template-parts/content', 'form');
        ?></section><p class="links" data-transition="slideup">
            <span class="elink left"><?php echo $prev ? $prev : '' ?></span>
            <span class="elink center"><a href="/services/">Services</a></span>
            <span class="elink right"><?php echo $next ? $next : '' ?></span>
        </p>

        <?php
        addComponent('share');
}

$mainformshortcode = get_kenza_hubspot_mainform_shortcode();

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
]);

if ($category_name) {
    addComponent('crosslinks', [
        'count' => '2',
        'category_name' => $category_name
    ]);
}
?>
</div>
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
