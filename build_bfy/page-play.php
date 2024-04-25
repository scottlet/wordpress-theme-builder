<?php
/**
 * Template Name: Play
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
$noform = true;
addComponent('top-navigation');
?><main>
<div class="play-overlay invert">
    <div class="vcenter">
        <h3><?php echo get_kenza_title() ?></h3>
        <i>hand</i>
    </div>
</div>
<?php
$existingkey = get_query_var('q');
$data = null;
if ($existingkey === '') {
    $newkey = get_query_var('q', get_key());
} else {
    $newkey = rawurlencode($existingkey);
    $data = kenza_load_play_data($existingkey);
}
$shareurl = get_permalink(). $newkey;

addComponent('background', [
    'classes' => 'bg r play',
    'options' => [
        'play' => true,
        'autoplay' => $existingkey == '' ? false : true,
        'invert' => true
    ]
]);

addComponent('footforms', [
    'email' => 'kenza_emailform',
    'chat' => 'kenza_chat'
]);
$noform = false;
$shareurl = null;
addComponent('share');
$categoryname = kenza_get_category_name();
if (!empty($categoryname)) {
    addComponent('crosslinks', [
        'count' => '2',
        'category_name' => get_the_category()[0]->slug
    ]);
}
?>

<div
    data-nonce="<?php echo wp_create_nonce('play_key') ?>"
    data-key="<?php echo $newkey ?>" <?php echo $data !== null ? 'data-audio="'.$data.'"' : ''?>>
</div>
</main>
<?php
get_footer();
