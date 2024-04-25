<?php
/**
 * Template Name: Case
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
$existing = get_post_meta($post->ID, 'kenza_page_class', true);
$invert = get_post_meta($post->ID, 'kenza_page_inverted', true);
$widelayout = get_post_meta($post->ID, 'kenza_page_widelayout', true);

$bodyclass = 'case';

if ($invert == 'yes') {
    $bodyclass .= ' invert';
}

if ($widelayout == 'yes') {
    $bodyclass .= ' wide-layout';
}

if (!empty($existing)) {
    $bodyclass .= ' ' . $existing;
}
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

if (empty($previd)) {
    $previd = end($ids);
}

if (empty($nextid)) {
    $nextid = $ids[0];
}

$prevlink = get_permalink($previd);
$nextlink = get_permalink($nextid);

$prevtitle = get_the_title($previd);
$nexttitle = get_the_title($nextid);

$previmage = get_the_post_thumbnail($previd, 'medium');
$nextimage = get_the_post_thumbnail($nextid, 'medium');


//console_log($ids);

//console_log('key '.$key.', prev '.$previd.', next '.$nextid);

?>
<main>
<?php
while (have_posts()) {
    the_post();
}
?>
    <section class="content" data-transition="slideup" data-transition-include="through">
        <?php the_content(); ?>
    </section>
    <section class="blocklinks" data-transition="slideup">
        <h3>Explore more</h3>
        <div class="inner">
            <a href="<?php echo $prevlink ?>">
                <?php echo $previmage ?>
                <i class="icon nav-left-arrow">&lt;</i><?php echo $prevtitle?>
            </a><a href="<?php echo $nextlink ?>">
                <?php echo $nextimage ?>
                <?php echo $nexttitle?><i class="icon nav-right-arrow">&gt;</i>
            </a>
            <a class="center" href="/our-work/">Work</a>
        </div>
    </section>
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
