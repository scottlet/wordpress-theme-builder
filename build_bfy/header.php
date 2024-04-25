<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package kenza
 */
global $version;
global $template_location_uri;
global $environment;

$appleicons = ['57', '60', '72', '76', '114', '120', '144', '152', '180'];
$androidicons = ['36', '48', '72', '96', '144', '192'];
$defaulticons = ['16', '32', '96'];

?><!DOCTYPE html>
<html <?php
language_attributes();
if (kenza_is_front_page()) {
    ?> class=""<?php // include `fixed` for old behaviour
}
?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<link rel="preload" href="<?php echo $template_location_uri. '/css/kenza-'. $version .'.min.css'?>"
    as="style" />
<link rel="preload" href="<?php echo $template_location_uri. '/js/kenza-'. $version .'.min.js'?>"
    as="script" />
<link href="https://kenza.site" rel="preconnect" crossorigin>
<link href="https://www.googletagmanager.com" rel="preconnect">
<link href="https://www.google-analytics.com" rel="preconnect">
<link href="https://forms.hsforms.com" rel="preconnect">
<link href="https://js.hsforms.net" rel="preconnect">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>">
<meta name="title" content="<?php bloginfo('name'); ?>">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
<?php wp_head(); ?>
<?php
foreach ($appleicons as $icon) {
    ?>
<link rel="apple-touch-icon" sizes="<?php echo $icon?>x<?php echo $icon?>" href="<?php
echo $template_location_uri;
?>/images/social-icons/apple-icon-<?php echo $icon?>x<?php echo $icon?>.png">
    <?php
}

foreach ($androidicons as $icon) {
    ?>
<link rel="icon" type="image/png" sizes="<?php echo $icon?>x<?php echo $icon?>" href="<?php
echo $template_location_uri;
?>/images/social-icons/android-icon-<?php echo $icon?>x<?php echo $icon?>.png">
    <?php
}

foreach ($defaulticons as $icon) {
    ?>
<link rel="icon" type="image/png" sizes="<?php echo $icon?>x<?php echo $icon?>" href="<?php
echo $template_location_uri;
?>/images/social-icons/favicon-<?php echo $icon?>x<?php echo $icon?>.png">
    <?php
}?>

<link rel="icon" type="image/png"
    href="<?php echo $template_location_uri; ?>/images/social-icons/favicon-32x32.png">

<meta name="theme-color" content="#000">
<script>
    (function(d, c, h) {
        function m() {
            var r = /__hstc/.test(d.cookie);
            if (!r) {
                try {
                    r = localStorage.getItem('cookie-check');
                } catch (ex) {
                    console.error('no local storage');
                }
            }

            return r;
        }

        c = ['js', 'apending'];
        h = d.querySelector('html');
        if (h.className !== '') {
            c.push(h.className);
        } if (window.chrome) {
            c.push('chrome');
        }
        if (!m()) {
            c.push('cookie');
        }
        h.className = c.join(' ');
        d.addEventListener('DOMContentLoaded', function() {
            h.className += ' js-ready';
        });
    } (document));
</script>
<?php dynamic_sidebar('kenza_scripts_header'); ?>
</head>
<?php
global $bodyclass;
$extraclass='';
if (!empty($bodyclass)) {
    $extraclass = $bodyclass;
}
?>
<body <?php body_class($extraclass); ?>>
    <?php dynamic_sidebar('kenza_scripts_body'); ?>
    <a name="top"></a>
    <input type="checkbox" id="main-form" class="form-opener" value="foo"/>
