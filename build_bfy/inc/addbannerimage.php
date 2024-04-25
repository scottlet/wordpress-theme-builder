<?php
// phpcs:disable PSR1.Files.SideEffects
/**
 * Custom functions for Kenza website.
 *
 * @link https://ojoho.co/
 *
 * @package kenza
 */

global $version;

function kenza_add_mobile_featured_images($featured_images)
{
    $args_1 = array(
        'id' => 'featured-image-mobile',
        'desc' => 'Optional featured image for mobile',
        'label_name' => 'Mobile Featured Image',
        'label_set' => 'Set mobile featured image',
        'label_remove' => 'Remove mobile featured image',
        'label_use' => 'Set mobile featured image',
        'post_type' => array( 'page', 'post', 'careers' ),
    );

    $featured_images[] = $args_1;

    return $featured_images;
}

add_filter('kdmfi_featured_images', 'kenza_add_mobile_featured_images');

function doHeaderTitle($header, $tag, $subtitle, $small)
{
    $ret = '';
    $ret .= '<div class="vcenter">';
    $ret .= '<' . $tag . '>'.$header.'</' . $tag . '>';
    if ($subtitle) {
        if ($small) {
            $ret .= '<p>' . $subtitle . '</p>';
        } else {
            $ret .= '<h2>' . $subtitle . '</h2>';
        }
    }
    $ret .= '</div>';

    return $ret;
}

function addBannerImage($header = '', $subtitle = '', $title = false, $small = false, $showvideo = true)
{
    echo getBannerImage($header, $subtitle, $title, $small, $showvideo);
}

function kenza_get_image_set($imageid, $size)
{
    $image = wp_get_attachment_image_src($imageid, $size);

    if ($image) {
        list($src, $width, $height) = $image;
        $imagemetadata = wp_get_attachment_metadata($imageid);
        if (is_array($imagemetadata)) {
            $size_array = array(absint($width), absint($height));
            $srcset = wp_calculate_image_srcset($size_array, $src, $imagemetadata, $imageid);

            return $srcset;
        }
    }
}

function get_thumbs_with_mobile($mobileimageid, $size)
{
    global $post;
    $ret = '';
    $src = get_the_post_thumbnail_url($post->ID, $size);
    $mobileimages = kenza_get_image_set($mobileimageid, $size);
    $desktopimages = kenza_get_image_set(get_post_thumbnail_id(), $size);
    $ret .= '<picture>';
    $ret .= '<source sizes="100vw" media="(max-width: 767px)" srcset="'.esc_attr($mobileimages).'">';
    $ret .= '<source sizes="100vw" media="(min-width: 767px)" srcset="'.esc_attr($desktopimages).'">';
    $ret .= '<img src="'.$src.'" />';
    $ret .= '</picture>';

    return $ret;
}

function getBannerImage($header = '', $subtitle = '', $title = false, $small = false, $showvideo = true)
{
    $ret = '';
    $mobileimage = kdmfi_has_featured_image('featured-image-mobile');
    $headclasses='';
    $videos = [];
    $tag = 'h1';
    if ($title) {
        $headclasses = ' main-title';
    }
    if ($showvideo) {
        $videos = get_kenza_video_for_page();
    }

    if (has_post_thumbnail() && empty($videos)) {
        $ret .= '<div data-transition="parallax" class="poster'.$headclasses.'">';
        $size = $small ? 'bigger1' : 'bigger3';
        if (empty($mobileimage)) {
            $ret .= get_the_post_thumbnail(null, $size);
        } else {
            $ret .= get_thumbs_with_mobile($mobileimage, $size);
        }
        if ($header || $subtitle) {
            $ret .= doHeaderTitle($header, $tag, $subtitle, $small);
        }
        $ret .= '<a class="scrolldown" href="#down">scroll down</a>';
        $ret .= '</div>';
    }

    if ($videos) {
        $ret .= renderVideos($videos, $headclasses, $header, $tag, $subtitle, $small);
    }

    return $ret;
}
