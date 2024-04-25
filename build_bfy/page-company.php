<?php
/**
 * Template Name: Company
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
$big = true;
//if (!kenza_is_front_page()) {
    $bodyclass= 'company';
//}
get_header();
// if (kenza_is_front_page()) {
//     addComponent('background', [
//         'classes' => 'bg r',
//         'options' => [
//             'logoAnimation' => true,
//             'invert' => true
//         ]
//     ]);
// }
addComponent('top-navigation');
?>

<main>
<?php
while (have_posts()) {
    the_post();?>
<input type="checkbox" id="social-share" />
    <?php
    addBannerImage(get_kenza_title(), get_kenza_subtitle(), true);
    $shareurl = urlencode(get_permalink() . '#video');
    $noform = true;
    $alt = ' alt';
    addComponent('share');
    ?>
        <section class="main" data-transition="slideup">
        <?php
        the_content();
        ?></section>
    <?php
    get_template_part('template-parts/content', 'form');
}

$args = array(
    'post_type' => 'clients',
    'posts_per_page' => 6 //, to change this to load as many as possible, 100.
    //'category_name' => 'company' // if above is changed, use category_name to filter

);
$the_query = new WP_Query($args);?>
<section class="work" data-transition="twist,slideup,up">
    <h2>Work that unites worlds</h2>
<?php
if ($the_query->have_posts()) {
    while ($the_query->have_posts()) {
        $the_query->the_post();
        the_post_thumbnail();
    }
    wp_reset_postdata();
}?>
</section>
<?php

addComponent('footforms', [
    'email' => 'kenza_emailform',
    'chat' => 'kenza_chat'
]);
$noform = false;
$shnum = '2';
$alt = '';
addComponent('share');

$categoryname = kenza_get_category_name();
if (!empty($categoryname)) {
    addComponent('crosslinks', [
        'count' => '2',
        'category_name' => get_the_category()[0]->slug
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
