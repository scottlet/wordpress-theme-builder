<?php
/**
 * Template Name: Insights
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
$bodyclass= 'insights';
$sticky = get_post_meta($post->ID, 'kenza_sticky_link_text', true);
get_header();
addComponent('top-navigation');

if ($post->post_parent) {
    $parent = $post->post_parent;
}

$pages = get_pages(array(
    'child_of'=>$parent
));
$ids = wp_list_pluck($pages, 'ID');

$key = array_search($post->ID, $ids);
$length = count($ids);

$offset = $key-1;
$previd = $ids[$offset > -1 ? $offset : $length - 1];

$offset = $key+1;
// console_log($length);
// console_log($offset);
$nextid = $ids[$offset < $length ? $offset : 0];

$prevlink = get_permalink($previd);
$nextlink = get_permalink($nextid);

$prevtitle = get_the_title($previd);
$nexttitle = get_the_title($nextid);
//
// $previmage = get_the_post_thumbnail($previd, 'medium');
// $nextimage = get_the_post_thumbnail($nextid, 'medium');
?>
<main>
<?php

while (have_posts()) {
    the_post();?>
    <?php
    $shareurl = urlencode(get_permalink());
    ?>
    <section class="main gutenberg" data-transition="slideup" data-transition-include="through">
        <?php
        the_content();
        ?></section>
        <?php if ($length>1) {?>
        <!-- <section class="blocklinks" data-transition="slideup">
            <div class="inner">
                <a href="<?php echo $prevlink ?>">
                    <i class="icon nav-left-arrow">&lt;</i><?php echo $prevtitle?>
                </a><a href="<?php echo $nextlink ?>">
                    <?php echo $nexttitle?><i class="icon nav-right-arrow">&gt;</i>
                </a>
                <a class="center" href="/insights/">Insights</a>
            </div>
        </section> -->
            <?php
        }
}

$mainformshortcode = get_kenza_hubspot_mainform_shortcode();

$categoryname = kenza_get_category_name();
addComponent('footforms', [
    'email' => 'kenza_emailform',
    'chat' => 'kenza_chat'
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
if (!empty($mainformshortcode)) {
    addComponent('hsform', [
        'title'     => get_kenza_hubspot_mainform_title(),
        'shortcode' => get_kenza_hubspot_mainform_shortcode(),
        'error'     => get_kenza_hubspot_mainform_error_message()
    ]);
}
get_footer();
