<?php
/**
 * Template Name: Services
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
    $category_name = null;
    $sectionclass = '';
    while (have_posts()) {
        the_post();
        get_template_part('template-parts/content', 'page');
        $category_name = kenza_get_category_name();
    }?>
    <div class="services">
        <?php
        addComponent('crosslinks', [
            'count' => '8',
            'category_name' => null,
            'alt_class' => 'alt',
            'layout' => 'services'
        ]);
        ?>
    </div>
<?php
$mainformshortcode = get_kenza_hubspot_mainform_shortcode();
addComponent('footforms', [
    'email' => 'kenza_emailform',
    'chat' => 'kenza_chat'
]);
addComponent('share');
if ($category_name) {
    addComponent('crosslinks', [
        'count' => '2',
        'category_name' => $category_name
    ]);
}
?>
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
